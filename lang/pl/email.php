<?php

declare(strict_types=1);

use Getthebox\Core\Enums\Invoice\Type;

return [
    "reset" => [
        "subject" => "Powiadomienie o zresetowaniu hasła",
        "greeting" => "Witaj,",
        "reason" => "Otrzymujesz tę wiadomość, ponieważ dostaliśmy żądanie zresetowania hasła dla twojego konta.",
        "action" => "Zresetuj hasło",
        "expire" => "Odnośnik do resetu hasła straci ważność za :count minut.",
        "ignore" => "Jeśli nie Ty żądałeś zmiany hasła, nie klikaj w odnośnik.",
    ],
    "regards" => "Pozdrowienia",
    "footer" => "Jeśli masz problemy z kliknięciem w przycisk \":actionText\", skopiuj i wklej następujący adres do swojej przeglądarki:",
    "rights_reserved" => "Wszystkie prawa zastrzeżone.",
];
