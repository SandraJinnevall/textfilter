<?php

namespace Anax\TextFilter;

/**
 * Test negative
 */
class ParsePhaseOneJsonFrontmatterFailTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Use only frontmatter yaml to start with.
     */
    private $options = [
        "include"               => false,
        "frontmatter_json"      => true,
        "frontmatter_yaml"      => false,
        "variables"             => false,
    ];



    /**
     * Frontmatter without end marker.
     *
     * @expectedException Anax\TextFilter\Exception
     */
    public function testJsonWithoutEndMarker()
    {
        $filter = new ParsePhaseOne();

        $text = <<<EOD
{{{
{
    "title": "hi",
    "author": "mos"
}
EOD;

        $filter->parse($text, $this->options);
    }



    /**
     * Frontmatter with error.
     *
     * @expectedException Anax\TextFilter\Exception
     */
    public function testJsonWithError()
    {
        $filter = new ParsePhaseOne();

        $text = <<<EOD
{{{
{
    "title": "hi"
    "author": "mos"
}
}}}
EOD;

        $filter->parse($text, $this->options);
    }



//     /**
//      * Missing frontmatter parser.
//      *
//      * @expectedException Anax\TextFilter\Exception
//      */
//     public function testWithoutParser()
//     {
//         $filter = new ParsePhaseOne();
// 
//         $text = <<<EOD
// {{{
// {
//     title: "hi",
//     author: "mos"
// }
// }}}
// EOD;
// 
//         $options = $this->options;
//         $filter->parse($text, $this->$options);
//     }
}
