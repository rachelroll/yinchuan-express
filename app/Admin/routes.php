<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('index');
    $router->resource('users', UserController::class);
    $router->resource('couriers', CourierController::class);
    $router->resource('votes', VoteController::class);
    $router->resource('banners', BannerController::class);
    $router->resource('companies', CompanyController::class);
    $router->resource('advise', AdviseController::class);

    $router->get('/complaint/change', 'ComplaintController@change')->name('complaint.change');

    $router->resource('complaints', ComplaintController::class);

    // 与微信交互
    $router->any('/wechat', 'WeChatController@serve');

});
