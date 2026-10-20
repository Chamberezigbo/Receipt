<?php
require_once __DIR__ . '/../src/Auth.php';
$me = Auth::requireLogin();
$base = '';
$db = Db::get();
$msg = $err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Csrf::check();
    $current = (string)($_POST['current'] ?? '');
    $newUser = trim((string)($_POST['username'] ?? $me['username']));
    $newPass = (string)($_POST['password'] ?? '');
    $isSuper = $me['role'] === 'admin';

    if (!password_verify($current, $me['password_hash'])) {
        $err = 'Current password is incorrect.';
    } elseif ($isSuper && !preg_match('/^[A-Za-z0-9_.-]{3,30}$/', $newUser)) {
        $err = 'Username must be 3-30 letters, numbers, dot, dash or underscore.';
    } elseif ($newPass !== '' && strlen($newPass) < 8) {
        $err = 'New password must be at least 8 characters.';
    } elseif ($newPass !== '' && $newPass !== ($_POST['confirm'] ?? '')) {
        $err = 'New passwords do not match.';
    } else {
        $username = $isSuper ? $newUser : $me['username'];
        $hash = $newPass !== '' ? password_hash($newPass, PASSWORD_DEFAULT) : $me['password_hash'];
        try {
            $db->prepare('UPDATE users SET username = ?, password_hash = ? WHERE id = ?')->execute([$username, $hash, $me['id']]);
            session_regenerate_id(true);
            $msg = 'Account updated.';
            $me = Auth::user();
        } catch (PDOException $e) {
            $err = 'That username is already taken.';
        }
    }
}
$e = function ($s) { return htmlspecialchars((string)$s); };
?>
<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>My account</title><link rel="stylesheet" href="assets/css/base.css?v=<?= filemtime(__DIR__ . '/assets/css/base.css') ?>"></head><body>
<?php include __DIR__ . '/../templates/partials/nav.php'; ?>
<main class="card">
    <h1>My account</h1>
    <p class="sub">Change your <?= $me['role'] === 'admin' ? 'username and ' : '' ?>password. Your current password is required.</p>
    <?php if ($msg): ?><p class="notice"><?= $e($msg) ?></p><?php endif; ?>
    <?php if ($err): ?><p class="alert"><?= $e($err) ?></p><?php endif; ?>
    <form method="post"><?= Csrf::field() ?>
        <?php if ($me['role'] === 'admin'): ?>
        <label>Username <input name="username" value="<?= $e($me['username']) ?>" required></label>
        <?php endif; ?>
        <label>New password (leave blank to keep) <input name="password" type="password" autocomplete="new-password"></label>
        <label>Confirm new password <input name="confirm" type="password" autocomplete="new-password"></label>
        <label>Current password <input name="current" type="password" required autocomplete="current-password"></label>
        <button class="primary">Save changes</button>
    </form>
</main></body></html>
