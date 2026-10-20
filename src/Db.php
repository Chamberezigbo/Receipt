<?php
final class Db
{
    private static $pdo;

    public static function get(): PDO
    {
        if (self::$pdo === null) {
            $pdo = new PDO('sqlite:' . __DIR__ . '/../data/app.sqlite');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $pdo->exec('CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT NOT NULL UNIQUE COLLATE NOCASE,
                password_hash TEXT NOT NULL,
                role TEXT NOT NULL DEFAULT "sub",
                active INTEGER NOT NULL DEFAULT 1,
                created_by INTEGER,
                created_at TEXT NOT NULL,
                last_login_at TEXT
            )');
            self::$pdo = $pdo;
        }
        return self::$pdo;
    }
}
