<?php

namespace Packages\ObjectOriented\Domain\Customer;

/**
 * 顧客 エンティティ
 */
final class Customer
{
    private CustomerId $id;
    private string $name;
    private Rank $rank;

    public function __construct(CustomerId $id, string $name, Rank $rank)
    {
        $this->id = $id;
        $this->name = $name;
        $this->rank = $rank;
    }

    public function getId(): CustomerId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRank(): Rank
    {
        return $this->rank;
    }
}
