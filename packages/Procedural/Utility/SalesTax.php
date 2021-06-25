<?php

namespace Packages\Procedural\Utility;

/**
 * 消費税計算ユーティリティ
 */
final class SalesTax
{
    /**
     * 指定価格の消費税を算出する (端数切り捨て)
     *
     * @param int $price
     * @param float $rate ex: 0.1
     * @return int
     */
    public static function calc(int $price, float $rate): int
    {
        return (int) floor(bcmul($price, $rate, 0));
    }

    /**
     * 税込価格から税抜価格を算出する（端数切り上げ）
     *
     * @param int $price
     * @param float $rate ex: 0.1
     * @param int $scale bcdiv()の第3引数
     * @return int
     */
    public static function calcPriceWithoutTax(int $price, float $rate, int $scale): int
    {
        $result = (int) ceil(bcdiv($price, bcadd(1, $rate, 2), $scale));

        return $result;
    }
}
