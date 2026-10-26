<?php
/** @var ReceiptData $r  @var string $logoClass */
require_once __DIR__ . '/../../src/helpers.php';
$logoFile = 'assets/logos/' . $r->bankId . '.png';
$hasLogo  = is_file(__DIR__ . '/../../public/' . $logoFile);
if ($hasLogo): ?>
    <img class="logo-img" src="<?= asset($logoFile) ?>" alt="<?= htmlspecialchars($r->bankName) ?>">
<?php else: ?>
    <span class="logo"><?= htmlspecialchars(mb_substr($r->bankName, 0, 1)) ?></span>
<?php endif; ?>
