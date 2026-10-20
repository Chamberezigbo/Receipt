<?php
final class Csrf
{
    public static function token(): string
    {
        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf'];
    }

    public static function field(): string
    {
        return '<input type="hidden" name="csrf" value="' . self::token() . '">';
    }

    public static function check(): void
    {
        $sent = (string)($_POST['csrf'] ?? '');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !hash_equals(self::token(), $sent)) {
            http_response_code(400);
            exit('Invalid or missing security token. Go back, refresh and try again.');
        }
    }
}
