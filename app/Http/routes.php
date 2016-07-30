<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'PagesController@home');
Route::get('/about', 'PagesController@about');
Route::get('/contact', 'TicketsController@create');
Route::post('/contact', 'TicketsController@store');
Route::get('/tickets', 'TicketsController@index');
Route::get('/ticket/{slug?}', 'TicketsController@show');
Route::get('/ticket/{slug?}/edit', 'TicketsController@edit');
Route::post('/ticket/{slug?}/edit', 'TicketsController@update');
Route::post('/ticket/{slug?}/delete', 'TicketsController@destroy');
Route::post('/comment', 'CommentsController@newComment');

Route::get('users/register', 'Auth\AuthController@getRegister');
Route::post('users/register', 'Auth\AuthController@postRegister');
Route::get('users/logout', 'Auth\AuthController@getLogout');
Route::get('users/login', 'Auth\AuthController@getLogin');
Route::post('users/login', 'Auth\AuthController@postLogin');

Route::get('/blog', 'BlogController@index');
Route::get('/blog/{slug?}', 'BlogController@show');

Route::group(array('prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'manager'), function() {
    Route::get('/', 'PagesController@home');

    Route::get('roles', 'RolesController@index');
    Route::get('roles/create', 'RolesController@create');
    Route::post('roles/create', 'RolesController@store');

    Route::get('users', ['as' => 'admin.user.index', 'uses' => 'UsersController@index']);
    Route::get('users/{id?}/edit', 'UsersController@edit');
    Route::post('users/{id?}/edit', 'UsersController@update');

    Route::get('posts', 'PostsController@index');
    Route::get('posts/create', 'PostsController@create');
    Route::post('posts/create', 'PostsController@store');
    Route::get('posts/{id?}/edit', 'PostsController@edit');
    Route::post('posts/{id?}/edit', 'PostsController@update');

    Route::get('categories', 'CategoriesController@index');
    Route::get('categories/create', 'CategoriesController@create');
    Route::post('categories/create', 'CategoriesController@store');

    Route::get('store/products', 'Store\ProductController@index');
    Route::get('store/products/{id?}/show', 'Store\ProductController@show');
    Route::get('store/products/create', 'Store\ProductController@create');
    Route::post('store/products/create', 'Store\ProductController@store');
    Route::get('store/products/{id?}/edit', 'Store\ProductController@edit');
    Route::post('store/products/{id?}/edit', 'Store\ProductController@update');
    Route::post('store/products/{id?}/editable', 'Store\ProductController@editable');
    Route::post('store/products/{id?}/instance', 'Store\ProductController@addInstance');
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

    Route::get('store/customers', 'Store\CustomerController@index');
    Route::post('store/customers/create', 'Store\CustomerController@store');
    Route::get('store/customers/{id?}/show', 'Store\CustomerController@show');
    Route::post('store/customers/{id?}/edit', 'Store\CustomerController@update');

    Route::get('store/discounts', 'Store\DiscountController@index');
    Route::post('store/discounts/create', 'Store\DiscountController@store');
    Route::get('store/discounts/{id?}/edit', 'Store\DiscountController@edit');
    Route::post('store/discounts/{id?}/edit', 'Store\DiscountController@update');

});