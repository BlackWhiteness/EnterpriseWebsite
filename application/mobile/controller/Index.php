<?php

namespace app\mobile\controller;

use app\admin\model\OwnerDemand;
use app\admin\model\Workshop as Workshop_Model;
use app\admin\model\Officebuilding;
use app\admin\model\City as City_Model;
use app\common\controller\MobileBase;
use app\format\LandFormat;
use app\format\OfficeBuildFormat;
use app\format\WorkShopFormat;
use app\index\validate\Customer;
use think\App;
use think\Db;
use think\Loader;
use think\Request;

class Index extends MobileBase
{
    //初始化
    protected function initialize()
    {
        parent::initialize();
        $this->City_Model = new City_Model;
    }

    //会员中心首页
    public function index(Request $request)
    {
//        if ($request->isAjax()) {
//            $para = $request->post();
//            $CustomerValidate = new Customer();
//            $result = $CustomerValidate->check($para);
//            if (!$result) {
//                return $this->error($CustomerValidate->getError());
//            }
//            if (OwnerDemand::create($para)) {
//                return 1;
//            } else {
//                return 2;
//            }
//        }
        $city = isset($_COOKIE['city']) ? $_COOKIE['city'] : 8;
        $officeInstance = OfficeBuildFormat::getInstance();
        $workshopInstance = WorkShopFormat::getInstance();
        $landInstance = LandFormat::getInstance();
        $workshop = Workshop_Model::order(array('releasetime' => 'DESC'))->page(1, 5)->select()->toArray();
        $office = Officebuilding::where(array('type' => 2))->order(array('releasetime' => 'DESC'))->page(1, 4)->select()->toArray();
        $land = Workshop_Model::where(array('type' => 2))->order(array('releasetime' => 'DESC'))->page(1, 6)->select()->toArray();
        $this->assign([
            'workshop'=>$workshopInstance->formatList($workshop),
            'office' => $officeInstance->formatList($office),
            'land' => $landInstance->formatList($land),
        ]);
        return $this->fetch();
    }

    public function city_shift(Request $request)
    {
        $city = isset($_COOKIE['city']) ? $_COOKIE['city'] : 8;
        $cityInfo = Db::name('city')->where('id', '=', $city)->find();
        $cityList = Db::name('city')->where('id', '<>', $city)->select();
        $this->assign([
            'currentCity' =>$cityInfo,
            'cityList' => $cityList
        ]);
        return $this->fetch("city_shift");
    }

}
