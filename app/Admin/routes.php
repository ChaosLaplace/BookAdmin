<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Dcat\Admin\Admin;

Admin::routes();

Route::group([
    'prefix'     => config('admin.route.prefix'),
    'namespace'  => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    // 用戶管理
    $router->get('/user/list', 'BookUserController@index');
    // 繪本管理
    $router->get('/book/list', 'BookController@index');
    $router->put('/book/list/{id?}', 'BookController@saveBook');
    // 財務管理
    $router->get('/finance/pay', 'UserPayRecordController@index');
    $router->get('/finance/buy', 'BookOrderController@index');
    $router->get('/finance/income', 'BookIncomeController@index');
    $router->get('/finance/withdraw', 'WithdrawOrderController@index');
});
