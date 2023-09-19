<?php

declare(strict_types=1);

return [
    "supported_locales" => ["en", "pl"],
    "frontends" => [
        "vue" => [
            "password_reset" => env("VUE_PASSWORD_RESET_URL", ""),
        ],
    ],
];
