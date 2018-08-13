<?php
/**
 * Configuration file for DI container.
 */
return [
    "services" => [
        "textfilter" => [
            "shared" => true,
            "callback" => "\Anax\TextFilter\TextFilter",
        ],
    ],
];
