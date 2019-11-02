<?php
/**
 * Created by PhpStorm.
 * User: hegangbiao
 * Date: 2019/10/16
 * Time: 23:28
 */

namespace app\api\controller;


use app\common\controller\Base;
use think\Db;

class City extends Base
{
    /**
     * 获取省
     */
    public function getProvince()
    {
        $province = Db::name('city')->field('id,name')->where(array('level' => 1))->cache(true)->select();
        $res = array('status' => 1, 'msg' => '获取成功', 'result' => $province);
        exit(json_encode($res));
    }

    /**
     * 获取市或者区
     */
    public function getRegionByParentId()
    {
        $parent_id = input('city');
        $res = array('status' => 0, 'msg' => '获取失败，参数错误', 'result' => '');
        if($parent_id){
            $region_list = Db::name('area')->field('id,name')->where(['parentid'=>$parent_id])->select();
            $res = array('status' => 1, 'msg' => '获取成功', 'result' => $region_list);
        }
        exit(json_encode($res));
    }
}