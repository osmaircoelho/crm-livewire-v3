<?php
function obfuscate_email(?string $email = null): string
{

    if (!$email) {
        return '';
    }

    $email = strtolower($email);
    $parts = explode('@', $email);

    if(sizeof($parts) != 2) {
        return '';
    }

    $name = substr($parts[0], 0, 2) .
                       str_repeat('*', strlen($parts[0]) - 2);

    $second_part      = $parts[1];
    $qty              = (int) floor(strlen($second_part) * 0.75);
    $remaining        = strlen($second_part) - $qty;
    $maskedSecondPart = str_repeat('*', $qty) . substr($second_part, $remaining * -1, $remaining);

    return $name . '@' . $maskedSecondPart;
}
