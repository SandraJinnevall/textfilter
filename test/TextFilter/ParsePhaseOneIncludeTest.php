<?php

namespace Anax\TextFilter;

/**
 * Test
 */
class ParsePhaseOneIncludeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Use only frontammter yaml to start with.
     */
    private $options = [
        "include"               => true,
        "include_base"          => __DIR__ . "/../content",
        "frontmatter_json"      => false,
        "frontmatter_yaml"      => true,
        "variables"             => false,
    ];



    /**
     * No include directive..
     */
    public function testNoInclude()
    {
        $filter = new ParsePhaseOne();

        $text = <<<EOD
#
# include file1.md
#includefile1.md
# A H1 header in Markdown

EOD;

        $res = $filter->parse($text, $this->options);

        $this->assertContains(
            "# include file1.md",
            $res["lines"]
        );

        $this->assertContains(
            "#includefile1.md",
            $res["lines"]
        );

        $this->assertContains(
            "# A H1 header in Markdown",
            $res["lines"]
        );
    }



    /**
     * Include single file with text and frontmatter.
     */
    public function testIncludeOneFile()
    {
        $filter = new ParsePhaseOne();

        $text = <<<EOD
#include file1.md
EOD;

        $res = $filter->parse($text, $this->options);

        $this->assertEquals([
            "title" => "file1"
        ], $res["frontmatter"]);
        
        $this->assertContains(
            "file1",
            $res["lines"]
        );
    }



    /**
     * Include two files with text and frontmatter.
     */
    public function testIncludeTwoFiles()
    {
        $filter = new ParsePhaseOne();

        $text = <<<EOD
#include file1.md
#include file2.md
Some markdown text.
EOD;

        $res = $filter->parse($text, $this->options);

        $this->assertEquals([
            "title" => "file2"
        ], $res["frontmatter"]);
        
        $this->assertContains(
            "file1",
            $res["lines"]
        );

        $this->assertContains(
            "file2",
            $res["lines"]
        );

        $this->assertContains(
            "Some markdown text.",
            $res["lines"]
        );
    }


    /**
     * Include a file which includes two other files.
     */
    public function testIncludeFileWhichIncludes()
    {
        $filter = new ParsePhaseOne();

        $text = <<<EOD
#include file3.md
Some markdown text.
EOD;

        $res = $filter->parse($text, $this->options);

        $this->assertEquals([
            "title" => "file2"
        ], $res["frontmatter"]);
        
        $this->assertContains(
            "file1",
            $res["lines"]
        );

        $this->assertContains(
            "file2",
            $res["lines"]
        );

        $this->assertContains(
            "file3",
            $res["lines"]
        );

        $this->assertContains(
            "Some markdown text.",
            $res["lines"]
        );
    }
}
