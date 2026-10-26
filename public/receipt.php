<?php
require_once __DIR__ . '/../src/Auth.php';
Auth::requireLogin();
$banks = require __DIR__ . '/../src/banks.php';
require_once __DIR__ . '/../src/ReceiptData.php';
require_once __DIR__ . '/../src/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}
$r = ReceiptData::fromRequest($_POST, $banks);
header('Cache-Control: no-store');
?>
<!doctype html>
<html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Receipt <?= htmlspecialchars($r->reference) ?></title>
<link rel="stylesheet" href="assets/css/base.css?v=<?= filemtime(__DIR__ . '/assets/css/base.css') ?>">
</head><body class="receipt-page">
<div class="toolbar no-print">
    <button class="primary" onclick="window.print()">🖨 Print</button>
    <button class="ghost" id="dl">⬇ Download PNG</button>
    <?php if ($r->bankId === 'opaylite'): ?>
    <button class="ghost" id="theme" type="button">Theme</button>
    <?php endif; ?>
    <button class="ghost" onclick="window.close()">Close</button>
</div>
<div id="capture">
<?php
// Per-bank override: templates/<bankId>.php wins, else the template named in banks.php
$tpl = is_file(__DIR__ . "/../templates/{$r->bankId}.php") ? $r->bankId : $banks[$r->bankId]['template'];
include __DIR__ . "/../templates/{$tpl}.php";
?>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
document.getElementById('dl').onclick = async () => {
    const canvas = await html2canvas(document.querySelector('.receipt'), {scale: 3, backgroundColor: null});
    const a = document.createElement('a');
    a.download = <?= json_encode($r->reference . '.png') ?>;
    a.href = canvas.toDataURL('image/png');
    a.click();
};

// Opay only: dark / light toggle (follows the system until the viewer picks one)
const themeBtn = document.getElementById('theme');
if (themeBtn) {
    const card = document.querySelector('.receipt');
    const mq = window.matchMedia('(prefers-color-scheme: dark)');
    let saved = null;
    try { saved = localStorage.getItem('opayTheme'); } catch (e) {}
    const apply = t => {
        card.dataset.theme = t;
        themeBtn.textContent = t === 'dark' ? '\u2600 Light' : '\u{1F319} Dark';
    };
    apply(saved === 'dark' || saved === 'light' ? saved : (mq.matches ? 'dark' : 'light'));
    themeBtn.onclick = () => {
        const next = card.dataset.theme === 'dark' ? 'light' : 'dark';
        apply(next);
        saved = next;
        try { localStorage.setItem('opayTheme', next); } catch (e) {}
    };
    mq.addEventListener('change', e => { if (!saved) apply(e.matches ? 'dark' : 'light'); });
}
</script>
</body></html>
