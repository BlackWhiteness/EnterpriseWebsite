<?php

namespace app\index\controller;

use app\admin\model\AdManage;
use app\admin\model\HrefManage;
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
//        dump($cityList);die;
        $newWorkShop = [];
        $firstCity = Db::name('city')->order(array('id' => 'ASC'))->page(1, 9)->select();
        foreach ($firstCity as $k => $value) {
            $sz = Workshop_Model::where(array('city' => $value['id']))->order(array('releasetime' => 'DESC'))->page(1, 5)->select()->toArray();
            $newWorkShop[$k]['city'] = $value;
            $newWorkShop[$k]['workshop'] = $sz;
        }
        $hot = Workshop_Model::where(array('type' => 2))->order(array('releasetime' => 'DESC'))->page(1, 6)->select()->toArray();
        $this->assign("hot", $hot);
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
            "hot" => $hot,
            'href' => $href,
            'ad' => $adList
        ]);
        return $this->fetch();
    }
}
