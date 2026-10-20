<?php
require_once __DIR__ . '/../src/Auth.php';
$me = Auth::requireLogin();
$base = '';
$banks = require __DIR__ . '/../src/banks.php';
$recipientBanks = require __DIR__ . '/../src/recipientBanks.php';

if (($_GET['action'] ?? '') === 'name') {
    require_once __DIR__ . '/../src/NameGenerator.php';
    header('Content-Type: application/json');
    echo json_encode(['name' => NameGenerator::generate()]);
    exit;
}
?>
<!doctype html>
<html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Receipt Design Practice</title>
<link rel="stylesheet" href="assets/css/base.css?v=<?= filemtime(__DIR__ . '/assets/css/base.css') ?>">
</head><body>
<?php include __DIR__ . '/../templates/partials/nav.php'; ?>
<main class="card">
    <h1>Receipt Designer</h1>
    <p class="sub">Practice layout for sample receipts. The receipt opens in a new tab.</p>
    <form method="post" action="receipt.php" target="_blank">
        <label>Bank
            <select name="bank">
                <?php foreach ($banks as $id => $b): ?>
                    <option value="<?= htmlspecialchars($id) ?>"><?= htmlspecialchars($b['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Type
            <select name="type"><option>Transfer</option><option>Deposit</option></select>
        </label>
        <label>Name
            <div class="row">
                <input name="name" id="name" placeholder="Leave blank to auto-generate">
                <button type="button" id="gen" class="ghost">Generate</button>
            </div>
        </label>
        <label>Sender name
            <input name="sender" placeholder="Leave blank to auto-generate">
        </label>
        <label>Account number
            <input name="account" inputmode="numeric" maxlength="10" pattern="[0-9]{10}" title="10 digits" placeholder="Leave blank to auto-generate">
        </label>
        <label>Recipient bank
            <select name="recipient_bank">
                <option value="">Auto (random)</option>
                <?php foreach ($recipientBanks as $rb): ?>
                    <option><?= htmlspecialchars($rb) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Description
            <input name="description" placeholder="e.g. Rent, Food (optional)">
        </label>
        <label>Fee (₦)
            <input name="fee" type="number" step="0.01" min="0" value="10">
        </label>
        <label>Amount (₦)
            <input name="amount" type="number" step="0.01" min="0" value="5000" required>
        </label>
        <label>Date &amp; time <small>(leave blank to use now)</small>
            <input name="date" type="datetime-local">
        </label>
        <button class="primary">Generate receipt ↗</button>
    </form>
</main>
<script>
document.getElementById('gen').onclick = async () => {
    const res = await fetch('?action=name');
    document.getElementById('name').value = (await res.json()).name;
};
</script>
</body></html>
