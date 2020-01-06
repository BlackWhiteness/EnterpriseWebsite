<?php

namespace app\mobile\controller;

use app\admin\model\AdManage;
use app\admin\model\HrefManage;
use app\admin\model\LandManage;
use app\admin\model\Officebuilding;
use app\admin\model\ShopManage;
use app\admin\model\Workshop as Workshop_Model;
use app\common\controller\MobileBase;
use app\admin\model\Workshop;
use app\format\LandFormat;
use app\format\OfficeBuildFormat;
use app\format\ShopFormat;
use app\format\WorkShopFormat;
use app\search\model\Workshopsearch as Workshopsearch;
use app\search\model\Offbuildingsearch as Offbuildingsearch;
use app\admin\model\City;
use app\admin\model\Area;
use \think\Db;
use think\Request;

class Search extends MobileBase
{
    //初始化
    protected function initialize()
    {
        parent::initialize();
        $this->Workshopsearch = new Workshopsearch();
        $this->Offbuildingsearch = new Offbuildingsearch();
        $this->City = new City();
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function workshop(Request $request, Workshop $workshop)
    {
        $city = getCity($request);
        $cityList = Db::name('city')->where('id', '<>', $city)->select();
        $cityInfo = Db::name('city')->where('id', '=', $city)->select();
        $areaInfo = Db::name('area')->where('parentId', 'in', $city)->select();
        $category = request()->param('category');
        $tagName = in_array($category, [1, 2, 3]) ? Workshop::CATEGORY_CONFIG[$category] : '';
        $data = $workshop->getWorkShopBySearch();
        $this->assign([
            'cityInfo' => $cityInfo,
            'areaInfo' => $areaInfo,
            'cityList' => $cityList,
            'category' => $category,
            'tag' => $cityInfo[0]['name'] . $tagName,
            'category_type_name' => $tagName ? mb_substr($tagName, 0, 2, 'utf8') : '厂房',
            'data' => WorkShopFormat::getInstance()->formatList($data),
            'page' => paginate($data)
        ]);
        return $this->fetch('rentalofworkshop');
    }

    public function workshopdetail()
    {
        $id = $this->request->param('id/d', '');
        $info = Db::name('workshop')->where(['id' => $id])->find();
        $cityInfo = Db::name('city')->where('id', 'in', $info['city'])->select();
        $areaInfo = Db::name('area')->where('parentId', 'in', $info['city'])->select();

        $this->assign("info", $info);
        $this->assign("cityInfo", $cityInfo);
        $this->assign("areaInfo", $areaInfo);
        $recommend = Db::name('workshop')->where(["type" => 1])->order(array('releasetime' => 'DESC'))->paginate(10);
        $this->assign("recommend", $recommend);
        $floor1 = Db::name('workshop')->where(["floor" => 1])->order(array('releasetime' => 'DESC'))->paginate(10);
        $this->assign("floor1", $floor1);
        $floor2 = Db::name('workshop')->where(["floor" => 2])->order(array('releasetime' => 'DESC'))->paginate(10);
        $this->assign("floor2", $floor2);
        $floor3 = Db::name('workshop')->where(["floor" => 3])->order(array('releasetime' => 'DESC'))->paginate(10);
        $this->assign("floor3", $floor3);
        return $this->fetch('workshopdetail');
    }

    /**
     * 土地
     * @param LandManage $landManage
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function landList(LandManage $landManage)
    {
        $city = isset($_COOKIE['city']) ? $_COOKIE['city'] : 8;
        $cityInfo = Db::name('city')->where('id', 'in', $city)->select();
        $areaInfo = Db::name('area')->where('parentId', 'in', $city)->select();
        $cityList = Db::name('city')->where('id', 'not in', $city)->select();
        $data = $landManage->getLandSearch();
        $this->assign([
            'cityInfo' => $cityInfo,
            'areaInfo' => $areaInfo,
            'cityList' => $cityList,
            'data' => LandFormat::getInstance()->formatList($data),
            'page' => paginate($data)
        ]);
        return $this->fetch('landlist');
    }

    public function officebuilding(Request $request, Officebuilding $officebuilding)
    {
        $city = getCity($request);
        $cityInfo = Db::name('city')->where('id', 'in', $city)->select();
        $areaInfo = Db::name('area')->where('parentId', 'in', $city)->select();
        $cityList = Db::name('city')->where('id', 'not in', $city)->select();
        $data = $officebuilding->getOfficeBuild();

        $this->assign([
            'cityInfo' => $cityInfo,
            'areaInfo' => $areaInfo,
            'cityList' => $cityList,
            'data' => OfficeBuildFormat::getInstance()->formatAjaxList($data),
            'page' => paginate($data)
        ]);
        return $this->fetch('officebuilding');

    }

    public function shopList(ShopManage $shopManage)
    {
        $city = isset($_COOKIE['city']) ? $_COOKIE['city'] : 8;
        $cityInfo = Db::name('city')->where('id', 'in', $city)->select();
        $areaInfo = Db::name('area')->where('parentId', 'in', $city)->select();
        $cityList = Db::name('city')->where('id', 'not in', $city)->select();
        $data = $shopManage->getShopBuild();

        $this->assign([
            'cityInfo' => $cityInfo,
            'areaInfo' => $areaInfo,
            'cityList' => $cityList,
            'data' => ShopFormat::getInstance()->formatList($data),
            'page' => paginate($data)
        ]);
        return $this->fetch('shoplist');
    }

    public function createUrl()
    {
        $param = $this->request->param();
        exit(url('index/search/workshop', $param));
    }

    public function offbuilddetail()
    {
        $id = $this->request->param('id/d', '');
        $info = Db::name('officebuilding')->where(['id' => $id])->find();
        $cityInfo = Db::name('city')->where('id', 'in', $info['city'])->select();
        $areaInfo = Db::name('area')
            ->where('parentId', 'in', $info['city'])->select();

        $this->assign("info", $info);
        $this->assign("cityInfo", $cityInfo);
        $this->assign("areaInfo", $areaInfo);
        $recommend = Db::name('officebuilding')->where(["type" => 1])->order(array('releasetime' => 'DESC'))->paginate(10);
        $this->assign("recommend", $recommend);
        $new = Db::name('officebuilding')->order(array('releasetime' => 'DESC'))->paginate(10);
        $this->assign("new", $new);
        $hot = Db::name('officebuilding')->where(["type" => 2])->order(array('releasetime' => 'DESC'))->paginate(10);
        $this->assign("hot", $hot);
        return $this->fetch('offbuilddetail');
    }
}
