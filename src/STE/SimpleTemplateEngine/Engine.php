<?php
namespace STE\SimpleTemplateEngine;

class Engine
{
    // variables
    private $startPlaceholder = '{{';
    private $endPlaceholder = '}}';
    private $startCyclePlaceholder = '{{#each ';
    private $endCyclePlaceholder = '{{/each}}';
    private $startConditionalPlaceholder = '{{#unless @last}}';
    private $endConditionalPlaceholder = '{{/unless}}';
    private $elseConditionalPlaceholder = '{{else}}';

    /**
     * Extracts a string enclosed between two other strings
     *
     * Example: getBetween('##FOO##','##','##') will extract FOO
     *
     *
     * @param string $content - the whole input string
     * @param string $start - starting string
     * @param string $end - end string
     *
     * @return string  returns the middle remainder, or empty string
     */
    private function getBetween($content, $start ,$end)
    {
        $r = explode($start, $content);
        if (isset($r[1])){
            $r = explode($end, $r[1]);
            return $r[0];
        }
        return '';
    }

    /**
     * Replaces a placeholder with actual contents
     *
     * Example: replacePlaceholders('##FOO##',array('FOO' => 'TEXT')) will replace FOO with TEXT
     *
     *
     * @param string $content - the whole input string
     * @param array $templateVariables - an array containing template variables
     *
     * @return string  returns the modified content
     */
    private function replacePlaceholders($content, $templateVariables)
    {
        if (strpos($content, $this->startPlaceholder) !== false) {
            $var = $this->getBetween($content, $this->startPlaceholder, $this->endPlaceholder);
            $content = str_replace(
                $this->startPlaceholder . $var . $this->endPlaceholder, 
                $templateVariables[$var], 
                $content
            );
        }
        return $content;
    }

    /**
     * Given a conditional placeholder, it will return the proper value, viven the matching condition
     *
     * Example: replaceConditionalPlaceholders('{{#unless @last}},{{else}}!{{/unless}}', true) will return !
     * Example: replaceConditionalPlaceholders('{{#unless @last}},{{else}}!{{/unless}}', false) will return ,
     *
     *
     * @param string $content - the whole conditional placeholder string
     * @param bool $matchedCondition - discrimitator for applying a condition or another of the conditional placeholder
     *
     * @return string  returns the proper conditional placeholder that matches the condition
     */
    function replaceConditionalPlaceholders($content, $matchedCondition = false)
    {
        $conditionalPosition = strpos($content, $this->startConditionalPlaceholder);
        $conditionalVar = '';
        if (!$matchedCondition) {
            $conditionalVar = $this->getBetween(
                $content, 
                $this->startConditionalPlaceholder, 
                $this->elseConditionalPlaceholder
            );
        } else {
            $conditionalVar = $this->getBetween(
                $content, 
                $this->elseConditionalPlaceholder, 
                $this->endConditionalPlaceholder
            );
        }
        return (substr($content, 0, $conditionalPosition) . $conditionalVar);
    }

    /**
     * Parse a tmpl file (sent here as a string), searcing for cycle conditions, and returns the modified text
     *
     *
     * @param string $handle - the whole template text, containing cyclic placeholders
     * @param array $templateVariables - an array containing template variables
     *
     * @return string  returns the modified text, with placeholders replaced by text coming from templateVariables
     */
    private function parseCycles($handle, $templateVariables)
    {
        while (strpos($handle, $this->startCyclePlaceholder) !== false) {
            $appendString = '';
            $var = $this->getBetween(
                $handle, 
                $this->startCyclePlaceholder, 
                $this->endPlaceholder
            );
            $pieces = $templateVariables[$var];
            $numPieces = count($pieces);
            $numIterations = 1;
            $innerContent = $this->getBetween(
                $handle, 
                $this->startCyclePlaceholder . $var . $this->endPlaceholder, 
                $this->endCyclePlaceholder
            );

            foreach ($pieces as $tempVars) {
                $tmpString = $innerContent;
                foreach ($tempVars as $key => $value) {
                    $tmpString = $this->replacePlaceholders($tmpString, array($key => $value));
                }
                if (strpos($tmpString, $this->startConditionalPlaceholder) !== false) {
                    $tmpString = $this->replaceConditionalPlaceholders(
                        $tmpString, 
                        $numIterations === $numPieces
                    );
                }
                $appendString .= $tmpString . PHP_EOL;
                $numIterations++;
            }
            $handle = str_replace(
                $this->startCyclePlaceholder . $var . $this->endPlaceholder . $innerContent . $this->endCyclePlaceholder, 
                $appendString, 
                $handle
            );
        }
        return $handle;
    }

    /**
     * Parse a tmpl file (sent here as a string) and returns the modified text
     *
     *
     * @param string $handle - the whole template text, containing placeholders
     * @param array $templateVariables - an array containing template variables
     *
     * @return string  returns the modified text, with placeholders replaced by text coming from templateVariables
     */
    public function parseTemplate($handle, $templateVariables) 
    {
        // parse cycles
        $handle = $this->parseCycles($handle, $templateVariables);

        // substitute simple variables, enclosed in {{ }}
        $handle = $this->replacePlaceholders($handle, $templateVariables);

        return $handle;
    }
}
