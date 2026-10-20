<?php
/** @var ReceiptData $r  @var string $logoClass */
$logoFile = 'assets/logos/' . $r->bankId . '.png';
$hasLogo  = is_file(__DIR__ . '/../../public/' . $logoFile);
if ($hasLogo): ?>
    <img class="logo-img" src="<?= htmlspecialchars($logoFile) ?>" alt="<?= htmlspecialchars($r->bankName) ?>">
<?php else: ?>
    <span class="logo"><?= htmlspecialchars(mb_substr($r->bankName, 0, 1)) ?></span>
<?php endif; ?>
