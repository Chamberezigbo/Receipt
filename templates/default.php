<?php /** @var ReceiptData $r */ ?>
<div class="receipt" style="--brand: <?= htmlspecialchars($r->color) ?>">
    <header class="receipt-head">
        <span class="logo"><?= htmlspecialchars(mb_substr($r->bankName, 0, 1)) ?></span>
        <h2><?= htmlspecialchars($r->bankName) ?></h2>
    </header>
    <p class="amount"><?= $r->amountFormatted() ?></p>
    <p class="status">✔ <?= htmlspecialchars($r->type) ?> Successful</p>
    <dl>
        <dt><?= $r->type === 'Deposit' ? 'Account Name' : 'Recipient' ?></dt>
        <dd><?= htmlspecialchars($r->name) ?></dd>
        <dt>Transaction Type</dt><dd><?= htmlspecialchars($r->type) ?></dd>
        <dt>Date</dt><dd><?= htmlspecialchars(date('M jS, Y H:i:s', strtotime($r->date))) ?></dd>
        <dt>Reference</dt><dd><?= htmlspecialchars($r->reference) ?></dd>
    </dl>
    <?php include __DIR__ . '/partials/watermark.php'; ?>
</div>
