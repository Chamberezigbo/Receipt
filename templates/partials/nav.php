<?php /** @var array $me  @var string $base */ ?>
<nav class="nav no-print">
    <a href="<?= $base ?>index.php" class="home">← Home</a>
    <?php if ($me['role'] === 'admin'): ?><a href="<?= $base ?>admin/users.php">Users</a><?php endif; ?>
    <span class="spacer"></span>
    <a href="<?= $base ?>account.php" class="who"><?= htmlspecialchars($me['username']) ?> (<?= $me['role'] === 'admin' ? 'super admin' : 'sub-admin' ?>)</a>
    <form method="post" action="<?= $base ?>logout.php"><?= Csrf::field() ?><button class="ghost small">Log out</button></form>
</nav>
