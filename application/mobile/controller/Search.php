<?php

namespace app\mobile\controller;

use app\admin\model\AdManage;
use app\admin\model\City;
use app\admin\model\HrefManage;
use app\admin\model\LandManage;
use app\admin\model\Officebuilding;
use app\admin\model\ShopManage;
use app\admin\model\Workshop;
use app\admin\model\Workshop as Workshop_Model;
use app\common\controller\MobileBase;
use app\format\LandFormat;
use app\format\OfficeBuildFormat;
use app\format\ShopFormat;
use app\format\WorkShopFormat;
use app\search\model\Offbuildingsearch as Offbuildingsearch;
use app\search\model\Workshopsearch as Workshopsearch;
use think\Db;
use think\Request;

class Search extends MobileBase
{
    //初始化
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

        $measureList = [
            '0' => '不限',
            '500' => '500平米以下',
            '500-800' => '500-800平米',
            '800-1300' => '800-1300平米',
            '1300-2000' => '1300-2000平米',
            '2000-3000' => '2000-3000平米',
            '3000-5000' => '3000-5000平米',
            '5000-10000' => '5000-10000平米',
            '10000' => '10000平米以上'
        ];
        $floorList = [
            0 => '不限',
            1 => '一楼厂房',
            2 => '二楼以上',
            3 => '独院厂房',
            4 => '独栋厂房'
        ];
        $struckList = [
            0 => '不限',
            1 => '标准厂房',
            2 => '钢构结构厂房',
            3 => '简易厂房(铁皮房)',
            4 => '各类型仓库(单层仓库/多层仓库)'
        ];
        $this->assign([
            'cityInfo' => $cityInfo,
            'areaInfo' => $areaInfo,
            'cityList' => $cityList,
            'category' => $category,
            'tag' => $cityInfo[0]['name'] . $tagName,
            'category_type_name' => $tagName ? mb_substr($tagName, 0, 2, 'utf8') : '厂房',
            'measureList' => $measureList,
            'floorList' => $floorList,
            'struckList' => $struckList
        ]);
        return $this->fetch('rentalofworkshop');
    }

    public function workshopdetail()
    {
        $id = $this->request->param('id/d', '');
        $info = Workshop::where(['id' => $id])
            ->with(['belongsToOneCity', 'belongsToOneArea'])
            ->find();
        $cityInfo = Db::name('city')->where('id', 'in', $info['city'])->select();
        $areaInfo = Db::name('area')->where('parentId', 'in', $info['city'])->select();
        $workInstance = WorkShopFormat::getInstance();


        $recommend = Workshop::where(["category" => $info['category']])
            ->order(array('releasetime' => 'DESC'))
            ->paginate(10);

        $this->assign([
            'info' => $workInstance->formatDetail($info),
            'cityInfo' => $cityInfo,
            'areaInfo' => $areaInfo,
            'recommend' => $workInstance->formatList($recommend),
        ]);
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
//        $data = $landManage->getLandSearch();
        $measureList = [
            '0' => '不限',
            '500' => '500平米以下',
            '500-1000' => '500-1000平米',
            '1000-1500' => '1000-1500平米',
            '1500-2500' => '1500-2500平米',
            '2500-4000' => '2500-4000平米',
            '4000-6000' => '4000-6000平米',
            '6000-10000' => '6000-10000平米',
            '10000' => '10000平米以上'
        ];

        $renList = [
            '0' => '不限',
            '10' => '10元/平米以下',
            '10-15' => '10-15元/平米',
            '15-20' => '15-20元/平米',
            '20-25' => '20-25元/平米',
            '25' => '25元/平米'
        ];
        $tagList = [
            '0' => '不限',
            '工业用地' => '工业用地',
            '农业用地' => '农业用地',
            '商业用地' => '商业用地',
            '仓库用地' => '仓库用地',
            '临时用地' => '临时用地',
            '国有用地' => '国有用地',
            '集体用地' => '集体用地',
        ];

        $typeList = [
            0 => '不限',
            1 => '出租',
            2 => '出售'
        ];

        $this->assign([
            'cityInfo' => $cityInfo,
            'areaInfo' => $areaInfo,
            'cityList' => $cityList,
            'measureList' => $measureList,
            'renList' => $renList,
            'tagList' => $tagList,
            'typeList' => $typeList
        ]);
        return $this->fetch('landlist');
    }

    public function officebuilding(Request $request, Officebuilding $officebuilding)
    {
        $city = getCity($request);
        $cityInfo = Db::name('city')->where('id', 'in', $city)->select();
        $areaInfo = Db::name('area')->where('parentId', 'in', $city)->select();
        $cityList = Db::name('city')->where('id', 'not in', $city)->select();

        $measureList = [
            '0' => '不限',
            '100' => '100平米以下',
            '100-200' => '100-200平米',
            '200-300' => '200-300平米',
            '300-500' => '300-500平米',
            '500-800' => '500-800平米',
            '800-1000' => '800-1000平米',
            '1000' => '1000平米以上',
        ];
        $this->assign([
            'cityInfo' => $cityInfo,
            'areaInfo' => $areaInfo,
            'cityList' => $cityList,
            'typeList' => Officebuilding::INDUS_TYPE,
            'measureList' => $measureList,
            'tagList' => Officebuilding::TAG_CONFIG,
            'floorList' => Officebuilding::FLOOR_CONFIG,
            'saleList' => Officebuilding::SALE_LIST,
        ]);
        return $this->fetch('officebuilding');
    }

    public function shopList(ShopManage $shopManage)
    {
        $city = isset($_COOKIE['city']) ? $_COOKIE['city'] : 8;
        $cityInfo = Db::name('city')->where('id', 'in', $city)->select();
        $areaInfo = Db::name('area')->where('parentId', 'in', $city)->select();
        $cityList = Db::name('city')->where('id', 'not in', $city)->select();
//        $data = $shopManage->getShopBuild();

        $measureList = [
            '0' => '不限',
            '20' => '20平米以下',
            '20-50' => '20-50平米',
            '50-100' => '50-100平米',
            '100-200' => '100-200平米',
            '200-500' => '200-500平米',
            '500-1000' => '500-1000平米',
            '1000' => '1000平米以上',
        ];
        $floorList = [
            0=>'不限',
            1=>'一楼',
            2=>'独栋',
            3=>'独院',
        ];
        $struckList = [
            0=>'不限',
            1=>'钢混结构',
            2=>'钢结构',
            3=>'简易结构',
        ];
        $saleList = [
            -1 => '不限',
            0 => '出租',
            1 => '出售',
        ];

        $this->assign([
            'cityInfo' => $cityInfo,
            'areaInfo' => $areaInfo,
            'cityList' => $cityList,
            'measureList'=>$measureList,
            'floorList'=>ShopManage::FLOOR_CONFIG,
            'struckList'=>ShopManage::STRUCK_CONFIG,
            'saleList'=>ShopManage::SALE_CONFIG,
        ]);
        return $this->fetch('shopList');
    }

    public function createUrl()
    {
        $param = $this->request->param();
        exit(url('index/search/workshop', $param));
    }

    public function offbuilddetail()
    {
        $id = $this->request->param('id/d', '');
        $info = Officebuilding::where(['id' => $id])->find();
        $cityInfo = Db::name('city')->where('id', 'in', $info['city'])->select();
        $areaInfo = Db::name('area')
            ->where('parentId', 'in', $info['city'])->select();

        $recommend = Officebuilding::where(["type" => 1])->order(array('releasetime' => 'DESC'))->paginate(10);
        $this->assign([
            'info' => OfficeBuildFormat::getInstance()->formatDetail($info),
            'cityInfo'=>$cityInfo,
            'areaInfo'=>$areaInfo,
            'recommend'=>OfficeBuildFormat::getInstance()->formatAjaxList($recommend),
        ]);
        return $this->fetch('offbuilddetail');
    }

    public function landdetail()
    {
        $id = $this->request->param('id/d', '');
        $info = LandManage::where(['id' => $id])
            ->with(['belongsToOneCity', 'belongsToOneArea'])
            ->find();
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
            'info' => LandFormat::getInstance()->formatDetail($info)
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
            'cityInfo' => $cityInfo,
            'areaInfo' => $areaInfo,
            'cityList' => $cityList,
            'href' => $href,
            'ad' => $adList,
            'info' => ShopFormat::getInstance()->formatDetail($info)
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

    protected function initialize()
    {
        parent::initialize();
        $this->Workshopsearch = new Workshopsearch();
        $this->Offbuildingsearch = new Offbuildingsearch();
        $this->City = new City();
    }
}
