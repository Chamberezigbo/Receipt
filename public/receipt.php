<?php
require_once __DIR__ . '/../src/Auth.php';
Auth::requireLogin();
$banks = require __DIR__ . '/../src/banks.php';
require_once __DIR__ . '/../src/ReceiptData.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}
$r = ReceiptData::fromRequest($_POST, $banks);
?>
<!doctype html>
<html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Receipt <?= htmlspecialchars($r->reference) ?></title>
<link rel="stylesheet" href="assets/css/base.css">
</head><body class="receipt-page">
<div class="toolbar no-print">
    <button class="primary" onclick="window.print()">🖨 Print</button>
    <button class="ghost" id="dl">⬇ Download PNG</button>
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
</script>
</body></html>
