<?php

use think\facade\Route;


/**
 * 全局
 */
//Route::get('/', '\app\index/Index@index');
Route::post('ajax_search_ws', 'app\index\controller\Search@ajaxSearchWs');
Route::post('search_of', 'app\index\controller\Search@ajaxSearchOf');
Route::post('search_land', 'app\index\controller\Search@ajaxSearchLand');
Route::post('search_shop', 'app\index\controller\Search@ajaxSearchShop');
//土地
//Route::get('land_list', 'app\index\controller\Search@landList');


//移动端
//Route::domain('m', 'mobile');
Route::domain('m', function () {
    Route::get('/', 'mobile/Index/index');
    Route::get('ajax_search_ws', 'app\index\controller\Search@ajaxSearchWs');
    Route::get('city_shift', 'mobile/Index/city_shift')->name('city_shift');
    Route::get('workshop/:category', 'mobile/Search/workshop')->name('ws');
    Route::get('land', 'mobile/Search/landList')->name('land');
    Route::get('of_build', 'mobile/Search/officebuilding')->name('of_build');
    Route::get('shop', 'mobile/Search/shopList')->name('shop');


});