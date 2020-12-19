<?php

declare(strict_types=1);

namespace Samyan;

class BankCard
{
    protected static $cards = [
        // Debit cards
        'visaelectron' => [
            'type' => 'visaelectron',
            'pattern' => '/^4(026|17500|405|508|844|91[37])/',
            'length' => [16],
            'cvcLength' => [3],
            'luhn' => true,
        ],
        'maestro' => [
            'type' => 'maestro',
            'pattern' => '/^(5(018|0[23]|[68])|6(39|7))/',
            'length' => [12, 13, 14, 15, 16, 17, 18, 19],
            'cvcLength' => [3],
            'luhn' => true,
        ],
        'forbrugsforeningen' => [
            'type' => 'forbrugsforeningen',
            'pattern' => '/^600/',
            'length' => [16],
            'cvcLength' => [3],
            'luhn' => true,
        ],
        'dankort' => [
            'type' => 'dankort',
            'pattern' => '/^5019/',
            'length' => [16],
            'cvcLength' => [3],
            'luhn' => true,
        ],
        // Credit cards
        'visa' => [
            'type' => 'visa',
            'pattern' => '/^4/',
            'length' => [13, 16],
            'cvcLength' => [3],
            'luhn' => true,
        ],
        'mastercard' => [
            'type' => 'mastercard',
            'pattern' => '/^(5[0-5]|2[2-7])/',
            'length' => [16],
            'cvcLength' => [3],
            'luhn' => true,
        ],
        'amex' => [
            'type' => 'amex',
            'pattern' => '/^3[47]/',
            'format' => '/(\d{1,4})(\d{1,6})?(\d{1,5})?/',
            'length' => [15],
            'cvcLength' => [3, 4],
            'luhn' => true,
        ],
        'dinersclub' => [
            'type' => 'dinersclub',
            'pattern' => '/^3[0689]/',
            'length' => [14],
            'cvcLength' => [3],
            'luhn' => true,
        ],
        'discover' => [
            'type' => 'discover',
            'pattern' => '/^6([045]|22)/',
            'length' => [16],
            'cvcLength' => [3],
            'luhn' => true,
        ],
        'unionpay' => [
            'type' => 'unionpay',
            'pattern' => '/^(62|81)/',
            'length' => [16, 17, 18, 19],
            'cvcLength' => [3],
            'luhn' => false,
        ],
        'jcb' => [
            'type' => 'jcb',
            'pattern' => '/^35/',
            'length' => [16],
            'cvcLength' => [3],
            'luhn' => true,
        ],
        'elo' => [
            'type' => 'elo',
            'pattern' => '/^((50670[7-8])|(506715)|(50671[7-9])|(50672[0-1])|(50672[4-9])|(50673[0-3])|(506739)|(50674[0-8])|(50675[0-3])|(50677[4-8])|(50900[0-9])|(50901[3-9])|(50902[0-9])|(50903[1-9])|(50904[0-9])|(50905[0-9])|(50906[0-4])|(50906[6-9])|(50907[0-2])|(50907[4-5])|(636368)|(636297)|(504175)|(438935)|(40117[8-9])|(45763[1-2])|(457393)|(431274)|(50907[6-9])|(50908[0-9])|(627780))/',
            'length' => [16],
            'cvcLength' => [3],
            'luhn' => true,
        ],
        'hipercard' => [
            'type' => 'hipercard',
            'pattern' => '/^((606282|637568)[0-9]{10}|38[0-9]{14,17})$$/',
            'length' => [13, 16, 19],
            'cvcLength' => [3],
            'luhn' => true,
        ],
        'mir' => [
            'type' => 'mir',
            'pattern' => '/^220[0-4]/',
            'length' => [16],
            'cvcLength' => [3],
            'luhn' => true,
        ],
        'uatp' => [
            'type' => 'uatp',
            'pattern' => '/^1/',
            'length' => [15],
            'cvcLength' => [3],
            'luhn' => true,
        ],
        'rupay' => [
            'type' => 'rupay',
            'pattern' => '/^(60|6521|6522)/',
            'length' => [16],
            'cvcLength' => [3],
            'luhn' => true,
        ]
    ];

