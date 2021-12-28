<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::group(['prefix' => 'laravel-filemanager'], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', 'DashboardController@show');
    Route::get('admin', 'DashboardController@show');
    // User manager
    Route::get('admin/user/list', 'AdminUserController@list');
    Route::get('admin/user/add', 'AdminUserController@add');
    Route::post('admin/user/store', 'AdminUserController@store');
    Route::get('admin/user/delete/{id?}', 'AdminUserController@delete')->name('delete.user');
    Route::get('admin/user/forceDelete/{id?}', 'AdminUserController@forceDelete')->name('forceDelete.user');
    Route::post('admin/user/action', 'AdminUserController@action');
    Route::get('admin/user/edit/{id?}', 'AdminUserController@edit')->name('edit.user');
    Route::post('admin/user/update', 'AdminUserController@update');
    // Orders
    Route::get('admin/order', 'OrderController@list');
    Route::get('admin/order/list', 'OrderController@list');
    // Products
    Route::get('admin/product', 'ProductController@list');
    Route::get('admin/product/list', 'ProductController@list');
    Route::get('admin/product/add', 'ProductController@add');
    Route::get('admin/product/cat/list', 'ProductController@cat_list');
    // Posts
    Route::get('admin/post', 'PostController@list');
    Route::get('admin/post/list', 'PostController@list');
    Route::get('admin/post/add', 'PostController@add');
    Route::post('admin/post/store', 'PostController@store');
    Route::get('admin/post/cat', 'PostController@cat_list');
    Route::get('admin/post/delete/{id?}', 'PostController@delete')->name('delete.post');
    Route::get('admin/post/forceDelete/{id?}', 'PostController@forceDelete')->name('forceDelete.post');
    Route::post('admin/post/action', 'PostController@action');
    Route::get('admin/post/edit/{id?}', 'PostController@edit')->name('edit.post');
    Route::post('admin/post/update', 'PostController@update');
    // -- Posts cat
    Route::get('admin/post/cat/list', 'PostController@cat_list');
    Route::post('admin/post/cat/list/store', 'PostController@store_cat_list');
    Route::get('admin/post/cat/list/delete/{id?}', 'PostController@delete_cat_list')->name('delete.cat.post');
    // Pages
    Route::get('admin/page', 'PageController@list');
    Route::get('admin/page/list', 'PageController@list');
    Route::get('admin/page/add', 'PageController@add');
    Route::post('admin/page/store', 'PageController@store');
    Route::get('admin/page/delete/{id?}', 'PageController@delete')->name('delete.page');
    Route::get('admin/page/forceDelete/{id?}', 'PageController@forceDelete')->name('forceDelete.page');
    Route::post('admin/page/action', 'PageController@action');
    Route::get('admin/page/edit/{id?}', 'PageController@edit')->name('edit.page');
    Route::post('admin/page/update', 'PageController@update');
    // -- Pages cat

});
