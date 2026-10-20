<?php
require_once __DIR__ . '/Db.php';
require_once __DIR__ . '/Csrf.php';

final class Auth
{
    private const MAX_FAILS = 5;
    private const LOCK_SECONDS = 300;

    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax', 'path' => '/']);
            session_start();
        }
    }

    public static function hasAdmin(): bool
    {
        return (int)Db::get()->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn() > 0;
    }

    /** Re-reads the row every request so revoking takes effect immediately. */
    public static function user(): ?array
    {
        self::start();
        if (empty($_SESSION['uid'])) {
            return null;
        }
        $st = Db::get()->prepare('SELECT * FROM users WHERE id = ? AND active = 1');
        $st->execute([$_SESSION['uid']]);
        $u = $st->fetch();
        if (!$u) {
            unset($_SESSION['uid']);
            return null;
        }
        return $u;
    }

    public static function requireLogin(): array
    {
        self::start();
        if (!self::hasAdmin()) {
            header('Location: ' . self::base() . 'setup.php');
            exit;
        }
        $u = self::user();
        if (!$u) {
            header('Location: ' . self::base() . 'login.php');
            exit;
        }
        return $u;
    }

    public static function requireAdmin(): array
    {
        $u = self::requireLogin();
        if ($u['role'] !== 'admin') {
            http_response_code(403);
            exit('Forbidden: admin only.');
        }
        return $u;
    }

    /** Returns null on success, or an error message. */
    public static function login(string $username, string $password): ?string
    {
        self::start();
        $now = time();
        if (($_SESSION['lock_until'] ?? 0) > $now) {
            return 'Too many attempts. Try again in a few minutes.';
        }
        $st = Db::get()->prepare('SELECT * FROM users WHERE username = ?');
        $st->execute([trim($username)]);
        $u = $st->fetch();
        // verify against a dummy hash when the user is missing to keep timing similar
        $hash = $u ? $u['password_hash'] : '$2y$10$usesomesillystringfore7hnbRJHxXVLeakoG8K30oukPsA.ztMG';
        $ok = password_verify($password, $hash);
        if (!$u || !$ok) {
            $_SESSION['fails'] = ($_SESSION['fails'] ?? 0) + 1;
            if ($_SESSION['fails'] >= self::MAX_FAILS) {
                $_SESSION['lock_until'] = $now + self::LOCK_SECONDS;
                $_SESSION['fails'] = 0;
            }
            return 'Invalid username or password.';
        }
        if (!(int)$u['active']) {
            return 'This account has been disabled. Contact the admin.';
        }
        session_regenerate_id(true);
        $_SESSION['uid'] = (int)$u['id'];
        $_SESSION['fails'] = 0;
        Db::get()->prepare('UPDATE users SET last_login_at = ? WHERE id = ?')->execute([date('Y-m-d H:i:s'), $u['id']]);
        return null;
    }

    public static function logout(): void
    {
        self::start();
        $_SESSION = [];
        session_destroy();
    }

    /** Path prefix from the current script to /public/. */
    public static function base(): string
    {
        return strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false ? '../' : '';
    }
}
