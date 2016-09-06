<?php

/**
 * Public routes (no authentication required)
 */
//Route::get('users/register', 'Auth\AuthController@getRegister');
//Route::post('users/register', 'Auth\AuthController@postRegister');
Route::get('users/logout', 'Auth\AuthController@getLogout');
Route::get('users/login', 'Auth\AuthController@getLogin');
Route::post('users/login', 'Auth\AuthController@postLogin');

/**
 * Route group for 'Admin' users only (/admin/*)
 */
Route::group(array('prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'manager'), function() {
    Route::get('/', 'PagesController@home');

    Route::get('roles', 'RolesController@index');
    Route::get('roles/create', 'RolesController@create');
    Route::post('roles/create', 'RolesController@store');

    Route::get('users', ['as' => 'admin.user.index', 'uses' => 'UsersController@index']);
    Route::get('users/{id?}/edit', 'UsersController@edit');
    Route::post('users/{id?}/edit', 'UsersController@update');

    Route::get('store/products', 'Store\ProductController@index');
    Route::get('store/products/{id?}/show', 'Store\ProductController@show');
    Route::get('store/products/create', 'Store\ProductController@create');
    Route::post('store/products/create', 'Store\ProductController@store');
    Route::get('store/products/{id?}/edit', 'Store\ProductController@edit');
    Route::post('store/products/{id?}/edit', 'Store\ProductController@update');
    Route::post('store/products/{id?}/ajax', 'Store\ProductController@ajaxUpdate');
    Route::post('store/products/instance', 'Store\ProductController@addInstance');
    Route::get('store/products/instance/{id?}/edit', 'Store\ProductController@editInstance');
    Route::post('store/products/instance/{id?}/edit', 'Store\ProductController@updateInstance');

    Route::get('store/ingredients', 'Store\IngredientController@index');
    Route::post('store/ingredients/create', 'Store\IngredientController@store');
    Route::get('store/ingredients/{id?}/edit', 'Store\IngredientController@edit');
    Route::post('store/ingredients/{id?}/edit', 'Store\IngredientController@update');

    Route::get('store/recipes', 'Store\RecipeController@index');
    Route::get('store/recipes/{id?}/show', 'Store\RecipeController@show');
    Route::get('store/recipes/create', 'Store\RecipeController@create');
    Route::post('store/recipes/create', 'Store\RecipeController@store');
    Route::post('store/recipes/{id?}/ajax', 'Store\RecipeController@ajaxUpdate');
    Route::post('store/recipes/{id?}/update', 'Store\RecipeController@update');
    Route::get('store/recipes/{id?}/remove/{iid?}', 'Store\RecipeController@remove');
    Route::post('store/recipes/add', 'Store\RecipeController@add');

    Route::get('store/discounts', 'Store\DiscountController@index');
    Route::post('store/discounts/create', 'Store\DiscountController@store');
    Route::get('store/discounts/{id?}/edit', 'Store\DiscountController@edit');
    Route::post('store/discounts/{id?}/edit', 'Store\DiscountController@update');

});

/**
 * Route group for authenticated users who are not 'Admin' (/*)
 */
Route::group(array('namespace' => 'Front', 'middleware' => 'auth'), function() {
    Route::get('/', 'PagesController@home');

    Route::get('customers', 'Store\CustomerController@index');
    Route::post('customers/create', 'Store\CustomerController@store');
    Route::get('customers/{id?}/show', 'Store\CustomerController@show');
    Route::post('customers/{id?}/ajax', 'Store\CustomerController@ajaxUpdate');

    Route::get('orders', 'Store\ShopOrderController@index');
    Route::post('orders', 'Store\ShopOrderController@index');
    Route::get('orders/create', 'Store\ShopOrderController@create');
    Route::post('orders/create', 'Store\ShopOrderController@store');
    Route::get('orders/{id?}/show', 'Store\ShopOrderController@show');
    Route::get('orders/{id?}/delete', 'Store\ShopOrderController@delete');
    Route::post('orders/{id?}/add-product', 'Store\ShopOrderController@addProduct');
    Route::get('orders/{id?}/quantity-update', 'Store\ShopOrderController@quantityUpdate');
    Route::get('orders/{id?}/remove-product/{pid?}', 'Store\ShopOrderController@removeProduct');
    Route::post('orders/{id?}/add-liquid', 'Store\ShopOrderController@addLiquid');
    Route::get('orders/{id?}/remove-liquid/{lid?}', 'Store\ShopOrderController@removeLiquid');
    Route::post('orders/{id?}/add-discount', 'Store\ShopOrderController@addDiscount');
    Route::get('orders/{id?}/remove-discount/{did?}', 'Store\ShopOrderController@removeDiscount');
    Route::post('orders/{id?}/customer', 'Store\ShopOrderController@customer');
    Route::post('orders/{id?}/payment', 'Store\ShopOrderController@payment');
    Route::get('orders/{id?}/receipt', 'Store\ShopOrderController@receipt');

    Route::get('pdf/order/{id?}/receipt', 'PdfController@orderReceipt');
});