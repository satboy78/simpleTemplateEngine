<?php
require __DIR__ . '/vendor/autoload.php';

use STE\Utilities\File;
use STE\SimpleTemplateEngine\Engine;

$templateVariables = array(
    'Name' => 'Matteo',
    'Stuff' => array(
        array(
        'Thing' => 'roses',
        'Desc'  => 'red'
        ),
        array(
        'Thing' => 'violets',
        'Desc'  => 'blue'
        ),
        array(
        'Thing' => 'you',
        'Desc'  => 'able to solve this'
        ),
        array(
        'Thing' => 'we',
        'Desc'  => 'interested in you'
        )
    )
);

class StartEngine
{
    public static function execute($argv, $templateVariables)
    {
        $file = new File;
        $fileContents = $file->openFile($argv);
        $engine = new Engine;
        $text = $engine->parseTemplate($fileContents, $templateVariables);
        echo $text;
    }

}

StartEngine::execute($argv, $templateVariables);
