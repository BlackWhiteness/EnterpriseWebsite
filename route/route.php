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
Route::post('search_park', 'app\index\controller\Park@ajaxSearchPark');
Route::post('search_home', 'app\index\controller\PrivateHome@ajaxSearchHome');
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

    Route::get('park', 'mobile/Park/index')->name('park');
    Route::get('park_detail', 'mobile/Park/detail');

    Route::get('home_list', 'mobile/PrivateHome/index')->name('home_list');
    Route::get('home_detail', 'mobile/PrivateHome/detail');

    //详情
    Route::get('work_shop/detail', 'mobile/Search/workshopdetail');
    Route::get('of_build/detail', 'mobile/Search/offbuilddetail');
    Route::get('land/detail', 'mobile/Search/landdetail');
    Route::get('shop/detail', 'mobile/Search/shopdetail');

    // 关于我们
    Route::get('about', 'mobile/About/index');
    Route::get('concat', 'mobile/About/shopdetail');

    // 异步获取

    Route::get('ajax_search_ws/:category', 'mobile/Search/ajaxSearchWs');
    Route::get('search_of', 'mobile/Search/ajaxSearchOf');
    Route::get('search_land', 'mobile/Search/ajaxSearchLand');
    Route::get('search_shop', 'mobile/Search/ajaxSearchShop');
    Route::get('search_park', 'mobile/Park/ajaxSearchPark');
    Route::get('search_home', 'mobile/PrivateHome/ajaxSearchHome');

});
