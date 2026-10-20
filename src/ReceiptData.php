<?php
require_once __DIR__ . '/NameGenerator.php';

final class ReceiptData
{
    public $bankId;
    public $bankName;
    public $color;
    public $type;
    public $name;
    public $amount;
    public $date;
    public $reference;
    public $senderName;
    public $senderAccount;
    public $recipientAccount;
    public $recipientBank;
    public $fee;
    public $description;
    public $sessionId;

    public function __construct(array $f)
    {
        foreach ($f as $k => $v) {
            $this->$k = $v;
        }
    }

    private static function digits(int $n): string
    {
        $s = '';
        for ($i = 0; $i < $n; $i++) {
            $s .= random_int(0, 9);
        }
        return $s;
    }

    public static function fromRequest(array $in, array $banks): self
    {
        $bankId = isset($banks[$in['bank'] ?? '']) ? $in['bank'] : array_key_first($banks);
        $type   = ($in['type'] ?? '') === 'Deposit' ? 'Deposit' : 'Transfer';
        $name   = trim((string)($in['name'] ?? ''));
        if ($name === '') {
            $name = NameGenerator::generate();
        }
        $sender = trim((string)($in['sender'] ?? ''));
        if ($sender === '') {
            $sender = NameGenerator::generate();
        }
        $desc = trim((string)($in['description'] ?? ''));
        $account = preg_replace('/\D/', '', (string)($in['account'] ?? ''));
        if (strlen($account) !== 10) {
            $account = self::digits(10);
        }
        $recipientBanks = require __DIR__ . '/recipientBanks.php';
        $recipientBank = (string)($in['recipient_bank'] ?? '');
        if (!in_array($recipientBank, $recipientBanks, true)) {
            $recipientBank = $recipientBanks[array_rand($recipientBanks)];
        }
        $rawDate = trim((string)($in['date'] ?? ''));
        $ts = $rawDate !== '' ? strtotime($rawDate) : false;
        if ($ts === false) {
            $ts = time();
        }

        return new self([
            'bankId'           => $bankId,
            'bankName'         => $banks[$bankId]['name'],
            'color'            => $banks[$bankId]['color'],
            'type'             => $type,
            'name'             => mb_substr($name, 0, 60),
            'amount'           => max(0, (float)($in['amount'] ?? 0)),
            'date'             => date('Y-m-d H:i:s', $ts),
            'reference'        => 'DEMO-' . random_int(10000000, 99999999),
            'senderName'       => mb_substr($sender, 0, 60),
            'senderAccount'    => self::digits(10),
            'recipientAccount' => $account,
            'recipientBank'    => $recipientBank,
            'fee'              => max(0, (float)($in['fee'] ?? 10)),
            'description'      => mb_substr($desc, 0, 80),
            'sessionId'        => 'DEMO' . self::digits(26),
        ]);
    }

    public function amountFormatted(): string
    {
        return '₦' . number_format($this->amount, 2);
    }

    public function feeFormatted(): string
    {
        return '₦' . number_format($this->fee, 2);
    }
}
