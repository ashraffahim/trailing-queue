<?php

namespace app\components;

class QueueManager {
    public const TOKEN_LENGTH = 3;
    public const TOKEN_NUMBER_PAD_STRING = '0';
    public const TOKEN_INITIAL_NUMBER = 100;

    public const STATUS_CREATED = 0;
    public const STATUS_CALLED = 1;
    public const STATUS_RECALLED = 2;
    public const STATUS_IN_PROGRESS = 3;
    public const STATUS_ON_HOLD = 4;
    public const STATUS_ENDED = 5;

    /**
     * @param string $prefix
     * @param int $number
     * @param int $length
     * @param string $pad_string
     * @param int $initialNumber
     */
    public static function createToken(string $prefix, int $number, int $length = self::TOKEN_LENGTH, string $pad_string = self::TOKEN_NUMBER_PAD_STRING, int $initialNumber = self::TOKEN_INITIAL_NUMBER) : string {
        return $prefix . str_pad(($number + $initialNumber), $length, $pad_string);
    }
}

?>