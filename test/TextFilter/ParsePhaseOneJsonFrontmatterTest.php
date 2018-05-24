<?php

namespace Anax\TextFilter;

/**
 * Test
 */
class ParsePhaseOneJsonFrontmatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Use only frontammter yaml to start with.
     */
    private $options = [
        "include"               => false,
        "frontmatter_json"      => true,
        "frontmatter_yaml"      => false,
        "variables"             => false,
    ];



    /**
     * Document contains only frontmatter
     */
    public function testOnlyJson()
    {
        $filter = new ParsePhaseOne();

        $text = <<<EOD
{{{
{
    "title": "hi",
    "author": "mos"
}
}}}
EOD;

        $res = $filter->parse($text, $this->options);

        $this->assertEquals([
            "title" => "hi",
            "author" => "mos"
        ], $res["frontmatter"]);

        $this->assertContains(
            "",
            $res["lines"]
        );
    }



    /**
     * Ignore when not frontmatter
     */
    public function testWithoutJson()
    {
        $filter = new ParsePhaseOne();

        $text = <<<EOD
Header 2
{
    text
}
EOD;

        $res = $filter->parse($text, $this->options);

        $this->assertEquals([], $res["frontmatter"]);

        $this->assertContains(
            "Header 2",
            $res["lines"]
        );
    }



    /**
     * Frontmatter with simple value.
     */
    public function testOnlyJsonValue()
    {
        $filter = new ParsePhaseOne();

        $text = <<<EOD
{{{
"hi"    
}}}
EOD;

        $res = $filter->parse($text, $this->options);

        $this->assertEquals([
            "0" => "hi",
        ], $res["frontmatter"]);

        $this->assertContains(
            "",
            $res["lines"]
        );
    }



    /**
     * Document with frontmatter followed by text
     */
    public function testOnlyJsonWithText()
    {
        $filter = new ParsePhaseOne();

        $text = <<<EOD
{{{
{
    "title": "hi",
    "author": "mos"
}
}}}
Some markdown text.
EOD;

        $res = $filter->parse($text, $this->options);
        $this->assertEquals([
            "title" => "hi",
            "author" => "mos"
        ], $res["frontmatter"]);

        $this->assertContains(
            "Some markdown text.",
            $res["lines"]
        );
    }



    /**
     * Frontmatter surrounded by text
     */
    public function testOnlyJsonSurroundedByText()
    {
        $filter = new ParsePhaseOne();

        $text = <<<EOD
Inital markdown text.
{{{
{
    "title": "hi",
    "author": "mos"
}
}}}
Some markdown text.
EOD;

        $res = $filter->parse($text, $this->options);

        $this->assertEquals([
            "title" => "hi",
            "author" => "mos"
        ], $res["frontmatter"]);

        $this->assertContains(
            "Inital markdown text.",
            $res["lines"]
        );

        $this->assertContains(
            "Some markdown text.",
            $res["lines"]
        );
    }




    /**
     * Document with two frontmatter parts
     */
    public function testOnlyJsonTwoParts()
    {
        $filter = new ParsePhaseOne();

        $text = <<<EOD
{{{
{
    "title": "hi",
    "author": "mos"
}
}}}
{{{
{
    "title": "ho"
}
}}}
EOD;

        $res = $filter->parse($text, $this->options);

        $this->assertEquals([
            "title" => "ho",
            "author" => "mos"
        ], $res["frontmatter"]);

        $this->assertContains(
            "",
            $res["lines"]
        );
    }



    /**
     * Document with several frontmatter parts
     */
    public function testOnlyJsonSeveralParts()
    {
        $filter = new ParsePhaseOne();

        $text = <<<EOD
Inital markdown text.

{{{
{
    "title": "hi",
    "author": "mos"
}
}}}

More markdown text.

{{{
{
    "title": "ho"
}
}}}

Some markdown text.

{{{
{
    "title": "ha",
    "category": "cat"
}
}}}

EOD;

        $res = $filter->parse($text, $this->options);

        $this->assertEquals([
            "title" => "ha",
            "author" => "mos",
            "category" => "cat",
        ], $res["frontmatter"]);

        $this->assertContains(
            "Inital markdown text.",
            $res["lines"]
        );

        $this->assertContains(
            "More markdown text.",
            $res["lines"]
        );

        $this->assertContains(
            "Some markdown text.",
            $res["lines"]
        );
    }
}
