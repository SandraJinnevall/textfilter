<?php

namespace Anax\TextFilter;

/**
 * Test
 */
class ParsePhaseOneYamlFrontmatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Use only frontammter yaml to start with.
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
     * Document contains only frontmatter
     */
    public function testOnlyYaml()
    {
        $filter = new ParsePhaseOne();

        $text = <<<EOD
---
title: hi
author: mos
---
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
    public function testWithoutYaml()
    {
        $filter = new ParsePhaseOne();

        $text = <<<EOD
Header 2
--------
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
    public function testOnlyYamlValue()
    {
        $filter = new ParsePhaseOne();

        $text = <<<EOD
---
hi
---
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
    public function testOnlyYamlWithText()
    {
        $filter = new ParsePhaseOne();

        $text = <<<EOD
---
title: hi
author: mos
---
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
    public function testOnlyYamlSurroundedByText()
    {
        $filter = new ParsePhaseOne();

        $text = <<<EOD
Inital markdown text.
---
title: hi
author: mos
---
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
    public function testOnlyYamlTwoParts()
    {
        $filter = new ParsePhaseOne();

        $text = <<<EOD
---
title: hi
author: mos
---
---
title: ho
---
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
    public function testOnlyYamlSeveralParts()
    {
        $filter = new ParsePhaseOne();

        $text = <<<EOD
Inital markdown text.

---
title: hi
author: mos
---

More markdown text.

---
title: ho
---

Some markdown text.

---
title: ha
category: cat
---

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



    /**
     * Test the symfony parser.
     */
    public function testYamlParserSymfony()
    {
        $filter = new ParsePhaseOne();

        $text = <<<EOD
---
title: hi
author: mos
---
EOD;

        $options = $this->options;
        $options["yaml_parser_pecl"] = false;
        $options["yaml_parser_symfony"] = true;
        $options["yaml_parser_spyc"] = false;
        $res = $filter->parse($text, $options);

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
     * Test the spyc parser.
     */
    public function testYamlParserSpyc()
    {
        $filter = new ParsePhaseOne();

        $text = <<<EOD
---
title: hi
author: mos
---
EOD;

        $options = $this->options;
        $options["yaml_parser_pecl"] = false;
        $options["yaml_parser_symfony"] = false;
        $options["yaml_parser_spyc"] = true;
        $res = $filter->parse($text, $options);

        $this->assertEquals([
            "title" => "hi",
            "author" => "mos"
        ], $res["frontmatter"]);

        $this->assertContains(
            "",
            $res["lines"]
        );
    }
}
