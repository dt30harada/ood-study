<?php

namespace Packages\ObjectOriented\Domain\Customer;

/**
 * 顧客関連モデルのリポジトリ
 */
interface CustomerRepositoryInterface
{
    /**
     * 指定IDの顧客エンティティを取得する
     *
     * @param CustomerId $id
     * @return Customer
     */
    public function findById(CustomerId $id): Customer;
}
