<?php
require_once __DIR__ . '/../src/Auth.php';
Auth::start();
if (Auth::hasAdmin()) {
    header('Location: login.php');
    exit;
}
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Csrf::check();
    $u = trim((string)($_POST['username'] ?? ''));
    $p = (string)($_POST['password'] ?? '');
    if (!preg_match('/^[A-Za-z0-9_.-]{3,30}$/', $u)) {
        $err = 'Username must be 3-30 letters, numbers, dot, dash or underscore.';
    } elseif (strlen($p) < 8) {
        $err = 'Password must be at least 8 characters.';
    } elseif ($p !== ($_POST['confirm'] ?? '')) {
        $err = 'Passwords do not match.';
    } else {
        Db::get()->prepare("INSERT INTO users (username, password_hash, role, active, created_at) VALUES (?, ?, 'admin', 1, ?)")
            ->execute([$u, password_hash($p, PASSWORD_DEFAULT), date('Y-m-d H:i:s')]);
        header('Location: login.php');
        exit;
    }
}
$title = 'First-time setup';
?>
<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Setup</title><link rel="stylesheet" href="assets/css/base.css?v=<?= filemtime(__DIR__ . '/assets/css/base.css') ?>"></head><body>
<main class="card">
    <h1>Create super admin</h1>
    <p class="sub">One-time setup. This account can create, revoke and delete sub-admins. You can change its username and password later under My account.</p>
    <?php if ($err): ?><p class="alert"><?= htmlspecialchars($err) ?></p><?php endif; ?>
    <form method="post"><?= Csrf::field() ?>
        <label>Username <input name="username" required autofocus></label>
        <label>Password (min 8) <input name="password" type="password" required></label>
        <label>Confirm password <input name="confirm" type="password" required></label>
        <button class="primary">Create super admin</button>
    </form>
</main></body></html>
