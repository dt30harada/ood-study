<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Packages\ObjectOriented\Domain\Customer\CustomerRepositoryInterface;
use Packages\ObjectOriented\Domain\Item\ItemRepositoryInterface;
use Packages\ObjectOriented\Domain\Order\OrderPaymentServiceInterface;
use Packages\ObjectOriented\Domain\Order\OrderRepositoryInterface;
use Packages\ObjectOriented\Infrastructure\Customer\EloquentCustomerRepository;
use Packages\ObjectOriented\Infrastructure\Item\EloquentItemRepository;
use Packages\ObjectOriented\Infrastructure\Order\EloquentOrderRepository;
use Packages\ObjectOriented\Infrastructure\Order\MockOrderPaymentService;

class MyDiServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CustomerRepositoryInterface::class, EloquentCustomerRepository::class);
        $this->app->bind(ItemRepositoryInterface::class, EloquentItemRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, EloquentOrderRepository::class);

        $this->app->bind(OrderPaymentServiceInterface::class, MockOrderPaymentService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
