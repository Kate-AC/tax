<?php

/**
 * 消費税に関するクラス
 */
class Tax
{
    const ACCURACY = 3;

    const TAX_1_00 = 1.00;
    const TAX_1_03 = 1.03;
    const TAX_1_05 = 1.05;
    const TAX_1_08 = 1.08;
    const TAX_1_10 = 1.10;

    /**
     * @var \Datetime|null
     */
    private static $currentDateTime = null;

    /**
     * 現在の消費税を返す
     *
     * @param string|\DateTime $dateTime "Y-m-d" or DateTime class
     * @return float
     */
    public static function now($dateTime = null)
    {
        self::validate($dateTime);
        if (is_null($dateTime)) {
            $dateTime = is_null(self::$currentDateTime) ? new \DateTime() : self::$currentDateTime;
        } elseif (is_string($dateTime)) {
            $dateTime = new \DateTime($dateTime);
        }
        return self::getTaxByDateTime($dateTime);
    }

    /**
     * 税を掛けた値を返す
     *
     * @param numeric $value 数値
     * @return float
     * @throws \RuntimeException
     */
    public static function calc($value)
    {
        if (!is_numeric($value)) {
            throw new \RuntimeException('Argument type is not numeric.');
        }
        //return bcmul(self::now(), $value, self::ACCURACY);
        return self::now() * $value;
    }

    /**
     * 基準となる日付をセットする
     *
     * @param string|\DateTime $dateTime "Y-m-d" or DateTime class
     */
    public static function setCurrentTime($dateTime)
    {
        self::validate($dateTime);
        self::$currentDateTime = is_string($dateTime) ? new \DateTime($dateTime) : $dateTime;
    }

    /**
     * 日付をリセットする
     */
    public static function resetCurrentTime()
    {
        self::$currentDateTime = null;
    }

    /**
     * 引数のバリデーションを行う
     *
     * @param null|string|\DateTime $value
     * @throws \RuntimeException
     */
    private static function validate($value)
    {
        if (is_null($value)) return;

        if ($value instanceof \DateTime) return;

        if (!preg_match('/^(?P<year>[0-9]{4})\-(?P<month>[0-9]{2})\-(?P<day>[0-9]{2})$/', $value)
            || $value === date("Y-m-d H:i:s", strtotime($value))
        ) {
            throw new \RuntimeException('Incorrect an argument type.');
        }
    }

    /**
     * 日付からその時点の消費税を返す
     *
     * @param \DateTime $dateTime
     * @return float
     */
    private static function getTaxByDateTime(\DateTime $dateTime)
    {
        if (new \DateTime('1989-04-01') > $dateTime) {
            return self::TAX_1_00;

        } elseif (new \DateTime('1989-04-01') <= $dateTime && new \DateTime('1997-04-01') > $dateTime) {
            return self::TAX_1_03;

        } elseif (new \DateTime('1997-04-01') <= $dateTime && new \DateTime('2014-04-01') > $dateTime) {
            return self::TAX_1_05;

        } elseif (new \DateTime('2014-04-01') <= $dateTime && new \DateTime('2019-10-01') > $dateTime) {
            return self::TAX_1_08;
        }

        return self::TAX_1_10;
    }
}

