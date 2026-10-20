<?php
require_once __DIR__ . '/../../src/Auth.php';
$me = Auth::requireAdmin();
$db = Db::get();
$msg = $err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Csrf::check();
    $action = (string)($_POST['action'] ?? '');
    $id = (int)($_POST['id'] ?? 0);
    $target = null;
    if ($id) {
        $st = $db->prepare('SELECT * FROM users WHERE id = ?');
        $st->execute([$id]);
        $target = $st->fetch() ?: null;
    }
    $activeAdmins = (int)$db->query("SELECT COUNT(*) FROM users WHERE role='admin' AND active=1")->fetchColumn();

    if ($action === 'create') {
        $u = trim((string)($_POST['username'] ?? ''));
        $p = (string)($_POST['password'] ?? '');
        if (!preg_match('/^[A-Za-z0-9_.-]{3,30}$/', $u)) {
            $err = 'Username must be 3-30 letters, numbers, dot, dash or underscore.';
        } elseif (strlen($p) < 8) {
            $err = 'Password must be at least 8 characters.';
        } else {
            try {
                $db->prepare("INSERT INTO users (username, password_hash, role, active, created_by, created_at) VALUES (?, ?, 'sub', 1, ?, ?)")
                    ->execute([$u, password_hash($p, PASSWORD_DEFAULT), $me['id'], date('Y-m-d H:i:s')]);
                $msg = "Sub-admin '$u' created.";
            } catch (PDOException $e) {
                $err = 'That username is already taken.';
            }
        }
    } elseif (!$target) {
        $err = 'User not found.';
    } elseif ($action === 'revoke' || $action === 'delete') {
        if ((int)$target['id'] === (int)$me['id']) {
            $err = 'You cannot revoke or delete your own account.';
        } elseif ($target['role'] === 'admin' && $activeAdmins <= 1) {
            $err = 'You cannot remove the last active admin.';
        } elseif ($action === 'revoke') {
            $db->prepare('UPDATE users SET active = 0 WHERE id = ?')->execute([$id]);
            $msg = "Access revoked for '{$target['username']}'.";
        } else {
            $db->prepare('DELETE FROM users WHERE id = ?')->execute([$id]);
            $msg = "'{$target['username']}' deleted.";
        }
    } elseif ($action === 'restore') {
        $db->prepare('UPDATE users SET active = 1 WHERE id = ?')->execute([$id]);
        $msg = "Access restored for '{$target['username']}'.";
    } elseif ($action === 'reset') {
        $p = (string)($_POST['password'] ?? '');
        if (strlen($p) < 8) {
            $err = 'New password must be at least 8 characters.';
        } else {
            $db->prepare('UPDATE users SET password_hash = ? WHERE id = ?')->execute([password_hash($p, PASSWORD_DEFAULT), $id]);
            $msg = "Password reset for '{$target['username']}'.";
        }
    }
}

$users = $db->query('SELECT * FROM users ORDER BY role, username')->fetchAll();
$base = '../';
$e = function ($s) { return htmlspecialchars((string)$s); };
?>
<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Users</title><link rel="stylesheet" href="../assets/css/base.css?v=<?= filemtime(__DIR__ . '/../assets/css/base.css') ?>"></head><body>
<?php include __DIR__ . '/../../templates/partials/nav.php'; ?>
<?php $activeCount = count(array_filter($users, function ($u) { return (int)$u['active']; })); ?>
<main class="page">
    <header class="page-head">
        <h1>Users</h1>
        <p class="sub"><?= count($users) ?> account<?= count($users) === 1 ? '' : 's' ?> · <?= $activeCount ?> active</p>
    </header>

    <?php if ($msg): ?><p class="notice"><?= $e($msg) ?></p><?php endif; ?>
    <?php if ($err): ?><p class="alert"><?= $e($err) ?></p><?php endif; ?>

    <section class="panel">
        <h2>Add a sub-admin</h2>
        <form method="post" class="add-form"><?= Csrf::field() ?><input type="hidden" name="action" value="create">
            <input name="username" placeholder="Username" required autocomplete="off">
            <input name="password" type="password" placeholder="Password (min 8 characters)" required autocomplete="new-password">
            <button class="primary">Add user</button>
        </form>
    </section>

    <section class="panel">
        <h2>Accounts</h2>
        <ul class="user-list">
        <?php foreach ($users as $u): $self = (int)$u['id'] === (int)$me['id']; $super = $u['role'] === 'admin'; ?>
            <li class="user<?= $u['active'] ? '' : ' is-off' ?>">
                <div class="avatar<?= $super ? ' super' : '' ?>"><?= $e(strtoupper(mb_substr($u['username'], 0, 1))) ?></div>
                <div class="info">
                    <div class="name"><?= $e($u['username']) ?><?= $self ? ' <span class="you">you</span>' : '' ?></div>
                    <div class="meta">
                        <span class="badge <?= $super ? 'super' : '' ?>"><?= $super ? 'Super admin' : 'Sub-admin' ?></span>
                        <span class="pill <?= $u['active'] ? 'on' : 'off' ?>"><?= $u['active'] ? 'Active' : 'Revoked' ?></span>
                        <span class="last">Last login: <?= $e($u['last_login_at'] ?: 'never') ?></span>
                    </div>
                </div>
                <div class="user-actions">
                    <?php if (!$self): ?>
                    <form method="post"><?= Csrf::field() ?><input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
                        <?php if ($u['active']): ?>
                            <button name="action" value="revoke" class="ghost small" onclick="return confirm('Revoke access for this user?')">Revoke</button>
                        <?php else: ?>
                            <button name="action" value="restore" class="primary small">Restore</button>
                        <?php endif; ?>
                        <button name="action" value="delete" class="ghost small danger" onclick="return confirm('Permanently delete this user?')">Delete</button>
                    </form>
                    <?php endif; ?>
                    <details class="reset-box">
                        <summary class="ghost small">Reset password</summary>
                        <form method="post"><?= Csrf::field() ?><input type="hidden" name="id" value="<?= (int)$u['id'] ?>"><input type="hidden" name="action" value="reset">
                            <input name="password" type="password" placeholder="New password" minlength="8" required autocomplete="new-password">
                            <button class="primary small">Save</button>
                        </form>
                    </details>
                </div>
            </li>
        <?php endforeach; ?>
        </ul>
    </section>
</main></body></html>
