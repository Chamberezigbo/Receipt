<?php /** @var ReceiptData $r */
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
<div class="receipt detailed <?= $r->bankId === 'kudos' ? 'kuda ' : '' ?><?= $r->bankId === 'opaylite' ? 'opay ' : '' ?><?= $r->bankId === 'moniepoint' ? 'monie ' : '' ?><?= $e($variant ?? '') ?>" style="--brand: <?= $e($r->color) ?>">
    <?php if ($r->bankId === 'kudos'): ?>
    <img class="d-head-img" src="assets/img/kuda-header.png" alt="Kuda - Transaction Details">
    <?php elseif ($r->bankId === 'opaylite'): ?>
    <img class="d-head-img" src="assets/img/opay-header.png" alt="OPay - Transaction Receipt">
    <?php elseif ($r->bankId === 'ecobank'): ?>
    <div class="eco-head"><img src="assets/img/ecobank-logo.png" alt="Ecobank - The Pan African Bank" height="52"></div>
    <?php elseif ($r->bankId === 'firstline'): ?>
    <div class="fbk-head">
        <img class="fbk-shapes" src="assets/img/firstbank-shapes.png" alt="" aria-hidden="true">
        <img class="fbk-logo" src="assets/img/firstbank-logo.png" alt="FirstBank" height="34">
        <h3>Transaction Receipt</h3>
        <p class="fbk-ok">Successful</p>
        <strong><?= $r->amountFormatted() ?></strong>
        <small><?= $e($words(floor($r->amount))) ?> Naira Only</small>
        <time><?= date('F d, Y H:i:s', $ts) ?></time>
    </div>
    <?php elseif ($r->bankId === 'accent'): ?>
    <img class="a-head" src="assets/img/access-header.png" alt="Access - more than banking">
    <?php elseif ($r->bankId === 'zenor'): ?>
    <div class="z-head">
        <b><?= $isDeposit ? 'Deposit' : 'Transfer' ?> Receipt</b>
        <img src="assets/img/zenith-logo.png" alt="Zenith Bank" height="30">
    </div>
    <?php elseif ($r->bankId === 'unity'): ?>
    <div class="u-head">
        <div class="u-bar"><span aria-hidden="true">&#8249;</span><b>Transaction receipt</b></div>
        <img src="assets/img/uba-avatar.png" alt="" height="64">
        <p class="u-ok"><?= $isDeposit ? 'Deposit' : 'Transfer' ?> successful</p>
        <h3><?= $isDeposit ? 'From' : 'To' ?> <?= $e($r->name) ?></h3>
    </div>
    <?php elseif ($r->bankId === 'gtrust'): ?>
    <div class="g-head"><img src="assets/img/gtco-logo.png" alt="GTCO - Guaranty Trust Nigeria" height="64"></div>
    <?php elseif ($r->bankId === 'nombra'): ?>
    <div class="n-head">
        <img src="assets/img/nomba-logo.png" alt="nomba" height="18">
        <p>Nomba Financial Services Limited<br>E-receipt</p>
        <strong><?= $isDeposit ? '+' : '-' ?><?= $r->amountFormatted() ?></strong>
    </div>
    <?php elseif ($r->bankId === 'carbonite'): ?>
    <div class="c-head">
        <img src="assets/img/carbon-header.png" alt="Carbon">
        <span><?= $isDeposit ? 'Deposit' : 'Transfer' ?> Receipt</span>
    </div>
    <?php elseif ($r->bankId === 'sparkly'): ?>
    <div class="s-head">
        <small>You are about to send</small>
        <b><?= $e($r->name) ?></b>
        <small>on Invoice</small>
        <small>Invoice No: <?= $e(substr(preg_replace('/\D/', '', $r->reference), -6)) ?></small>
        <small class="s-gap">Total Sum</small>
        <strong><?= $r->amountFormatted() ?></strong>
    </div>
    <?php elseif ($r->bankId === 'moniepoint'): ?>
    <img class="m-banner" src="assets/img/moniepoint-header.png" alt="Moniepoint">
    <div class="m-sheet">
    <div class="m-card">
        <div>
            <span class="m-chip"><?= $isDeposit ? 'CREDIT' : 'DEBIT' ?></span>
            <strong><?= $r->amountFormatted() ?></strong>
        </div>
        <img src="assets/img/moniepoint-icon.png" alt="" width="36" height="36">
    </div>
    <?php elseif ($r->bankId === 'palmwave'): ?>
    <img class="p-logo" src="assets/img/palmpay-logo.png" alt="PalmPay">
    <div class="p-top">
        <strong><?= $r->amountFormatted() ?></strong>
        <span class="p-status">Successful Transaction</span>
        <small><?= date('g:i A, M j, Y', $ts) ?></small>
    </div>
    <?php else: ?>
    <header class="d-head">
        <span class="d-brand"><?php include __DIR__ . '/partials/logo.php'; ?><?= $e($r->bankName) ?></span>
        <h2>Transaction Details</h2>
    </header>
    <?php endif; ?>

    <?php if ($r->bankId === 'opaylite'): ?>
    <div class="o-top">
        <strong><?= $r->amountFormatted() ?></strong>
        <span class="o-status">Successful</span>
        <small><?= date('M jS, Y H:i:s', $ts) ?></small>
    </div>
    <div class="o-rows">
        <div class="o-row"><span class="k"><?= $isDeposit ? 'Depositor Details' : 'Recipient Details' ?></span><span class="v"><b><?= $e(strtoupper($r->name)) ?></b><span><?= $e($r->recipientBank) ?> | <?= $e($r->recipientAccount) ?></span></span></div>
        <div class="o-row"><span class="k">Sender Details</span><span class="v"><b><?= $e(strtoupper($r->senderName)) ?></b><span><?= $e($r->bankName) ?> | <?= $e(substr($r->senderAccount, 0, 3) . '****' . substr($r->senderAccount, -3)) ?></span></span></div>
        <div class="o-row"><span class="k">Transaction Type</span><span class="v"><?= $isDeposit ? 'Deposit' : 'Transfer to Bank Account' ?></span></div>
        <div class="o-row"><span class="k">Fees</span><span class="v"><?= $r->feeFormatted() ?></span></div>
        <?php if ($r->description !== ''): ?>
        <div class="o-row"><span class="k">Description</span><span class="v"><?= $e($r->description) ?></span></div>
        <?php endif; ?>
        <div class="o-row"><span class="k">Transaction No.</span><span class="v"><?= $e($r->reference) ?></span></div>
        <div class="o-row"><span class="k">Session ID</span><span class="v"><?= $e($r->sessionId) ?></span></div>
    </div>
    <?php elseif ($r->bankId === 'ecobank'): ?>
    <div class="eco-foot">
        <b>ECOBANK TRANSACTION SLIP</b>
        <p><b>RECEIPT NO:</b> 000-GOODFRIDAY</p>
        <p><b>DATE:</b> April 18, 33 AD</p>
        <table>
            <tr><th>NO</th><th>ITEM DESCRIPTION</th><th>STATUS</th></tr>
            <tr><td>1</td><td>BURDEN</td><td>CLEARED</td></tr>
            <tr><td>2</td><td>GUILT</td><td>CANCELLED</td></tr>
            <tr><td>3</td><td>DEBT</td><td>PAID</td></tr>
            <tr><td>4</td><td>SHAME</td><td>REMOVED</td></tr>
            <tr><td>5</td><td>GRACE</td><td>CREDITED</td></tr>
        </table>
        <div class="eco-sum">
            <div><p><b>REFERENCE:</b> IT'S DONE.</p><p><b>PAYMENT METHOD:</b> SACRIFICE</p><p><b>FINAL STATUS:</b> CLEARED FOR ALL.</p></div>
            <p><b>BALANCE:</b> 0.00GHS</p>
        </div>
    </div>
    <?php elseif ($r->bankId === 'firstline'): ?>
    <div class="fk-rows">
        <div class="fk-row"><span class="k">From:</span><span class="v"><?= $e('*****' . substr($r->senderAccount, -5)) ?></span></div>
        <div class="fk-row"><span class="k">Sender Name:</span><span class="v"><?= $e(strtoupper($r->senderName)) ?></span></div>
        <div class="fk-row"><span class="k"><?= $isDeposit ? 'Depositor Name:' : 'Beneficiary Name:' ?></span><span class="v"><?= $e(strtoupper($r->name)) ?></span></div>
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
        <div class="ac-row"><span class="k"><?= $isDeposit ? 'Depositor' : 'Beneficiary' ?></span><span class="v"><?= $e(strtoupper($r->name)) ?><br><?= $e($r->recipientAccount) ?><br><?= $e($r->recipientBank) ?></span></div>
        <div class="ac-row"><span class="k">Fee</span><span class="v">N<?= number_format($r->fee, $r->fee == floor($r->fee) ? 0 : 2) ?></span></div>
        <?php if ($r->description !== ''): ?>
        <div class="ac-row"><span class="k">Remark</span><span class="v"><?= $e($r->description) ?></span></div>
        <?php endif; ?>
        <div class="ac-row"><span class="k">Transaction Reference</span><span class="v"><?= $e($r->reference) ?></span></div>
        <div class="ac-row"><span class="k">Session Id</span><span class="v"><?= $e($r->sessionId) ?></span></div>
        <div class="ac-row"><span class="k">Transaction Status</span><span class="v"><?= $isDeposit ? 'Deposit' : 'Transfer Request' ?> Successful</span></div>
    </div>
    <?php elseif ($r->bankId === 'zenor'): ?>
    <div class="zn-bar"><?= date('l, F d, Y', $ts) ?></div>
    <table class="zn-table">
        <tr><th>Transfer Type:</th><td><?= $isDeposit ? 'Inward Deposit' : 'Domestic Transfer' ?></td></tr>
        <tr><th>Transaction Date:</th><td><?= $e(strtolower(date('d-M-Y', $ts))) ?></td></tr>
        <tr><th>Debit Account:</th><td><?= $e('xxxxxx' . substr($r->senderAccount, -4)) ?></td></tr>
        <tr><th>Sender Name:</th><td><?= $e(strtoupper($r->senderName)) ?></td></tr>
        <tr><th>Credit Account:</th><td><?= $e($r->recipientAccount) ?></td></tr>
        <tr><th>Beneficiary Name:</th><td><?= $e(strtoupper($r->name)) ?></td></tr>
        <tr><th>Bank:</th><td><?= $e(strtoupper($r->recipientBank)) ?></td></tr>
        <tr><th>Status:</th><td>Completed</td></tr>
        <tr><th>Amount:</th><td>NGN <?= number_format($r->amount, 2) ?></td></tr>
        <tr><th>Fee:</th><td>NGN <?= number_format($r->fee, 2) ?></td></tr>
        <tr><th>Description:</th><td><?= $e($r->description !== '' ? $r->description : '-') ?></td></tr>
    </table>
    <div class="zn-disc">
        <b>DISCLAIMER:</b>
        <p>Your transaction has been successfully processed. Note, however, that completion of any transfer may be affected by other factors including but not limited to transmission error, incomplete information, fluctuations on the network/internet, interruptions, glitch delayed information or other matters beyond the Bank's control which may impact on the transaction and for which the Bank will not be liable. All transactions are subject to Zenith Bank confirmation and fraud proof of verification.</p>
    </div>
    <?php elseif ($r->bankId === 'unity'): ?>
    <div class="ub-card">
        <h4>Transaction details</h4>
        <div class="ub-row"><span class="k">Date</span><span class="v"><?= date('D M d Y', $ts) ?></span></div>
        <div class="ub-row"><span class="k">Time</span><span class="v"><?= date('H:i:s', $ts) ?></span></div>
        <div class="ub-row"><span class="k">Reference</span><span class="v"><?= $e($r->reference) ?></span></div>
        <div class="ub-row"><span class="k">Amount</span><span class="v"><?= $r->amountFormatted() ?></span></div>
        <div class="ub-row"><span class="k">Fee</span><span class="v"><?= $r->feeFormatted() ?></span></div>
    </div>
    <div class="ub-card">
        <h4>Account details</h4>
        <div class="ub-row"><span class="k">Sender name</span><span class="v"><?= $e($r->senderName) ?></span></div>
        <div class="ub-row"><span class="k">Sender account number</span><span class="v"><?= $e('*********' . substr($r->senderAccount, -5)) ?></span></div>
        <div class="ub-row"><span class="k">Receiver name</span><span class="v"><?= $e($r->name) ?></span></div>
        <div class="ub-row"><span class="k">Receiver account number</span><span class="v"><?= $e($r->recipientAccount) ?></span></div>
        <div class="ub-row"><span class="k">Receiver bank name</span><span class="v"><?= $e($r->recipientBank) ?></span></div>
        <div class="ub-row"><span class="k">Narration</span><span class="v"><?= $e($r->description !== '' ? $r->description : '-') ?></span></div>
    </div>
    <?php elseif ($r->bankId === 'gtrust'): ?>
    <div class="gt-title">Transaction Receipt</div>
    <div class="gt-body">
        <p><span>Transaction Date:</span> <?= date('d M Y, H:i \G\M\T+1', $ts) ?></p>
        <p><span>Reference Number:</span> <?= $e($r->reference) ?></p>
        <p><span>Sender:</span> <?= $e(strtoupper($r->senderName)) ?></p>
        <p><span>Transaction Amount:</span> <?= number_format($r->amount, $r->amount == floor($r->amount) ? 0 : 2) ?>NGN</p>
        <p><span>Amount In Words:</span> <?= $e($words(floor($r->amount))) ?> Naira Only.</p>
        <p><span>Transaction Type:</span> <?= $isDeposit ? 'GTB-Deposit' : 'GTB-ThirdParty Transfer' ?></p>
        <p><span>Receiver:</span> <?= $e(strtoupper($r->name)) ?></p>
        <p><span>Account Number:</span> <?= $e($r->recipientAccount) ?></p>
        <p><span>Receiving Bank:</span> <?= $e(strtoupper($r->recipientBank)) ?></p>
        <p><span>Fee:</span> <?= $r->feeFormatted() ?></p>
        <p><span>Remarks:</span> <?= $r->description !== '' ? $e($r->description) . ' ' : '' ?>TRANSFER BETWEEN CUSTOMERS Via USSD GTBank Transfer <?= $e($r->sessionId) ?> from <?= $e(strtoupper($r->senderName)) ?> to <?= $e(strtoupper($r->name)) ?></p>
    </div>
    <?php elseif ($r->bankId === 'nombra'): ?>
    <h3 class="nb-title">PAYMENT DETAILS</h3>
    <div class="nb-card">
        <div class="nb-row"><span class="k">Status</span><span class="v nb-ok">Successful</span></div>
        <div class="nb-row"><span class="k">Type</span><span class="v"><?= $isDeposit ? 'Deposit' : 'Transfer' ?></span></div>
        <div class="nb-row"><span class="k"><?= $isDeposit ? 'Depositor' : 'Beneficiary' ?></span><span class="v"><?= $e($r->name) ?><small><?= $e($r->recipientAccount) ?> | <?= $e($r->recipientBank) ?></small></span></div>
        <div class="nb-row"><span class="k">Sender</span><span class="v"><?= $e($r->senderName) ?><small><?= $e($r->senderAccount) ?></small></span></div>
        <div class="nb-row"><span class="k">Fee</span><span class="v"><?= $r->feeFormatted() ?></span></div>
        <?php if ($r->description !== ''): ?>
        <div class="nb-row"><span class="k">Description</span><span class="v"><?= $e($r->description) ?></span></div>
        <?php endif; ?>
        <div class="nb-row"><span class="k">Date</span><span class="v"><?= date('m/d/Y, h:iA', $ts) ?></span></div>
        <div class="nb-row"><span class="k">Reference</span><span class="v nb-ref"><?= $e($r->reference) ?></span></div>
    </div>
    <?php elseif ($r->bankId === 'carbonite'): ?>
    <div class="cb-rows">
        <div class="cb-row"><span class="k">Amount</span><span class="v"><?= $r->amountFormatted() ?></span></div>
        <div class="cb-row"><span class="k">Date &amp; time</span><span class="v"><?= date('M d, Y, h:i A', $ts) ?></span></div>
        <div class="cb-row"><span class="k"><?= $isDeposit ? 'Depositor' : 'Beneficiary' ?></span><span class="v"><?= $e(strtoupper($r->name)) ?><small><?= $e($r->recipientAccount) ?> | <?= $e($r->recipientBank) ?></small></span></div>
        <div class="cb-row"><span class="k">Sender</span><span class="v"><?= $e($r->senderName) ?></span></div>
        <div class="cb-row"><span class="k">Transaction fee</span><span class="v"><?= $r->feeFormatted() ?></span></div>
        <?php if ($r->description !== ''): ?>
        <div class="cb-row"><span class="k">Description</span><span class="v"><?= $e($r->description) ?></span></div>
        <?php endif; ?>
        <div class="cb-row"><span class="k">Transaction ID</span><span class="v"><?= $e($r->reference) ?></span></div>
        <div class="cb-row"><span class="k">Payment method</span><span class="v cb-pay"><span>Carbon<br>Account</span><img src="assets/img/carbon-icon.png" alt="" height="26"></span></div>
    </div>
    <?php elseif ($r->bankId === 'sparkly'): ?>
    <div class="s-bar">Items</div>
    <div class="s-item">
        <span class="s-thumb" aria-hidden="true"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="#868e96" stroke-width="1.6"><rect x="5" y="2" width="14" height="20" rx="2.5"/><path d="M10 18h4"/></svg></span>
        <span class="s-name"><b><?= $e($r->description !== '' ? $r->description : 'Item') ?></b><small><?= $r->amountFormatted() ?></small></span>
        <span class="s-price"><small>1 Unit</small><b><?= $r->amountFormatted() ?></b></span>
    </div>
    <div class="s-bar">Fees &amp; Deductions</div>
    <?php elseif ($r->bankId === 'moniepoint'): ?>
    <div class="mp-panel">
        <div class="mp-item"><span class="k">Transaction Type</span><span class="mp-chip"><?= $isDeposit ? 'Deposit' : 'Transfer' ?></span></div>
        <div class="mp-item"><span class="k">Sender Name</span><span class="v"><?= $e($r->senderName) ?></span></div>
        <div class="mp-item"><span class="k">Source Institution</span><span class="v"><?= $e($r->bankName) ?></span></div>
        <div class="mp-item"><span class="k"><?= $isDeposit ? 'Depositor' : 'Beneficiary' ?></span><span class="v"><?= $e(strtoupper($r->name)) ?></span></div>
        <div class="mp-item"><span class="k"><?= $isDeposit ? 'Depositor Institution' : 'Beneficiary Institution' ?></span><span class="v"><?= $e($r->recipientBank) ?></span></div>
        <div class="mp-item"><span class="k">Fee</span><span class="v"><?= $r->feeFormatted() ?></span></div>
        <?php if ($r->description !== ''): ?>
        <div class="mp-item"><span class="k">Description</span><span class="v"><?= $e($r->description) ?></span></div>
        <?php endif; ?>
        <div class="mp-item"><span class="k">Transaction Date</span><span class="v"><?= date('l, F jS | g:i A', $ts) ?></span></div>
        <div class="mp-item"><span class="k">Transaction Reference</span><span class="v"><?= $e($r->reference) ?></span></div>
        <div class="mp-item"><span class="k">Session ID</span><span class="v"><?= $e($r->sessionId) ?></span></div>
    </div>
    <?php elseif ($r->bankId === 'palmwave'): ?>
    <div class="pp-body">
        <div class="pp-party">
            <span class="k"><?= $isDeposit ? 'Depositor:' : 'Recipient:' ?></span>
            <span class="v"><b><?= $e(strtoupper($r->name)) ?></b><small><?= $e($r->recipientBank) ?> | <?= $e($r->recipientAccount) ?></small></span>
        </div>
        <div class="pp-party">
            <span class="k">Sender:</span>
            <span class="v"><b><?= $e(strtoupper($r->senderName)) ?></b><small><?= $e($r->bankName) ?> | <?= $e(substr($r->senderAccount, 0, 3) . ' *** ' . substr($r->senderAccount, -4)) ?></small></span>
        </div>
        <div class="pp-info">
            <h3>Transaction Info:</h3>
            <div class="pp-row"><span class="k">Transaction Type</span><span class="v"><?= $isDeposit ? 'Deposit' : 'Money Transfer - Bank account' ?></span></div>
            <div class="pp-row"><span class="k">Fees</span><span class="v"><?= $r->feeFormatted() ?></span></div>
            <?php if ($r->description !== ''): ?>
            <div class="pp-row"><span class="k">Description</span><span class="v"><?= $e($r->description) ?></span></div>
            <?php endif; ?>
            <div class="pp-row"><span class="k">Transaction ID</span><span class="v"><?= $e($r->reference) ?></span></div>
            <div class="pp-row"><span class="k">Session ID</span><span class="v"><?= $e($r->sessionId) ?></span></div>
        </div>
    </div>
    <?php else: ?>
    <?php if (!in_array($r->bankId, ['moniepoint', 'sparkly', 'carbonite', 'nombra', 'gtrust', 'unity', 'zenor', 'accent', 'firstline', 'ecobank'], true)): ?>
    <div class="d-amount">
        <small>Transaction Amount</small>
        <strong><?= $r->amountFormatted() ?></strong>
    </div>
    <?php endif; ?>

    <div class="d-row">
        <span class="k"><?= $isDeposit ? 'Depositor Details' : 'Beneficiary Details' ?></span>
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
    <div class="eco-end">
        <div class="eco-ben"><b>BENEFICIARIES:</b> <i>all</i></div>
        <div class="eco-tag">
            <span class="pb">PERSONAL<br>BANKING</span>
            <span class="bw">A BETTER WAY<br>A BETTER AFRICA</span>
        </div>
    </div>
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
    <div class="z-foot">
        <p>For any other assistance, kindly contact us on<br>zenithdirect@zenithbank.com and 234-1-2787000</p>
        <img src="assets/img/zenith-logo-white.png" alt="Zenith Bank" height="30">
    </div>
    <?php elseif ($r->bankId === 'unity'): ?>
    <?php elseif ($r->bankId === 'gtrust'): ?>
    <div class="g-foot">
        <h4>DISCLAIMER</h4>
        <p>Your transfer has been successful and the beneficiary's account will be credited. However, this does not serve as confirmation of credit into the beneficiary's account. Due to the nature of the internet, transactions may be subject to interruption, transmission blackout, delayed transmission and incorrect data transmission. The Bank is not liable for malfunctions in communications facilities not within its control that may affect the accuracy or timeliness of messages and transactions you send. All transactions are subject to verification and our normal fraud checks.</p>
        <b>Generated from GTWorld</b>
    </div>
    <?php elseif ($r->bankId === 'nombra'): ?>
    <div class="n-foot">
        <time><?= date('m/d/Y , h:i A', $ts) ?></time>
        <p><b>IMPORTANT:</b> If you have questions or issues with this payment, contact Nomba at <a href="mailto:support@nomba.com">support@nomba.com</a></p>
    </div>
    <?php elseif ($r->bankId === 'carbonite'): ?>
    <div class="c-foot">
        <h4>Disclaimer:</h4>
        <p>Carbon has successfully processed this transaction but the completion of transfers are subject to transmission errors, network interruptions, glitches and other factors that are beyond Carbon's control and for which Carbon will not be liable. If any issues are experienced with your transaction, please generate a receipt from the list of recent transactions on your home page and contact customer support.</p>
        <p class="c-legal">&copy;<?= date('Y') ?> Carbon is a financial service provided by Carbon Microfinance Bank (RC 1044655), licensed and regulated by the Central Bank of Nigeria (CBN).</p>
        <p class="c-addr">Registered address: <b>Plot 642C Akin Adesola St. Lagos 101233 Nigeria</b></p>
    </div>
    <?php elseif ($r->bankId === 'sparkly'): ?>
    <div class="s-foot">
        <div><span>Shipping/Transport Fee</span><span>+ <?= $r->feeFormatted() ?></span></div>
        <div><span>Discount</span><span>- ₦0.00</span></div>
    </div>
    <?php elseif ($r->bankId === 'moniepoint'): ?>
    </div>
    <img class="m-footer" src="assets/img/moniepoint-footer.png" alt="">
    <?php elseif ($r->bankId === 'palmwave'): ?>
    <img class="d-footer-img" src="assets/img/palmpay-footer.png" alt="Enjoy seamless and unlimited free transfers to all banks. Enjoy all at PalmPay!">
    <?php elseif ($r->bankId === 'opaylite'): ?>
    <img class="d-footer-img" src="assets/img/opay-footer.png" alt="Enjoy a better life with OPay">
    <?php elseif ($r->bankId === 'kudos'): ?>
    <img class="d-footer-img" src="assets/img/kuda-footer.png" alt="Not on Kuda? Tap here to download the money app for Africans">
    <?php else: ?>
    <div class="d-promo">
        <?php include __DIR__ . '/partials/logo.php'; ?>
        <p>Enjoying <?= $e($r->bankName) ?>? Tap here to get the app.</p>
    </div>
    <p class="d-legal">© <?= date('Y') ?> <?= $e($r->bankName) ?> (sample brand). This is a design-practice sample, not a real transaction. Ref: <?= $e($r->reference) ?>.</p>
    <?php endif; ?>

    <?php include __DIR__ . '/partials/watermark.php'; ?>
</div>
