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
    $router->put('/finance/withdraw/{id?}', 'WithdrawOrderController@saveWithdrawOrder');

    // 檔案列表
    $router->get('/file/list', 'FileComparisonController@index');
    // 多文件上傳頁面
    $router->get('/file/list/create', 'FileComparisonController@create');
    // 檔案上傳並把內容存入資料庫
    $router->any('/file/upload', 'FileComparisonController@upload');
    
    // 計畫內容分析(於資料庫比對歷年計畫內容, 找相似) 並生成報告提供下載
    $router->get('/file/comparison', 'FileComparisonController@comparison');
    // 設定偏好
    $router->get('/file/config', 'FileComparisonController@config');
    // 查看系統性能
    $router->get('/file/performance', 'FileComparisonController@performance');
    // 使用者操作日誌
    $router->get('/file/adminLog', 'FileComparisonController@adminLog');
    // 維護系統
    $router->get('/file/maintenance', 'FileComparisonController@maintenance');
});
