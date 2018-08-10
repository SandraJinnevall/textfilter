<?php
/**
 * Configuration file for DI container.
 */
return [
    "services" => [
        "response" => [
            "shared" => true,
            "callback" => "\Anax\TextFilter\TextFilter",
        ],
    ],
];
