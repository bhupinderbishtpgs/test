<?php 

return [
    "general" => [
        "timezone" => "Europe/Berlin",
        "language" => "en",
        "validLanguages" => "en"
    ],
    "database" => [
        "params" => [
            "username" => "root",
            "password" => "root",
            "dbname" => "birchbox_base_dump",
            "host" => "localhost",
            "port" => "3306"
        ]
    ],
    "documents" => [
        "versions" => [
            "steps" => "10"
        ],
        "default_controller" => "default",
        "default_action" => "default",
        "error_pages" => [
            "default" => "/"
        ],
        "createredirectwhenmoved" => "",
        "allowtrailingslash" => "no",
        "generatepreview" => "1"
    ],
    "objects" => [
        "versions" => [
            "steps" => "10"
        ]
    ],
    "assets" => [
        "versions" => [
            "steps" => "10"
        ]
    ],
    "services" => [

    ],
    "cache" => [
        "excludeCookie" => ""
    ],
    "httpclient" => [
        "adapter" => "Socket"
    ],
    "email" => [
        "sender" => [
            "name" => "",
            "email" => ""
        ],
        "return" => [
            "name" => "",
            "email" => ""
        ],
        "method" => "mail",
        "smtp" => [
            "host" => "",
            "port" => "",
            "ssl" => NULL,
            "name" => "",
            "auth" => [
                "method" => NULL,
                "username" => "",
                "password" => ""
            ]
        ],
        "debug" => [
            "emailaddresses" => ""
        ]
    ],
    "newsletter" => [
        "sender" => [
            "name" => "",
            "email" => ""
        ],
        "return" => [
            "name" => "",
            "email" => ""
        ],
        "method" => "mail",
        "smtp" => [
            "host" => "",
            "port" => "",
            "ssl" => NULL,
            "name" => "",
            "auth" => [
                "method" => NULL,
                "username" => "",
                "password" => ""
            ]
        ],
        "usespecific" => ""
    ]
];