    /**
     * Validate bank card number
     *
     * @param string $number
     * @param string $type
     * @return array
     */
    public static function validateCardNumber(string $number, string $type = null): array
    {
        $ret = [
            'valid' => false,
            'number' => '',
            'type' => '',
        ];

        // Strip non-numeric characters
        $number = preg_replace('/[^0-9]/', '', $number);

        if (empty($type)) {
            $type = self::getCardType($number);
        }

        if (array_key_exists($type, self::$cards) && self::isValidCard($number, $type)) {
            return [
                'valid' => true,
                'number' => $number,
                'type' => $type,
            ];
        }

        return $ret;
    }

    /**
     * Get card type
     *
     * @param string $number
     * @return string
     */
    protected static function getCardType(string $number): string
    {
        foreach (self::$cards as $type => $card) {
            if (preg_match($card['pattern'], $number)) {
                return $type;
            }
        }

        return '';
    }

    /**
     * Check if is valid cvc
     *
     * @param string $cvc
     * @param string $type
     * @return boolean
     */
    public static function isValidCvc(string $cvc, string $type): bool
    {
        return (ctype_digit($cvc) && array_key_exists($type, self::$cards) && self::isValidCvcLength($cvc, $type));
    }

    /**
     * Check if is valid date
     *
     * @param string $year
     * @param string $month
     * @return boolean
     */
    public static function isValidDate(string $year, string $month): bool
    {
        $month = str_pad($month, 2, '0', STR_PAD_LEFT);

        if (!preg_match('/^20\d\d$/', $year)) {
            return false;
        }

        if (!preg_match('/^(0[1-9]|1[0-2])$/', $month)) {
            return false;
        }

        // past date
        if ($year < date('Y') || $year == date('Y') && $month < date('m')) {
            return false;
        }

        return true;
    }

    /**
     * Is valid credit card
     *
     * @param string $number
     * @param string $type
     * @return boolean
     */
    protected static function isValidCard(string $number, string $type): bool
    {
        return (self::isValidPattern($number, $type) && self::isValidLength($number, $type) && self::isValidLuhn($number, $type));
    }

    /**
     * Is valid credit card
     *
     * @param string $number
     * @param string $type
     * @return boolean
     */
    protected static function isValidPattern(string $number, string $type): bool
    {
        return (bool) preg_match(self::$cards[$type]['pattern'], $number);
    }

    /**
     * Is valid length
     *
     * @param string $number
     * @param string $type
     * @return boolean
     */
    protected static function isValidLength(string $number, string $type): bool
    {
        foreach (self::$cards[$type]['length'] as $length) {
            if (strlen($number) == $length) {
                return true;
            }
        }

        return false;
    }

    /**
     * Is valid cvc length
     *
     * @param string $cvc
     * @param string $type
     * @return boolean
     */
    protected static function isValidCvcLength(string $cvc, string $type): bool
    {
        foreach (self::$cards[$type]['cvcLength'] as $length) {
            if (strlen($cvc) == $length) {
                return true;
            }
        }

        return false;
    }

    /**
     * Is valid luhn
     *
     * @param string $number
     * @param string $type
     * @return boolean
     */
    protected static function isValidLuhn(string $number, string $type): bool
    {
        if (!self::$cards[$type]['luhn']) {
            return true;
        } else {
            return self::checkLuhn($number);
        }
    }

    /**
     * Check luhn
     *
     * @param string $number
     * @return boolean
     */
    protected static function checkLuhn(string $number): bool
    {
        $checksum = 0;

        for ($i = (2 - (strlen($number) % 2)); $i <= strlen($number); $i += 2) {
            $checksum += (int) ($number[$i - 1]);
        }

        // Analyze odd digits in even length strings or even digits in odd length strings.
        for ($i = (strlen($number) % 2) + 1; $i < strlen($number); $i += 2) {
            $digit = (int) ($number[$i - 1]) * 2;

            if ($digit < 10) {
                $checksum += $digit;
            } else {
                $checksum += ($digit - 9);
            }
        }

        return ($checksum % 10) == 0;
    }
}
