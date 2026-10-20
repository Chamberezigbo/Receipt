<?php
require_once __DIR__ . '/../src/Auth.php';
Auth::start();
if (!Auth::hasAdmin()) {
    header('Location: setup.php');
    exit;
}
if (Auth::user()) {
    header('Location: index.php');
    exit;
}
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Csrf::check();
    $err = Auth::login((string)($_POST['username'] ?? ''), (string)($_POST['password'] ?? ''));
    if ($err === null) {
        header('Location: index.php');
        exit;
    }
}
?>
<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Log in</title><link rel="stylesheet" href="assets/css/base.css"></head><body>
<main class="card">
    <h1>Admin login</h1>
    <p class="sub">Sign in to use the receipt designer.</p>
    <?php if ($err): ?><p class="alert"><?= htmlspecialchars($err) ?></p><?php endif; ?>
    <form method="post"><?= Csrf::field() ?>
        <label>Username <input name="username" required autofocus autocomplete="username"></label>
        <label>Password <input name="password" type="password" required autocomplete="current-password"></label>
        <button class="primary">Log in</button>
    </form>
</main></body></html>
