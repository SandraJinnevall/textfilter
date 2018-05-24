<?php

namespace Anax\TextFilter;

/**
 * Test negative
 */
class ParsePhaseOneYamlFrontmatterFailTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Use only frontmatter yaml to start with.
     */
    private $options = [
        "include"               => false,
        "frontmatter_json"      => false,
        "frontmatter_yaml"      => true,
        "variables"             => false,
        "yaml_parser_pecl"      => true,
        "yaml_parser_symfony"   => true,
        "yaml_parser_spyc"      => true,
    ];



    /**
     * Frontmatter without end marker.
     *
     * @expectedException Anax\TextFilter\Exception
     */
    public function testYamlWithoutEndMarker()
    {
        $filter = new ParsePhaseOne();

        $text = <<<EOD
---
title: hi
author: mos
EOD;

        $filter->parse($text, $this->options);
    }



    /**
     * Frontmatter with error, tested with Symfony parser.
     *
     * @expectedException Symfony\Component\Yaml\Exception\ParseException
     */
    public function testYamlWithError()
    {
        $filter = new ParsePhaseOne();

        $text = <<<EOD
---
fail: [true]true
---
EOD;

        $options = $this->options;
        $options["yaml_parser_pecl"] = false;
        $options["yaml_parser_spyc"] = false;
        $filter->parse($text, $options);
    }



    /**
     * Missing frontmatter parser.
     *
     * @expectedException Anax\TextFilter\Exception
     */
    public function testWithoutParser()
    {
        $filter = new ParsePhaseOne();

        $text = <<<EOD
---
title: hi
---
EOD;

        $options = $this->options;
        $options["yaml_parser_pecl"] = false;
        $options["yaml_parser_symfony"] = false;
        $options["yaml_parser_spyc"] = false;
        $filter->parse($text, $options);
    }
}
