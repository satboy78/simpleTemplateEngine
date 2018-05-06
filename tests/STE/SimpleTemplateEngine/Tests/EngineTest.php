<?php

namespace STE\SimpleTemplateEngine\Tests;

use STE\SimpleTemplateEngine\Engine;

class EngineTest extends \PHPUnit\Framework\TestCase
{
    public function testParseTemplate()
    {
        $fileContents = 'Hey {{Name}}, here\'s a poem for you';
        $expectedResult = 'Hey Matteo, here\'s a poem for you';
        $templateVariables = array('Name' => 'Matteo');

        $engine = new Engine;
        $text = $engine->parseTemplate($fileContents, $templateVariables);

        $this->assertEquals(
            $text, 
            $expectedResult
        );

        $this->assertNotEquals(
            $text, 
            ''
        );
    }
}
