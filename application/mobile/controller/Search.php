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
     * 厂房或仓库
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

    public function landdetail()
    {
        $id = $this->request->param('id/d', '');
        $info = LandManage::where(['id' => $id])->find();
        $cityInfo = Db::name('city')->where('id', 'in', $info['city'])->select();
        $areaInfo = Db::name('area')->where('parentId', 'in', $info['city'])->select();
        $cityList = Db::name('city')->where('id', 'not in', $info['city'])->select();
        $recommend = LandManage::where(["type" => 1])->order(array('releasetime' => 'DESC'))
            ->limit(0, 10)->select();
        $new = LandManage::order(array('releasetime' => 'DESC'))
            ->limit(0, 10)->select();
        $hot = LandManage::where(["type" => 2])
            ->order(array('releasetime' => 'DESC'))->limit(10)->select();

        $href = HrefManage::order('sort', 'desc')->select()->toArray();
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
            'recommend' => LandFormat::getInstance()->formatList($recommend),
            'new' => LandFormat::getInstance()->formatList($new),
            'hot' => LandFormat::getInstance()->formatList($hot),
            'cityInfo' => $cityInfo,
            'areaInfo' => $areaInfo,
            'cityList' => $cityList,
            'href' => $href,
            'ad' => $adList,
            'info' => $info
        ]);
        return $this->fetch('landdetail');
    }

    public function shopdetail()
    {
        $id = $this->request->param('id/d', '');
        $info = ShopManage::where(['id' => $id])->find();
        $cityInfo = Db::name('city')->where('id', 'in', $info['city'])->select();
        $areaInfo = Db::name('area')->where('parentId', 'in', $info['city'])->select();
        $cityList = Db::name('city')->where('id', 'not in', $info['city'])->select();
        $recommend = ShopManage::where(["category" => 1])->order(array('releasetime' => 'DESC'))
            ->limit(0, 10)->select();
        $new = ShopManage::order(array('releasetime' => 'DESC'))
            ->limit(0, 10)->select();
        $hot = ShopManage::where(["category" => 2])
            ->order(array('releasetime' => 'DESC'))->limit(10)->select();

        $href = HrefManage::order('sort', 'desc')->select()->toArray();
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
            'recommend' => ShopFormat::getInstance()->formatList($recommend),
            'new' => ShopFormat::getInstance()->formatList($new),
            'hot' => ShopFormat::getInstance()->formatList($hot),
            'cityInfo' => $cityInfo,
            'areaInfo' => $areaInfo,
            'cityList' => $cityList,
            'href' => $href,
            'ad' => $adList,
            'info' => $info
        ]);
        return $this->fetch('shopdetail');
    }


    /**
     * 获取土地信息
     * @param LandManage $landManage
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function ajaxSearchLand(LandManage $landManage)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT');
        $data = $landManage->getLandSearch();
        return json([
            'data' => LandFormat::getInstance()->formatList($data),
            'page' => paginate($data)
        ]);
    }

    /**
     * 获取商铺信息
     * @param ShopManage $shopManage
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function ajaxSearchShop(ShopManage $shopManage)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT');
        $data = $shopManage->getShopBuild();
        return json([
            'data' => ShopFormat::getInstance()->formatList($data),
            'page' => paginate($data)
        ]);
    }

    /**
     * 获取数据
     * @param Workshop_Model $workshop
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function ajaxSearchWs(Workshop $workshop)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT');
        $category = request()->param('category');
        if (empty($category)) {
            return json(['status' => false, 'msg' => '参数错误!']);
        }
        $data = $workshop->getWorkShopBySearch();
        return json([
            'data' => WorkShopFormat::getInstance()->formatList($data),
            'page' => paginate($data)
        ]);
    }
    /**
     * 获取写字楼信息
     * @param Officebuilding $officebuilding
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function ajaxSearchOf(Officebuilding $officebuilding)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT');
        $data = $officebuilding->getOfficeBuild();
        return json([
            'data' => OfficeBuildFormat::getInstance()->formatAjaxList($data),
            'page' => paginate($data)
        ]);
    }
}
