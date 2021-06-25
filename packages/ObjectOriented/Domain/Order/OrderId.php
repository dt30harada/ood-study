<?php

namespace Packages\ObjectOriented\Domain\Order;

use InvalidArgumentException;

/**
 * 注文ID 値オブジェクト
 *
 * @note value_object:
 * - ドメイン(対象領域)の概念モデルをオブジェクトに反映したもの
 * - 識別子ではなく属性によりその同一性が判断される (スカラー型と同じ)
 * - 不変オブジェクト
 * -- setter無し
 * -- コンストラクタで初期化したらその後は変更しない
 * -- 値が変わる場合は変更ではなく、その値をもつ新規インスタンスを生成する
 * - メリット
 * -- この値に対する操作(ドメインロジック・ルール)の一元化
 * -- コード自体がドメインの仕様を反映する (自己文書化)
 * -- 型安全なコーディングを促す
 * -- 不変 = 副作用がない = 安全
 * - デメリット
 * -- クラス数が増える
 */
final class OrderId
{
    private int $value;

    /**
     * @param int $value 0より大きい一意の整数 採番テーブル`order_seq`を使用
     */
    public function __construct(int $value)
    {
        if ($value < 1) {
            throw new InvalidArgumentException('order_id must be int and greater than 0');
        }
        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
