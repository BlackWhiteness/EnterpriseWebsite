<?php

namespace app\index\controller;

use app\admin\model\AdManage;
use app\admin\model\HrefManage;
use app\admin\model\LandManage;
use app\admin\model\Officebuilding;
use app\common\controller\Homebase;
use app\admin\model\Workshop as Workshop_Model;
use app\admin\model\City as City_Model;
use app\format\OfficeBuildFormat;
use \think\Db;
use think\Request;

class Index extends Homebase
{
    //初始化
    protected function initialize()
    {
        parent::initialize();
        $this->City_Model = new City_Model;
    }

    public function test(Request $request)
    {
        $param = $request->param();
        return json($param);
    }

    /**
     * 网站首页
     * @return mixed|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $city = isset($_COOKIE['city']) ? $_COOKIE['city'] : 8;
        $cityInfo = Db::name('city')->where('id', '=', $city)->find();
        $areaInfo = Db::name('area')->where('parentId', 'in', $city)->select();
        $cityList = Db::name('city')->where('id', '<>', $city)->select();
        $firstCity = Db::name('city')->order(array('id' => 'ASC'))->page(1, 9)->select();
        $newWorkShop = $newOffice =$newLand= [];
        foreach ($firstCity as $k => $value) {
            //厂房
            $sz = Workshop_Model::where(array('city' => $value['id']))->order(array('releasetime' => 'DESC'))->page(1, 5)->select()->toArray();
            if (count($sz) > 0) {
                $newWorkShop[$k]['city'] = $value;
                $newWorkShop[$k]['workshop'] = $sz;
            }
            //写字楼
            $ob = Officebuilding::where(array('city' => $value['id']))->order(array('releasetime' => 'DESC'))->page(1, 5)->select()->toArray();
            if (count($ob) > 0) {
                $newOffice[$k]['city'] = $value;
                $newOffice[$k]['workshop'] = $ob;
            }
            //土地
            $land = LandManage::where(array('city' => $value['id']))->order(array('releasetime' => 'DESC'))->page(1, 5)->select()->toArray();
            if (count($land) > 0) {
                $newLand[$k]['city'] = $value;
                $newLand[$k]['workshop'] = $land;
            }
        }
        $hot = Workshop_Model::where(array('type' => 2))->order(array('releasetime' => 'DESC'))->page(1, 6)->select()->toArray();
        $recommend = Workshop_Model::where(array('type' => 1))->order(array('releasetime' => 'DESC'))->page(1, 10)->select()->toArray();
        $href = HrefManage::order('sort', 'desc')->select()->toArray();
        $officeInstance = OfficeBuildFormat::getInstance();
        $ad = AdManage::where('is_enable', '=', 1)->order(['code' => 'asc', 'sort' => 'desc'])->select();
        $adList = [];
        foreach ($ad as $row) {
            $detail = [
                'title' => $row->title,
                'pic_path' => $row->pic_path,
                'href' => $row->href
            ];
            if ($row->code == '001') {
                $adList['top'] = $detail;
            } elseif ($row->code == '002') {
                $adList['mid_ad'][] = $detail;
            } elseif ($row->code == '003') {
                $adList['bottom_ad'][] = $detail;
            }
        }

        $this->assign([
            'recommend' => $officeInstance->formatList($recommend),
            'cityInfo' => $cityInfo,
            'cityList' => $cityList,
            'areaInfo' => $areaInfo,
            'newWorkShop' => $newWorkShop,
            "hot" => $officeInstance->formatList($hot),
            'href' => $href,
            'ad' => $adList,
            'newOffice'=>$newOffice,
            'newLand'=>$newLand

        ]);
        return $this->fetch();
    }
}
