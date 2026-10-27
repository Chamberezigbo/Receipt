<?php /** @var ReceiptData $r */
require_once __DIR__ . '/../src/helpers.php';
$ts = strtotime($r->date) ?: time();
$e = function ($s) { return htmlspecialchars((string)$s); };
$isDeposit = $r->type === 'Deposit';
$words = function ($n) use (&$words) {
    $n = (int)$n;
    $ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
    $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
    if ($n === 0) return 'Zero';
    if ($n < 20) return $ones[$n];
    if ($n < 100) return $tens[intdiv($n, 10)] . ($n % 10 ? ' ' . $ones[$n % 10] : '');
    if ($n < 1000) return $ones[intdiv($n, 100)] . ' Hundred' . ($n % 100 ? ' ' . $words($n % 100) : '');
    foreach ([1000000000 => 'Billion', 1000000 => 'Million', 1000 => 'Thousand'] as $v => $label) {
        if ($n >= $v) return $words(intdiv($n, $v)) . ' ' . $label . ($n % $v ? ' ' . $words($n % $v) : '');
    }
    return '';
};
?>
<div class="receipt detailed <?= $r->bankId === 'kudos' ? 'kuda2 ' : '' ?><?= $r->bankId === 'carbonite' ? 'cbn ' : '' ?><?= $r->bankId === 'sparkly' ? 'spk ' : '' ?><?= $r->bankId === 'opaylite' ? 'opay2 ' : '' ?><?= $r->bankId === 'palmwave' ? 'palm ' : '' ?><?= $r->bankId === 'unity' ? 'uba ' : '' ?><?= $r->bankId === 'zenor' ? 'zen ' : '' ?><?= $r->bankId === 'moniepoint' ? 'monie ' : '' ?><?= $e($variant ?? '') ?>" style="--brand: <?= $e($r->color) ?>">
    <?php if ($r->bankId === 'kudos'): ?>
    <div class="kd-head">
        <img src="<?= asset('assets/img/kuda-wordmark.png') ?>" alt="Kuda">
        <p>TRANSACTION AMOUNT</p>
        <strong>&#8358;<?= number_format(floor($r->amount)) ?><i>.<?= str_pad((string)(int)round(($r->amount - floor($r->amount)) * 100), 2, '0', STR_PAD_LEFT) ?></i></strong>
    </div>
    <?php elseif ($r->bankId === 'opaylite'): ?>
    <div class="op-l">
    <img class="d-head-img" src="<?= asset('assets/img/opay-header.png') ?>" alt="OPay - Transaction Receipt">
    </div>
    <div class="op-d">
    <div class="oz-top">
        <span class="oz-logo"><img src="<?= asset('assets/img/opay-logo-white.png') ?>" alt="OPay" height="20"></span>
        <span>Transaction Receipt</span>
    </div>
    <div class="oz-hero">
        <p><?= $isDeposit ? 'DEPOSIT' : ($r->recipientBank === 'Opay' ? 'TRANSFER TO OPAY ACCOUNT' : 'TRANSFER TO BANK') ?></p>
        <strong>&#8358;<?= number_format($r->amount, 2) ?></strong>
        <span>Successful</span>
    </div>
    </div>
    <?php elseif ($r->bankId === 'ecobank'): ?>
    <?php elseif ($r->bankId === 'firstline'): ?>
    <div class="fbk-head">
        <img class="fbk-shapes" src="<?= asset('assets/img/firstbank-shapes.png') ?>" alt="" aria-hidden="true">
        <img class="fbk-logo" src="<?= asset('assets/img/firstbank-logo.png') ?>" alt="FirstBank" height="34">
        <h3>Transaction Receipt</h3>
        <p class="fbk-ok">Successful</p>
        <strong><?= $r->amountFormatted() ?></strong>
        <small><?= $e($words(floor($r->amount))) ?> Naira Only</small>
        <time><?= date('F d, Y H:i:s', $ts) ?></time>
    </div>
    <?php elseif ($r->bankId === 'accent'): ?>
    <img class="a-head" src="<?= asset('assets/img/access-header.png') ?>" alt="Access - more than banking">
    <?php elseif ($r->bankId === 'zenor'): ?>
    <div class="zr-head">
        <img class="zr-logo" src="<?= asset('assets/img/zenith-logo-metal.png') ?>" alt="Zenith Bank">
        <small><?= $isDeposit ? 'DEPOSIT' : 'TRANSACTION' ?> RECEIPT</small>
        <strong><span class="zr-n">&#8358;</span><?= number_format($r->amount, 2) ?></strong>
    </div>
    <?php elseif ($r->bankId === 'unity'): ?>
    <div class="u-banner">
        <svg class="u-art" viewBox="0 0 605 251" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
            <defs>
                <radialGradient id="uo" cx="60%" cy="50%" r="70%"><stop offset="0" stop-color="#c45a1c"/><stop offset="1" stop-color="#3a1408"/></radialGradient>
                <linearGradient id="ur" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#3a0909"/><stop offset="1" stop-color="#8a1812"/></linearGradient>
                <linearGradient id="uw" x1="0" y1="0" x2="1" y2="0"><stop offset="0" stop-color="#8f2216"/><stop offset="1" stop-color="#d4501f"/></linearGradient>
            </defs>
            <rect width="605" height="251" fill="#0a0506"/>
            <circle cx="-12" cy="150" r="112" fill="url(#uo)"/>
            <circle cx="-12" cy="150" r="124" fill="none" stroke="#b4501c" stroke-opacity=".35" stroke-width="3"/>
            <path d="M200 0H605V251H300C345 205 300 120 250 80C225 56 205 30 200 0Z" fill="url(#ur)"/>
            <path d="M330 251C385 190 470 178 605 128V251Z" fill="url(#uw)"/>
        </svg>
        <div class="u-brand"><img src="<?= asset('assets/img/uba-logo.png') ?>" alt="UBA"><small>United Bank for Africa</small></div>
        <svg class="u-app" viewBox="90 55 630 680" aria-label="UBA app icon">
            <defs>
                <linearGradient id="ug" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#ff2b2b"/><stop offset="1" stop-color="#c8000f"/></linearGradient>
                <linearGradient id="uh" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#fff" stop-opacity=".55"/><stop offset="1" stop-color="#fff" stop-opacity="0"/></linearGradient>
            </defs>
            <rect x="100" y="65" width="610" height="655" rx="115" fill="url(#ug)"/>
            <rect x="100" y="65" width="610" height="230" rx="115" fill="url(#uh)"/>
            <path fill="#fff" d="M305 285L232 578H388L412 500Z"/>
            <path fill="#fff" d="M388 235L558 212L498 515L420 425Z"/>
        </svg>
        <span class="u-word">Receipt</span>
    </div>
    <?php elseif ($r->bankId === 'gtrust'): ?>
    <div class="gz-head">
        <div><h3>Receipt</h3><small><?= date('d M Y, H:i', $ts) ?>, WAT</small></div>
        <span class="gz-logo"><svg viewBox="0 0 10 10" aria-hidden="true"><rect x="5.6" y="1.3" width="2.6" height="2.6" fill="#fff" opacity=".9"/></svg><b>GTCO</b></span>
    </div>
    <?php elseif ($r->bankId === 'nombra'): ?>
    <div class="n-head">
        <img src="<?= asset('assets/img/nomba-logo.png') ?>" alt="nomba" height="18">
        <p>Nomba Financial Services Limited<br>E-receipt</p>
        <strong><?= $isDeposit ? '+' : '-' ?><?= $r->amountFormatted() ?></strong>
    </div>
    <?php elseif ($r->bankId === 'carbonite'): ?>
    <?php
    $cnStamp = (!$isDeposit && $r->amount >= 10000) ? 50 : 0;
    $cnFee = $isDeposit ? 0 : $r->fee;
    $cnTotal = $r->amount + $cnFee + $cnStamp;
    $cnDigits = substr($r->reference, -10);
    $cnH = crc32($r->reference);
    $cnRef = 'TRF-' . chr(97 + $cnH % 26) . chr(97 + intdiv($cnH, 26) % 26) . chr(65 + intdiv($cnH, 676) % 26) . $cnDigits;
    $cnMoney = function ($n) { return '&#8358;' . number_format($n, 2); };
    ?>
    <div class="cn-top">
        <strong class="cn-amt <?= $isDeposit ? 'in' : 'out' ?>"><?= $cnMoney($r->amount) ?></strong>
        <span class="cn-ok">Successful</span>
    </div>
    <?php if ($isDeposit): ?>
    <div class="cn-who"><i class="cn-av"></i><div><b><?= $e(strtoupper($r->name)) ?></b><span><?= $e($r->recipientAccount) ?></span><em><?= date('d M Y h:i A', $ts) ?></em></div></div>
    <?php else: ?>
    <div class="cn-flow">
        <div class="cn-from"><i class="cn-dot"></i><div><b><?= $e(strtoupper($r->senderName)) ?></b><span><?= $e($r->senderAccount) ?></span></div></div>
        <div class="cn-to"><i class="cn-av"></i><div><b><?= $e($r->name) ?></b><span><?= $e($r->recipientAccount) ?></span><span><?= $e(strtoupper($r->recipientBank)) ?></span><em><?= date('d M Y h:i A', $ts) ?> / others</em></div></div>
    </div>
    <?php endif; ?>
    <?php elseif ($r->bankId === 'sparkly'): ?>
    <?php
    $spW = preg_split('/\s+/', trim($r->name));
    $spInit = strtoupper(mb_substr($spW[0] ?? '', 0, 1) . mb_substr($spW[count($spW) - 1] ?? '', 0, 1));
    ?>
    <div class="sp-logo"><img src="<?= asset('assets/img/sparkle-logo.png') ?>" alt="Sparkle" height="26"></div>
    <div class="sp-hero">
        <?php if ($isDeposit): ?>
        <p><?= $e($r->senderName) ?> sent you</p>
        <?php else: ?>
        <span class="sp-av"><?= $e($spInit) ?></span>
        <?php endif; ?>
        <strong class="sp-acct"><?= $e($isDeposit ? $r->senderAccount : $r->recipientAccount) ?></strong>
        <strong class="sp-amt"><?= $isDeposit ? '+' : '-' ?>&#8358;<?= number_format($r->amount, 2) ?></strong>
    </div>
    <?php elseif ($r->bankId === 'moniepoint'): ?>
    <img class="m-banner" src="<?= asset('assets/img/moniepoint-header.png') ?>" alt="Moniepoint">
    <div class="m-sheet">
    <div class="m-card">
        <div>
            <span class="m-chip"><?= $isDeposit ? 'CREDIT' : 'DEBIT' ?></span>
            <strong><?= $r->amountFormatted() ?></strong>
        </div>
        <img src="<?= asset('assets/img/moniepoint-icon.png') ?>" alt="" width="36" height="36">
    </div>
    <?php elseif ($r->bankId === 'palmwave'): ?>
    <img class="p-logo" src="<?= asset('assets/img/palmpay-logo.png') ?>" alt="PalmPay">
    <div class="p-top">
        <strong><?= $r->amountFormatted() ?></strong>
        <span class="p-status">Successful Transaction</span>
        <small><?= date('H:i, M j, Y', $ts) ?></small>
    </div>
    <?php else: ?>
    <header class="d-head">
        <span class="d-brand"><?php include __DIR__ . '/partials/logo.php'; ?><?= $e($r->bankName) ?></span>
        <h2>Transaction Details</h2>
    </header>
    <?php endif; ?>

    <?php if ($r->bankId === 'opaylite'): ?>
    <div class="op-l">
    <div class="o-top">
        <strong><?= $r->amountFormatted() ?></strong>
        <span class="o-status">Successful</span>
        <small><?= date('M jS, Y H:i:s', $ts) ?></small>
    </div>
    <div class="o-rows">
        <div class="o-row"><span class="k"><?= $isDeposit ? 'Recipient Details' : 'Recipient Details' ?></span><span class="v"><b><?= $e(strtoupper($r->name)) ?></b><span><?= $e($r->recipientBank) ?> | <?= $e($r->recipientAccount) ?></span></span></div>
        <div class="o-row"><span class="k">Sender Details</span><span class="v"><b><?= $e(strtoupper($r->senderName)) ?></b><span><?= $e($r->bankName) ?> | <?= $e(substr($r->senderAccount, 0, 3) . '****' . substr($r->senderAccount, -3)) ?></span></span></div>
        <div class="o-row"><span class="k">Transaction Type</span><span class="v"><?= $isDeposit ? 'Deposit' : 'Transfer to Bank Account' ?></span></div>
        <div class="o-row"><span class="k">Fees</span><span class="v"><?= $r->feeFormatted() ?></span></div>
        <?php if ($r->description !== ''): ?>
        <div class="o-row"><span class="k">Description</span><span class="v"><?= $e($r->description) ?></span></div>
        <?php endif; ?>
        <div class="o-row"><span class="k">Transaction No.</span><span class="v"><?= $e($r->reference) ?></span></div>
        <div class="o-row"><span class="k">Session ID</span><span class="v"><?= $e($r->sessionId) ?></span></div>
    </div>
    </div>
    <div class="op-d">
    <div class="oz-rows">
        <p><span>Date/Time</span><b><?= date('d/m/Y H:i', $ts) ?></b></p>
        <p><span>Sender Details</span><b><?= $e($r->senderName) ?><br>OPay|<?= $e(substr($r->senderAccount, 0, 4) . '****' . substr($r->senderAccount, -2)) ?></b></p>
        <p><span><?= $isDeposit ? 'Recipient Details' : 'Recipient Details' ?></span><b><?= $e(strtoupper($r->name)) ?><br><?= $e($r->recipientBank) ?>|<?= $e($r->recipientAccount) ?></b></p>
        <?php if ($r->description !== ''): ?>
        <p><span>Description</span><b><?= $e($r->description) ?></b></p>
        <?php endif; ?>
        <p class="oz-num"><span>SessionID</span><b><?= $e($r->sessionId) ?></b></p>
        <p class="oz-num"><span>Transaction No.</span><b><?= $e($r->reference . substr(preg_replace('/\D/', '', $r->sessionId), -8)) ?></b></p>
    </div>
    </div>
    <?php elseif ($r->bankId === 'kudos'): ?>
    <?php
    $kdParts = preg_split('/\s+/', trim($r->name));
    $kdFirst = count($kdParts) > 1 ? array_shift($kdParts) : '';
    $kdBen = strtoupper($kdFirst !== '' ? implode(' ', $kdParts) . ', ' . $kdFirst : $r->name);
    $kdVat = round($r->fee * 0.075, 2);
    $kdMoney = function ($n) { return '&#8358;' . number_format($n, $n == floor($n) ? 0 : 2); };
    ?>
    <div class="kd-card">
        <div class="kd-row"><span>Beneficiary Details</span><b><?= $e($kdBen) ?><small><?= $e($r->recipientBank) ?> | <?= $e($r->recipientAccount) ?></small></b></div>
        <div class="kd-row"><span>Sender Details</span><b><?= $e($r->senderName) ?><small><?= $e($r->bankName) ?> | <?= $e($r->senderAccount) ?></small></b></div>
        <div class="kd-row"><span>Paid On</span><b><?= date('F j, Y', $ts) ?><small><?= date('h:i A', $ts) ?></small></b></div>
        <div class="kd-row"><span>Transfer Fee</span><b><?= $kdMoney($r->fee) ?></b></div>
        <div class="kd-row"><span>VAT</span><b><?= $kdMoney($kdVat) ?></b></div>
        <div class="kd-row"><span>Description</span><b><?= $e($r->description !== '' ? $r->description : 'Transfer from ' . $r->senderName) ?></b></div>
        <div class="kd-row"><span>Transaction Reference</span><b class="kd-ref">000003-<?= $e($r->sessionId) ?></b></div>
        <div class="kd-row"><span>Payment Type</span><b><?= $isDeposit ? 'Inward Transfer' : 'Outward Transfer' ?></b></div>
    </div>
    <?php elseif ($r->bankId === 'ecobank'): ?>
    <?php $ecoTax = round($r->fee * 0.075, 2); $ecoTotal = $r->amount + $r->fee + $ecoTax; ?>
    <div class="eco2-card">
        <div class="eco2-logo"><img src="<?= asset('assets/img/ecobank-logo-color.png') ?>" alt="Ecobank" height="38"><small>The Pan African Bank</small></div>
        <h3>Transaction Successful <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><circle cx="12" cy="12" r="11" fill="#2e9b57"/><path d="M6.500 12.500l3.700 3.700 7.300-7.600" fill="none" stroke="#fff" stroke-width="2.400" stroke-linecap="round" stroke-linejoin="round"/></svg></h3>
        <span class="eco2-chip"><?= $isDeposit ? 'Deposit' : 'Send to Others' ?></span>
        <div class="eco2-sec eco2-dt"><span>Date <?= date('d M Y', $ts) ?></span><span>Time <?= date('g:i A', $ts) ?></span></div>
        <div class="eco2-sec eco2-sid"><b>Session<br>ID:</b><span><?= $e($r->sessionId) ?></span><svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="none" stroke="#0a7fb5" stroke-width="2" d="M9 3h10a2 2 0 0 1 2 2v12H9zM5 7h4M5 7v14h12v-2"/></svg></div>
        <div class="eco2-sec"><small>Source Account</small><b><?= $e(strtoupper($r->senderName)) ?></b></div>
        <div class="eco2-sec"><small><?= $isDeposit ? 'Recipient Details' : 'Beneficiary Details' ?></small><b><?= $e(strtoupper($r->name)) ?> (<?= $e($r->recipientAccount) ?>)</b><small class="eco2-bn">Bank Name: <?= $e($r->recipientBank) ?></small></div>
        <div class="eco2-sec eco2-amt">
            <p><span>Transaction Amount</span><b>NGN <?= number_format($r->amount, 2) ?></b></p>
            <p><span>Charges</span><b>NGN <?= number_format($r->fee, 2) ?></b></p>
            <p><span>Tax</span><b>NGN <?= number_format($ecoTax, 2) ?></b></p>
        </div>
        <div class="eco2-sec eco2-total"><span>Total Amount</span><b>NGN <?= number_format($ecoTotal, 2) ?></b></div>
        <div class="eco2-sec eco2-nar"><div><small>Transaction Narration</small><span><?= $e($r->description !== '' ? $r->description : '-') ?></span></div><i class="eco2-chip">Others</i></div>
    </div>
    <?php elseif ($r->bankId === 'firstline'): ?>
    <div class="fk-rows">
        <div class="fk-row"><span class="k">From:</span><span class="v"><?= $e('*****' . substr($r->senderAccount, -5)) ?></span></div>
        <div class="fk-row"><span class="k">Sender Name:</span><span class="v"><?= $e(strtoupper($r->senderName)) ?></span></div>
        <div class="fk-row"><span class="k"><?= $isDeposit ? 'Recipient Name:' : 'Beneficiary Name:' ?></span><span class="v"><?= $e(strtoupper($r->name)) ?></span></div>
        <div class="fk-row"><span class="k">Account No:</span><span class="v"><?= $e($r->recipientAccount) ?></span></div>
        <div class="fk-row"><span class="k">Bank:</span><span class="v"><?= $e(strtoupper($r->recipientBank)) ?></span></div>
        <div class="fk-row"><span class="k">Transaction Type:</span><span class="v"><?= $isDeposit ? 'Inward Deposit' : 'Interbank Transfer' ?></span></div>
        <div class="fk-row"><span class="k">Fee:</span><span class="v"><?= $r->feeFormatted() ?></span></div>
        <div class="fk-row"><span class="k">Reference No:</span><span class="v"><?= $e($r->sessionId) ?></span></div>
        <div class="fk-row"><span class="k">Narration:</span><span class="v"><?= $e($r->description !== '' ? $r->description : 'None') ?></span></div>
    </div>
    <?php elseif ($r->bankId === 'accent'): ?>
    <h3 class="ac-title">Transaction Receipt</h3>
    <p class="ac-gen">Generated from <b>AccessMore</b> on <?= date('d/m/y H:i:s', $ts) ?></p>
    <div class="ac-rows">
        <div class="ac-row"><span class="k">Transaction Amount</span><span class="v">N<?= number_format($r->amount, $r->amount == floor($r->amount) ? 0 : 2) ?></span></div>
        <div class="ac-row"><span class="k">Transaction Type</span><span class="v"><?= $isDeposit ? 'DEPOSIT' : 'INTER-BANK' ?></span></div>
        <div class="ac-row"><span class="k">Transaction Date</span><span class="v"><?= date('Y-m-d H:i:s', $ts) ?></span></div>
        <div class="ac-row"><span class="k">Sender</span><span class="v"><?= $e(strtoupper($r->senderName)) ?></span></div>
        <div class="ac-row"><span class="k"><?= $isDeposit ? 'Recipient' : 'Beneficiary' ?></span><span class="v"><?= $e(strtoupper($r->name)) ?><br><?= $e($r->recipientAccount) ?><br><?= $e($r->recipientBank) ?></span></div>
        <div class="ac-row"><span class="k">Fee</span><span class="v">N<?= number_format($r->fee, $r->fee == floor($r->fee) ? 0 : 2) ?></span></div>
        <?php if ($r->description !== ''): ?>
        <div class="ac-row"><span class="k">Remark</span><span class="v"><?= $e($r->description) ?></span></div>
        <?php endif; ?>
        <div class="ac-row"><span class="k">Transaction Reference</span><span class="v"><?= $e($r->reference) ?></span></div>
        <div class="ac-row"><span class="k">Session Id</span><span class="v"><?= $e($r->sessionId) ?></span></div>
        <div class="ac-row"><span class="k">Transaction Status</span><span class="v"><?= $isDeposit ? 'Deposit' : 'Transfer Request' ?> Successful</span></div>
    </div>
    <?php elseif ($r->bankId === 'zenor'): ?>
    <div class="zr-body">
        <p><span>Transaction Type</span><b><?= $isDeposit ? 'Inward Deposit' : ($r->recipientBank === $r->bankName ? 'Intra-Bank Transfer' : 'Inter-Bank Transfer') ?></b></p>
        <p><span>Transaction Date</span><b><?= date('d-M-Y, g:i:s A', $ts) ?></b></p>
        <p><span>Session ID</span><b><?= $e($r->sessionId) ?></b></p>
        <p><span>Reference ID</span><b>EXTTRF|<?= $e($r->reference) ?></b></p>
        <hr>
        <p><span>Debit Account</span><b><?= $e($r->senderName . ' - ' . substr($r->senderAccount, 0, 4) . '***' . substr($r->senderAccount, -3)) ?></b></p>
        <p><span>Credit Account</span><b><?= $e($r->recipientAccount) ?></b></p>
        <p><span>Beneficiary</span><b><?= $e($r->name) ?></b></p>
        <hr class="zr-gap">
        <p><span>Bank</span><b><?= $e(strtoupper($r->recipientBank)) ?></b></p>
        <p><span>Narration</span><b><?= $e($r->description !== '' ? $r->description : '-') ?></b></p>
        <p><span>Amount</span><b><?= number_format($r->amount, 2) ?></b></p>
        <p><span>Status</span><b class="zr-ok">Successful</b></p>
        <div class="zr-open"><em>Open a Zenith Bank account, visit</em><div><b>www.zenithbank.com/openaccount</b><i></i></div></div>
        <div class="zr-disc">
            <b>Disclaimer:</b> Your transaction has been successfully processed. Note, however, that completion of any transfer may be affected by other factors including but not limited to transmission errors, incomplete information, fluctuations on the network/internet, interruptions, glitch, delayed information or other matters beyond the Bank's control which may impact on the transaction and for which the Bank will not be liable. All transactions are subject to Zenith Bank confirmation and fraud proof verification.
            <p>For any other assistance, kindly contact us on<br><a>zenithdirect@zenithbank.com</a> and <a>234-201-2787000</a></p>
        </div>
    </div>
    <?php elseif ($r->bankId === 'unity'): ?>
    <div class="ut">
        <div class="ut-head"><h3><?= $isDeposit ? 'Deposit' : 'Transfer' ?></h3><div><small><?= $isDeposit ? 'Deposit' : 'Transfer' ?> ID</small><b><?= $e(substr(preg_replace('/\D/', '', $r->reference), -8)) ?></b></div></div>
        <div class="ut-row"><span class="k">Transaction date</span><span class="v"><?= date('d-M-Y', $ts) ?></span></div>
        <div class="ut-row"><span class="k">Sender's Name</span><span class="v b"><?= $e(strtoupper($r->senderName)) ?></span></div>
        <div class="ut-row"><span class="k">Source</span><span class="v b"><?= $e('xxxxxx' . substr($r->senderAccount, -4)) ?></span></div>
        <div class="ut-row"><span class="k"><?= $isDeposit ? 'Recipient Name' : 'Destination Name' ?></span><span class="v b"><?= $e(strtoupper($r->name)) ?></span></div>
        <div class="ut-row"><span class="k">Destination</span><span class="v b"><?= $e($r->recipientAccount) ?></span></div>
        <div class="ut-row"><span class="k">Amount</span><span class="v b"><?= $r->amountFormatted() ?></span></div>
        <div class="ut-row"><span class="k">Fee</span><span class="v b"><?= $r->feeFormatted() ?></span></div>
        <div class="ut-row"><span class="k">Receiver&rsquo;s Bank</span><span class="v b"><?= $e($r->recipientBank) ?></span></div>
        <div class="ut-row"><span class="k">Reason</span><span class="v b"><?= $e($r->description !== '' ? $r->description : '-') ?></span></div>
    </div>
    <?php elseif ($r->bankId === 'gtrust'): ?>
    <?php
    $gtKobo = (int)round(($r->amount - floor($r->amount)) * 100);
    $gtParts = preg_split('/\s+/', trim($r->name));
    $gtFirst = count($gtParts) > 1 ? array_shift($gtParts) : '';
    $gtReceiver = $gtFirst !== '' ? implode(' ', $gtParts) . ', ' . $gtFirst : $r->name;
    $gtRef = 'M0' . substr($r->reference, -7);
    ?>
    <div class="gz-body">
        <div class="gz-amt"><span>&#8358;</span><b><?= number_format(floor($r->amount)) ?></b><i>.<?= str_pad((string)$gtKobo, 2, '0', STR_PAD_LEFT) ?></i></div>
        <p class="gz-words"><?= $e($words(floor($r->amount))) ?> Naira <?= $gtKobo ? $e($words($gtKobo)) : 'Zero' ?> Kobo</p>
        <p class="gz-ok"><?= $isDeposit ? 'Deposit successful' : 'Success' ?></p>
        <dl>
            <dt>Sender</dt><dd><?= $e($r->senderName) ?></dd>
            <dt><?= $isDeposit ? 'Recipient' : 'Receiver' ?></dt><dd><?= $e($gtReceiver) ?></dd>
            <dt>Account number</dt><dd><?= $e($r->recipientAccount) ?></dd>
            <dt>Receiving bank</dt><dd><?= $e($r->recipientBank) ?></dd>
            <dt>Reference number</dt><dd><?= $e($gtRef) ?></dd>
            <dt>Remark</dt><dd><?= $r->description !== '' ? $e($r->description) . ' - ' : '' ?>Transfer Between Customers via GTWorld &nbsp;REF: <?= $e($gtRef) ?> from <?= $e($r->senderName) ?> to <?= $e($gtReceiver) ?></dd>
        </dl>
    </div>
    <?php elseif ($r->bankId === 'nombra'): ?>
    <h3 class="nb-title">PAYMENT DETAILS</h3>
    <div class="nb-card">
        <div class="nb-row"><span class="k">Status</span><span class="v nb-ok">Successful</span></div>
        <div class="nb-row"><span class="k">Type</span><span class="v"><?= $isDeposit ? 'Deposit' : 'Transfer' ?></span></div>
        <div class="nb-row"><span class="k"><?= $isDeposit ? 'Recipient' : 'Beneficiary' ?></span><span class="v"><?= $e($r->name) ?><small><?= $e($r->recipientAccount) ?> | <?= $e($r->recipientBank) ?></small></span></div>
        <div class="nb-row"><span class="k">Sender</span><span class="v"><?= $e($r->senderName) ?><small><?= $e($r->senderAccount) ?></small></span></div>
        <div class="nb-row"><span class="k">Fee</span><span class="v"><?= $r->feeFormatted() ?></span></div>
        <?php if ($r->description !== ''): ?>
        <div class="nb-row"><span class="k">Description</span><span class="v"><?= $e($r->description) ?></span></div>
        <?php endif; ?>
        <div class="nb-row"><span class="k">Date</span><span class="v"><?= date('m/d/Y, h:iA', $ts) ?></span></div>
        <div class="nb-row"><span class="k">Reference</span><span class="v nb-ref"><?= $e($r->reference) ?></span></div>
    </div>
    <?php elseif ($r->bankId === 'carbonite'): ?>
    <div class="cn-rows">
        <div class="cn-sec"><b>Narration</b><span><?= $isDeposit
            ? 'NIP//' . $e($r->sessionId) . ' - FROM ' . $e(strtoupper($r->senderName)) . '//Transfer from ' . $e(strtoupper($r->senderName))
            : 'TRF/' . $e($r->name) . '/' . $e($cnDigits) . '/CB/' . $e($cnRef) ?><?= $r->description !== '' ? ' - ' . $e($r->description) : '' ?></span></div>
        <?php if (!$isDeposit): ?>
        <div class="cn-sec"><b>Note</b><span>CB/<?= $e($cnRef) ?></span></div>
        <?php endif; ?>
        <div class="cn-sec cn-split"><div><b>Total</b><span><?= $cnMoney($cnTotal) ?></span></div><em class="cn-fee">Fee: <?= $cnMoney($cnFee) ?></em></div>
        <div class="cn-sec"><b>Govt. Stamp Duty <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><circle cx="12" cy="12" r="10" fill="none" stroke="#fff" stroke-width="1.800"/><path d="M12 11v6M12 7.500v.1" stroke="#fff" stroke-width="2" stroke-linecap="round"/></svg></b><span><?= $cnMoney($cnStamp) ?></span></div>
        <div class="cn-sec cn-split"><div><b>Transaction Reference</b><span><?= $e($isDeposit ? $r->sessionId : $cnRef) ?></span></div><i class="cn-copy"><svg viewBox="0 0 24 24" width="26" height="26" aria-hidden="true"><rect x="9" y="3" width="12" height="12" rx="3" fill="#2c1a74"/><rect x="3" y="9" width="12" height="12" rx="3.500" fill="#5d30d3"/></svg></i></div>
        <div class="cn-sec cn-split"><div><b>Session ID</b><span><?= $e($r->sessionId) ?></span></div><i class="cn-copy"><svg viewBox="0 0 24 24" width="26" height="26" aria-hidden="true"><rect x="9" y="3" width="12" height="12" rx="3" fill="#2c1a74"/><rect x="3" y="9" width="12" height="12" rx="3.500" fill="#5d30d3"/></svg></i></div>
        <div class="cn-cat"><b>Category</b><div><span>Miscellaneous</span><svg viewBox="0 0 24 24" width="14" height="14" aria-hidden="true"><path d="M5 8h14l-7 9z" fill="#5d30d3"/></svg></div></div>
    </div>
    <?php elseif ($r->bankId === 'sparkly'): ?>
    <div class="sp-card">
        <p><span>Session ID</span><b class="sp-sid"><em><?= $e($r->sessionId) ?></em><i><svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><rect x="9" y="3" width="12" height="12" rx="3.500" fill="#7fd36b"/><rect x="3" y="9" width="12" height="12" rx="3.500" fill="#3aa845"/></svg></i></b></p>
        <p><span>Date &amp; Time</span><b><?= date('D, M j Y g:i:s a', $ts) ?></b></p>
        <p><span>Activity</span><b><?= $isDeposit ? 'Credit' : 'Debit' ?></b></p>
        <p><span>Activity Type</span><b><?= $isDeposit ? 'Receive Money' : 'Send Money' ?></b></p>
        <p><span><?= $isDeposit ? 'Paid From' : 'Paid To' ?></span><b><?= $e($isDeposit ? $r->senderName : $r->name) ?></b></p>
        <p><span>Bank</span><b><?= $e($isDeposit ? $r->bankName : $r->recipientBank) ?></b></p>
        <?php if ($r->description !== ''): ?>
        <p><span>Narration</span><b><?= $e($r->description) ?></b></p>
        <?php endif; ?>
        <p><span>Category</span><b>General</b></p>
    </div>
    <?php elseif ($r->bankId === 'moniepoint'): ?>
    <div class="mp-panel">
        <div class="mp-item"><span class="k">Transaction Type</span><span class="mp-chip"><?= $isDeposit ? 'Deposit' : 'Transfer' ?></span></div>
        <div class="mp-item"><span class="k">Sender Name</span><span class="v"><?= $e($r->senderName) ?></span></div>
        <div class="mp-item"><span class="k">Source Institution</span><span class="v"><?= $e($r->bankName) ?></span></div>
        <div class="mp-item"><span class="k"><?= $isDeposit ? 'Recipient' : 'Beneficiary' ?></span><span class="v"><?= $e(strtoupper($r->name)) ?></span></div>
        <div class="mp-item"><span class="k"><?= $isDeposit ? 'Recipient Account' : 'Beneficiary Account' ?></span><span class="v"><?= $e($r->recipientAccount) ?></span></div>
        <div class="mp-item"><span class="k"><?= $isDeposit ? 'Recipient Institution' : 'Beneficiary Institution' ?></span><span class="v"><?= $e($r->recipientBank) ?></span></div>
        <div class="mp-item"><span class="k">Fee</span><span class="v"><?= $r->feeFormatted() ?></span></div>
        <?php if ($r->description !== ''): ?>
        <div class="mp-item"><span class="k">Description</span><span class="v"><?= $e($r->description) ?></span></div>
        <?php endif; ?>
        <div class="mp-item"><span class="k">Transaction Date</span><span class="v"><?= date('l, F jS | g:i A', $ts) ?></span></div>
        <div class="mp-item"><span class="k">Transaction Reference</span><span class="v"><?= $e($r->reference) ?></span></div>
        <div class="mp-item"><span class="k">Session ID</span><span class="v"><?= $e($r->sessionId) ?></span></div>
    </div>
    <?php elseif ($r->bankId === 'palmwave'): ?>
    <?php $isMmo = in_array($r->recipientBank, ['Opay', 'Palmpay'], true); ?>
    <div class="pp-body">
        <div class="pp-party">
            <span class="k"><?= $isDeposit ? 'Recipient:' : 'Recipient:' ?></span>
            <span class="v"><b><?= $e(strtoupper($r->name)) ?></b><small><?= $e($r->recipientBank) ?> | <?= $e($isMmo ? substr($r->recipientAccount, 0, 3) . ' ' . substr($r->recipientAccount, 3, 3) . ' ' . substr($r->recipientAccount, 6) : $r->recipientAccount) ?></small></span>
        </div>
        <div class="pp-party">
            <span class="k">Sender:</span>
            <span class="v"><b><?= $e(strtoupper($r->senderName)) ?></b><small><?= $e($r->bankName) ?> | <?= $e(substr($r->senderAccount, 0, 3) . ' **** ' . substr($r->senderAccount, -4)) ?></small></span>
        </div>
        <div class="pp-info">
            <h3>Transaction Info:</h3>
            <div class="pp-row"><span class="k">Transaction Type</span><span class="v"><?= $isDeposit ? 'Deposit' : ($isMmo ? 'Money Transfer - MMO' : 'Money Transfer - Bank account') ?></span></div>
            <div class="pp-row"><span class="k">Fees</span><span class="v"><?= $r->feeFormatted() ?></span></div>
            <?php if ($r->description !== ''): ?>
            <div class="pp-row"><span class="k">Description</span><span class="v"><?= $e($r->description) ?></span></div>
            <?php endif; ?>
            <div class="pp-row"><span class="k">Transaction ID</span><span class="v"><?= $e(substr(md5($r->reference), 0, 13)) ?></span></div>
            <div class="pp-row"><span class="k">Session ID</span><span class="v"><?= $e($r->sessionId) ?></span></div>
        </div>
    </div>
    <?php else: ?>
    <?php if (!in_array($r->bankId, ['moniepoint', 'sparkly', 'carbonite', 'nombra', 'gtrust', 'unity', 'zenor', 'accent', 'firstline', 'ecobank', 'kudos'], true)): ?>
    <div class="d-amount">
        <small>Transaction Amount</small>
        <strong><?= $r->amountFormatted() ?></strong>
    </div>
    <?php endif; ?>

    <div class="d-row">
        <span class="k"><?= $isDeposit ? 'Recipient Details' : 'Beneficiary Details' ?></span>
        <span class="v"><b><?= $e($r->name) ?></b><small><?= $e($r->recipientBank) ?> | <?= $e($r->recipientAccount) ?></small></span>
    </div>
    <div class="d-row">
        <span class="k">Sender Details</span>
        <span class="v"><b><?= $e($r->senderName) ?></b><small><?= $e($r->bankName) ?> | <?= $e($r->senderAccount) ?></small></span>
    </div>
    <div class="d-row">
        <span class="k">Paid On</span>
        <span class="v"><b><?= date('M j, Y', $ts) ?></b><small><?= date('g:i A', $ts) ?></small></span>
    </div>
    <?php if ($r->bankId !== 'sparkly'): ?>
    <div class="d-row"><span class="k">Fees</span><span class="v"><b><?= $r->feeFormatted() ?></b></span></div>
    <?php endif; ?>
    <?php if ($r->description !== ''): ?>
    <div class="d-row"><span class="k">Description</span><span class="v"><b><?= $e($r->description) ?></b></span></div>
    <?php endif; ?>
    <div class="d-row"><span class="k">Transaction Reference</span><span class="v"><b class="mono"><?= $e($r->sessionId) ?></b></span></div>
    <div class="d-row"><span class="k">Payment Type</span><span class="v"><b><?= $isDeposit ? 'Inward Deposit' : 'Outward Transfer' ?></b></span></div>

    <?php endif; ?>

    <?php if ($r->bankId === 'ecobank'): ?>
    <div class="eco2-done">Done</div>
    <?php elseif ($r->bankId === 'firstline'): ?>
    <div class="fk-foot">
        <small>For more information, please contact us: 0700-FIRSTCONTACT, firstcontact@firstbanknigeria.com, complaints@firstbanknigeria.com, www.firstbanknigeria.com</small>
        <p>Get airtime in seconds by dialing <b>*894*Amount#</b></p>
        <p>Out of Data? Dial <b>*894*2#</b> to get data now.</p>
        <small>Your transfer has been successfully sent. The beneficiary's account will be credited. However, this does not serve as confirmation of credit into the beneficiary's account. Due to the nature of the internet, transactions may be subject to interruption, transmission blackout, delayed transmission and incorrect data transmission. The Bank is not liable for malfunctions in communications facilities not within its control that may affect the accuracy or timeliness of messages and transactions you send. All transactions are subject to verification and normal fraud checks.</small>
    </div>
    <?php elseif ($r->bankId === 'accent'): ?>
    <div class="ac-foot">
        <p>If you have any questions or would like more information, please call our 24-hour Contact Centre on <a>0700CallAccess</a>, <a>0700 3000000</a>, <a>+234 1-2712005-7</a>, <a>+234 1-2802500</a> or send an email to <a href="mailto:contactcentre@accessbankplc.com">contactcentre@accessbankplc.com</a>.<br>Thank you for choosing Access Bank.</p>
        <p class="ac-bw">Banking with Access: Branch | ATM | Online | Mobile | Contact centre</p>
    </div>
    <?php elseif ($r->bankId === 'zenor'): ?>
    <?php elseif ($r->bankId === 'unity'): ?>
    <div class="uf">
        <p><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 5h18a1 1 0 0 1 1 1v.5l-10 6.5L2 6.5V6a1 1 0 0 1 1-1zm19 3.4V18a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V8.4l10 6.5 10-6.5z"/></svg>cfc@ubagroup.com</p>
        <p><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6.6 10.8a15 15 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.25 11.4 11.4 0 0 0 3.6.57 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.25.2 2.45.57 3.57a1 1 0 0 1-.25 1z"/></svg>(+234) 01-2808822, (+234) 01-6319822, 0700 225 5822</p>
        <p><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm6.9 9h-3a15.7 15.7 0 0 0-1.4-6A8 8 0 0 1 18.9 11zM12 4c.8 1 1.8 3.1 2 7h-4c.2-3.9 1.2-6 2-7zM9.5 5a15.7 15.7 0 0 0-1.4 6h-3A8 8 0 0 1 9.5 5zM5.1 13h3a15.7 15.7 0 0 0 1.4 6A8 8 0 0 1 5.1 13zM12 20c-.8-1-1.8-3.1-2-7h4c-.2 3.9-1.2 6-2 7zm2.5-1a15.700 15.700 0 0 0 1.4-6h3a8 8 0 0 1-4.4 6z"/></svg>www.ubagroup.com</p>
    </div>
    <?php elseif ($r->bankId === 'gtrust'): ?>
    <?php elseif ($r->bankId === 'nombra'): ?>
    <div class="n-foot">
        <time><?= date('m/d/Y , h:i A', $ts) ?></time>
        <p><b>IMPORTANT:</b> If you have questions or issues with this payment, contact Nomba at <a href="mailto:support@nomba.com">support@nomba.com</a></p>
    </div>
    <?php elseif ($r->bankId === 'carbonite'): ?>
    <div class="cn-dispute">Issue with this transaction? <b>Dispute Transaction</b></div>
    <?php elseif ($r->bankId === 'sparkly'): ?>
    <?php elseif ($r->bankId === 'moniepoint'): ?>
    </div>
    <img class="m-footer" src="<?= asset('assets/img/moniepoint-footer.png') ?>" alt="">
    <?php elseif ($r->bankId === 'palmwave'): ?>
    <div class="pp-foot">
        <p>90+ Free Bank Transfers Every Month!<br>Get cashbacks in Airtime &amp; data top-up!<br>Up to 150k Naira credit lines &amp; 16 days interest free!<br>Enjoy all at PalmPay!</p>
    </div>
    <?php elseif ($r->bankId === 'opaylite'): ?>
    <div class="op-l">
    <img class="d-footer-img" src="<?= asset('assets/img/opay-footer.png') ?>" alt="Enjoy a better life with OPay">
    </div>
    <div class="op-d">
    <div class="oz-foot">
        <p>CUSTOMER COPY REPRINT</p>
        <p>SUPPORT</p>
        <a>pos-service@opay-inc.com</a>
        <a>07008888329</a>
    </div>
    </div>
    <?php elseif ($r->bankId === 'kudos'): ?>
    <div class="kd-pill"><i><b>KU</b><b>DA</b></i><p>Not on Kuda? Tap here to download the Kuda app.<br>Get more out of your money, and more for your life.</p></div>
    <p class="kd-legal">&copy; <?= date('Y') ?> Kuda Technologies Ltd (Company No. 11472232).<br>All rights reserved.Nigerian banking services offered by Kuda Microfinance Bank (RC796975) with registered address at 1-11 Commercial avenue, Yaba, Lagos, Nigeria. Kuda Microfinance Bank is licensed by the Central Bank of Nigeria. Deposits are insured by the Nigerian Deposit Insurance Corporation (NDIC).</p>
    <?php else: ?>
    <div class="d-promo">
        <?php include __DIR__ . '/partials/logo.php'; ?>
        <p>Enjoying <?= $e($r->bankName) ?>? Tap here to get the app.</p>
    </div>
    <p class="d-legal">© <?= date('Y') ?> <?= $e($r->bankName) ?> (sample brand). This is a design-practice sample, not a real transaction. Ref: <?= $e($r->reference) ?>.</p>
    <?php endif; ?>

    <?php include __DIR__ . '/partials/watermark.php'; ?>
</div>
