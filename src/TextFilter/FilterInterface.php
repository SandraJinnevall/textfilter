<?php

namespace Anax\TextFilter;

/**
 * Interface which a filter must implement.
 */
interface FilterInterface
{
    /**
     * Parse the text through the filter and do what the filter does,
     * return the resulting text and with some optional additional details,
     * all wrapped in an key-value array.
     *
     * @param string $text     to parse.
     * @param array  $options  set options the filter can use when parsing.
     *
     * @return array with the resulting text and optional additional items.
     */
    public function parse($text, array $options = []);
}
