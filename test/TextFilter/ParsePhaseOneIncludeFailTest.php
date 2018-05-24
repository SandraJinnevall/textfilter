<?php

namespace Anax\TextFilter;

/**
 * Test negative
 */
class ParsePhaseOneIncludeFailTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Use only frontmatter yaml to start with.
     */
    private $options = [
        "include"               => true,
        "include_base"          => __DIR__ . "/../content",
        "frontmatter_json"      => false,
        "frontmatter_json"      => false,
        "frontmatter_yaml"      => false,
        "variables"             => false,
    ];



    /**
     * Include base path not set.
     *
     * @expectedException Anax\TextFilter\Exception
     */
    public function testIncludeBasePathNotSet()
    {
        $filter = new ParsePhaseOne();

        $text = <<<EOD
#include file1.md
EOD;

        $options = $this->options;
        $options["include_base"] = null;
        $filter->parse($text, $options);
    }



    /**
     * Incorrect include base path.
     *
     * @expectedException Anax\TextFilter\Exception
     */
    public function testIncorrectIncludeBasePath()
    {
        $filter = new ParsePhaseOne();

        $text = <<<EOD
#include file1.md
EOD;

        $options = $this->options;
        $options["include_base"] = "/NOSUCHDIR";
        $filter->parse($text, $options);
    }



    /**
     * Include file not found.
     *
     * @expectedException Anax\TextFilter\Exception
     */
    public function testIncludeFileNotFound()
    {
        $filter = new ParsePhaseOne();

        $text = <<<EOD
#include file1_NOTFOUND.md
EOD;

        $filter->parse($text, $this->options);
    }
}
