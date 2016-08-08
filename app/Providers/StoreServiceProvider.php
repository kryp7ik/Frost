<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class StoreServiceProvider extends ServiceProvider
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
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
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
    }
}
