<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application repositories.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind(
            \App\Repositories\Auth\UserRepositoryContract::class,
            \App\Repositories\Auth\EloquentUserRepository::class
        );

        $this->app->bind(
            \App\Repositories\Store\Customer\CustomerRepositoryContract::class,
            \App\Repositories\Store\Customer\EloquentCustomerRepository::class
        );

        $this->app->bind(
            \App\Repositories\Store\Discount\DiscountRepositoryContract::class,
            \App\Repositories\Store\Discount\EloquentDiscountRepository::class
        );

        $this->app->bind(
            \App\Repositories\Store\Ingredient\IngredientRepositoryContract::class,
            \App\Repositories\Store\Ingredient\EloquentIngredientRepository::class
        );

        $this->app->bind(
            \App\Repositories\Store\Product\ProductRepositoryContract::class,
            \App\Repositories\Store\Product\EloquentProductRepository::class
        );

        $this->app->bind(
            \App\Repositories\Store\ProductInstance\ProductInstanceRepositoryContract::class,
            \App\Repositories\Store\ProductInstance\EloquentProductInstanceRepository::class
        );

        $this->app->bind(
            \App\Repositories\Store\Recipe\RecipeRepositoryContract::class,
            \App\Repositories\Store\Recipe\EloquentRecipeRepository::class
        );

        $this->app->bind(
            \App\Repositories\Store\LiquidProduct\LiquidProductRepositoryContract::class,
            \App\Repositories\Store\LiquidProduct\EloquentLiquidProductRepository::class
        );

        $this->app->bind(
            \App\Repositories\Store\ShopOrder\ShopOrderRepositoryContract::class,
            \App\Repositories\Store\ShopOrder\EloquentShopOrderRepository::class
        );

        $this->app->bind(
            \App\Repositories\Store\Shipment\ShipmentRepositoryContract::class,
            \App\Repositories\Store\Shipment\EloquentShipmentRepository::class
        );

        $this->app->bind(
            \App\Repositories\Store\Transfer\TransferRepositoryContract::class,
            \App\Repositories\Store\Transfer\EloquentTransferRepository::class
        );

        $this->app->bind(
            \App\Repositories\Store\Shift\ShiftRepositoryContract::class,
            \App\Repositories\Store\Shift\EloquentShiftRepository::class
        );

        $this->app->bind(
            \App\Repositories\Announcement\AnnouncementRepositoryContract::class,
            \App\Repositories\Announcement\EloquentAnnouncementRepository::class
        );
    }
}
