<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 御宅男 <530765310@qq.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 全局路由
// +----------------------------------------------------------------------
//Route::get('/', '\app\index/Index@index');
Route::post('ajax_search_ws', 'app\index\controller\Search@ajaxSearchWs');
Route::post('search_of', 'app\index\controller\Search@ajaxSearchOf');
//土地
//Route::get('land_list', 'app\index\controller\Search@landList');