<?php

namespace app\index\controller;

use app\admin\model\AdManage;
use app\admin\model\HrefManage;
use app\admin\model\LandManage;
use app\admin\model\Officebuilding;
use app\admin\model\ShopManage;
use app\admin\model\Workshop;
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
     * @param Workshop_Model $workshop
     * @param LandManage $landManage
     * @param Officebuilding $officeBuilding
     * @param ShopManage $shopManage
     * @return mixed|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index(Workshop $workshop, LandManage $landManage, Officebuilding $officeBuilding, ShopManage $shopManage)
    {
        $city = isset($_COOKIE['city']) ? $_COOKIE['city'] : 8;
        $cityInfo = Db::name('city')->where('id', '=', $city)->find();
        $areaInfo = Db::name('area')->where('parentId', 'in', $city)->select();
        $cityList = Db::name('city')->where('id', '<>', $city)->select();
        $firstCity = Db::name('city')->order(array('id' => 'ASC'))->page(1, 9)->select();
        $newWorkShop = $newOffice = $newLand = [];
        foreach ($firstCity as $k => $value) {
            $sz = Workshop_Model::where(array('city' => $value['id']))
                ->order(array('releasetime' => 'DESC'))
                ->page(1, 5)
                ->select()
                ->toArray();
            $newWorkShop[$k]['city'] = $value;
            $newWorkShop[$k]['workshop'] = $sz;
        }
        $rentWs = $workshop->getFirstPage($city, 1)->toArray();
        $saleWs = $workshop->getFirstPage($city, 2)->toArray();
        $whWs = $workshop->getFirstPage($city, 3)->toArray();
        $landList = $landManage->getFirstPage($city)->toArray();
        $obList = $officeBuilding->getFirstPage($city)->toArray();
        $shop = $shopManage->getFirstPage($city)->toArray();
        $hot = Workshop_Model::where(array('type' => 2))->order(array('releasetime' => 'DESC'))->page(1, 6)->select()->toArray();
        $recommend = Workshop_Model::where(array('type' => 1))->order(array('releasetime' => 'DESC'))->page(1, 10)->select()->toArray();
        $href = HrefManage::order('sort', 'desc')->select()->toArray();
        $officeInstance = OfficeBuildFormat::getInstance();
        $ad = AdManage::where('is_enable', '=', 1)
            ->order(['code' => 'asc', 'sort' => 'desc'])
            ->select();
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
//        echo '<pre>';print_r($rentWs);die;
        $this->assign([
            'recommend' => $officeInstance->formatList($recommend),
            'cityInfo' => $cityInfo,
            'cityList' => $cityList,
            'areaInfo' => $areaInfo,
            "hot" => $officeInstance->formatList($hot),
            'href' => $href,
            'ad' => $adList,
            'rentWs' => $rentWs,
            'saleWs' => $saleWs,
            'whWs' => $whWs,
            'landList' => $landList,
            'obList' => $obList,
            'shop' => $shop,
            'newWorkShop' => $newWorkShop,
            'empty' => '<span class="empty">没有数据</span>'
        ]);
        return $this->fetch();
    }
}
