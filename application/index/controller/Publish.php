<?php
namespace app\index\controller;
use app\common\controller\Homebase;
use \think\Db;
class Publish extends Homebase
{
        //初始化
    protected function initialize()
    {
        parent::initialize();
    }

    //会员中心首页
    public function owner()
    {
        $data = $this->request->param();
        $city = isset($_COOKIE['city'])?$_COOKIE['city']:6;
        $cityInfo = Db::name('city')->where('id','in', $city)->select();
        $areaInfo = Db::name('area')->where('parentId','in', $city)->select();
        $cityList = Db::name('city')->where('id','not in', $city)->select();
        $recommend = Db::name('workshop')->order(array('releasetime' => 'DESC'))->paginate(1);
        $this->assign("recommend", $recommend);
        $this->assign("cityInfo", $cityInfo);
        $this->assign("areaInfo", $areaInfo);
        $this->assign("cityList", $cityList);
        return $this->fetch("owner");
    }
    
    public function customer()
    {
        $data = $this->request->param();
        $city = isset($_COOKIE['city'])?$_COOKIE['city']:8;
        $cityInfo = Db::name('city')->where('id','in', $city)->select();
        $areaInfo = Db::name('area')->where('parentId','in', $city)->select();
        $cityList = Db::name('city')->where('id','not in', $city)->select();
        $recommend = Db::name('workshop')->order(array('releasetime' => 'DESC'))->paginate(1);
        $this->assign("recommend", $recommend);
        $this->assign("cityInfo", $cityInfo);
        $this->assign("areaInfo", $areaInfo);
        $this->assign("cityList", $cityList);
        return $this->fetch("customer");
    }

}
