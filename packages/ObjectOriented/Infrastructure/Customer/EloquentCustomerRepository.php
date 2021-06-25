<?php

namespace Packages\ObjectOriented\Infrastructure\Customer;

use App\Models\Customer as EloquentCustomer;
use Packages\ObjectOriented\Domain\Customer\Customer;
use Packages\ObjectOriented\Domain\Customer\CustomerFactory;
use Packages\ObjectOriented\Domain\Customer\CustomerId;
use Packages\ObjectOriented\Domain\Customer\CustomerRepositoryInterface;

/**
 * Eloquentモデルを使用してCustomerRepositoryInterfaceを実装したリポジトリ
 */
final class EloquentCustomerRepository implements CustomerRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function findById(CustomerId $id): Customer
    {
        $eCustomer = EloquentCustomer::find($id->getValue());

        return CustomerFactory::reconstruct(
            $eCustomer->id,
            $eCustomer->name,
            $eCustomer->customer_rank_id
        );
    }
}
