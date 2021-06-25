<?php

namespace Packages\ObjectOriented\Domain\Item;

use InvalidArgumentException;

/**
 * 商品カテゴリ
 */
final class Category
{
    private int $id;
    private string $name;

    public function __construct(int $id, string $name)
    {
        if ($id < 1) {
            throw new InvalidArgumentException('invalid category_id');
        }
        if ($name === '' || mb_strlen($name) > 10) {
            throw new InvalidArgumentException('invalid category_name');
        }
        $this->id = $id;
        $this->name = $name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }
}
