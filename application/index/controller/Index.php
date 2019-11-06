<?php

namespace app\index\controller;

use app\common\controller\Homebase;
use app\admin\model\Workshop as Workshop_Model;
use app\admin\model\City as City_Model;
use \think\Db;
use think\Request;

class Index extends Homebase
{
    public function test(Request $request)
    {

        echo $request->method();
        echo $request->ext();
        echo 1111111111;
    }

    //初始化
    protected function initialize()
    {
        parent::initialize();
        $this->City_Model = new City_Model;
    }

    //会员中心首页
    public function index()
    {
        $data = $this->request->param();
        $city = isset($_COOKIE['city']) ? $_COOKIE['city'] : 8;

        $cityInfo = Db::name('city')->where('id', 'in', $city)->select();
        $areaInfo = Db::name('area')->where('parentId', 'in', $city)->select();

        $cityList = Db::name('city')->where('id', 'not in', $city)->select();

        $newworkshop = [];
        $firstcity = Db::name('city')->order(array('id' => 'ASC'))->page(1, 9)->select();

        foreach ($firstcity as $k => $value) {
            $sz = Workshop_Model::where(array('city' => $value['id']))->order(array('releasetime' => 'DESC'))->page(1, 5)->select()->toArray();
            $newworkshop[$k]['city'] = $value;
            $newworkshop[$k]['workshop'] = $sz;
        }
        $hot = Workshop_Model::where(array('type' => 2))->order(array('releasetime' => 'DESC'))->page(1, 6)->select()->toArray();
        $this->assign("hot", $hot);
        $recommend = Workshop_Model::where(array('type' => 1))->order(array('releasetime' => 'DESC'))->page(1, 10)->select()->toArray();
        $this->assign("recommend", $recommend);
        $this->assign("cityInfo", $cityInfo);
        $this->assign("cityList", $cityList);
        $this->assign("areaInfo", $areaInfo);
        $this->assign("newworkshop", $newworkshop);

        return $this->fetch();
    }
}
