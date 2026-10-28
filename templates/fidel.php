<?php /** @var ReceiptData $r */
require_once __DIR__ . '/../src/helpers.php';
$ts = strtotime($r->date) ?: time();
$e = function ($s) { return htmlspecialchars((string)$s); };
$isDeposit = $r->type === 'Deposit';
$initials = function ($n) {
    $p = preg_split('/\s+/', trim($n));
    return mb_strtoupper(mb_substr($p[0] ?? '', 0, 1) . mb_substr($p[1] ?? '', 0, 1));
};
?>
<div class="receipt fidel" style="--brand: <?= $e($r->color) ?>">
    <header class="f-head f-head-logo">
        <div class="f-top">
            <span></span>
            <img src="<?= asset('assets/img/fidelity-logo.png') ?>" alt="Fidelity Bank" height="34">
        </div>
    </header>

    <?php
    // Decorative QR-style code, seeded by the reference so it is stable per receipt.
    $seed = crc32($r->reference);
    $bit = function () use (&$seed) {
        $seed = ($seed * 1103515245 + 12345) & 0x7fffffff;
        return ($seed >> 16) & 1;
    };
    $n = 25; $cells = '';
    $finder = function ($x, $y) use ($n) {
        foreach ([[0, 0], [$n - 7, 0], [0, $n - 7]] as [$fx, $fy]) {
            if ($x >= $fx && $x < $fx + 7 && $y >= $fy && $y < $fy + 7) {
                $dx = $x - $fx; $dy = $y - $fy;
                return [true, $dx == 0 || $dx == 6 || $dy == 0 || $dy == 6 || ($dx >= 2 && $dx <= 4 && $dy >= 2 && $dy <= 4)];
            }
            if ($x >= $fx - 1 && $x <= $fx + 7 && $y >= $fy - 1 && $y <= $fy + 7) return [true, false];
        }
        return [false, false];
    };
    for ($y = 0; $y < $n; $y++) {
        for ($x = 0; $x < $n; $x++) {
            [$isF, $on] = $finder($x, $y);
            if (!$isF) $on = (bool)$bit();
            if ($on) $cells .= '<rect x="' . $x . '" y="' . $y . '" width="1" height="1"/>';
        }
    }
    $sameBank = $r->recipientBank === $r->bankName;
    ?>
    <div class="fb">
        <h3>Online Banking</h3>
        <p class="fb-sub">Transaction Receipt</p>
        <div class="fb-row"><span class="k">Payer</span><span class="v"><?= $e(strtoupper($r->senderName)) ?><br><?= $e($r->senderAccount) ?><br><?= $e($r->bankName) ?> Plc.</span></div>
        <div class="fb-row"><span class="k"><?= $isDeposit ? 'Recipient' : 'Receiver' ?></span><span class="v"><?= $e(strtoupper($r->name)) ?><br><?= $e($r->recipientAccount) ?><br><?= $e($r->recipientBank) ?> Plc.</span></div>
        <div class="fb-row"><span class="k">Transaction</span><span class="v">NGN <?= number_format($r->amount, $r->amount == floor($r->amount) ? 0 : 2) ?><br><?= $isDeposit ? 'DEPOSIT' : ($sameBank ? 'INTRATRANSFER' : 'INTERBANK TRANSFER') ?><br><?= date('j M Y, g:i a \G\M\T+1', $ts) ?></span></div>
        <div class="fb-row"><span class="k">Fee</span><span class="v">NGN <?= number_format($r->fee, 2) ?></span></div>
        <div class="fb-row"><span class="k">Narration</span><span class="v">ONB TRF TO <?= $e(strtoupper($r->name)) ?><?= $r->description !== '' ? ' ' . $e($r->description) : '' ?></span></div>
        <div class="fb-row"><span class="k">Reference</span><span class="v"><?= $e($r->reference) ?><br>SUCCESSFUL</span></div>
        <div class="fb-note">
            <svg viewBox="0 0 <?= $n ?> <?= $n ?>" width="78" height="78" shape-rendering="crispEdges" aria-hidden="true"><?= $cells ?></svg>
            <p>This is an electronic receipt of a transaction and does not require any signature. The authenticity of transaction can be confirmed with the Bank.<br>For any other assistance, kindly call True Serve on 0700 3233 5489 or email true.serve@fidelitybank.ng</p>
        </div>
    </div>
    <img class="f-ivy" src="<?= asset('assets/img/fidelity-ivy.png') ?>" alt="Bank with IVY on WhatsApp">

    <?php include __DIR__ . '/partials/watermark.php'; ?>
</div>
