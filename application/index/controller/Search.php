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
use app\format\OfficeBuildFormat;
use app\format\ShopFormat;
use app\format\WorkShopFormat;
use app\search\model\Workshopsearch as Workshopsearch;
use app\search\model\Offbuildingsearch as Offbuildingsearch;
use app\admin\model\City;
use app\admin\model\Area;
use \think\Db;
use think\facade\Validate;
use think\Request;
use app\format\LandFormat;

class Search extends Homebase
{
    public $Workshopsearch;
    public $Offbuildingsearch;
    public $City;

    //初始化
    protected function initialize()
    {
        parent::initialize();
        $this->Workshopsearch = new Workshopsearch();
        $this->Offbuildingsearch = new Offbuildingsearch();
        $this->City = new City();
    }

    //会员中心首页
    public function workshop(Request $request)
    {
        $city = getCity();
        $cityList = Db::name('city')->where('id', '<>', $city)->select();
        $cityInfo = Db::name('city')->where('id', '=', $city)->find();
        $areaInfo = Db::name('area')->where('parentId', 'in', $city)->select();
        $category = request()->param('category');
//        dump(in_array($category, Workshop::CATEGORY_CONFIG));die;
        $tagName = in_array($category, [1, 2, 3]) ? Workshop::CATEGORY_CONFIG[$category] : '';
        $recommend = Workshop::name('workshop')
            ->where(["type" => 1])
            ->order(array('releasetime' => 'DESC'))
            ->page(1, 10)->select();
//        dump($recommend);die;
        $floor1 = Db::name('workshop')
            ->where(["floor" => 3,'city'=>$city,'category'=>$category])
            ->order(['releasetime' => 'DESC'])
            ->paginate(10);
        $floor2 = Db::name('workshop')->where(["floor" => 1,'city'=>$city,'category'=>$category])
            ->order(array('releasetime' => 'DESC'))->paginate(10);
        $floor3 = Db::name('workshop')
            ->where(["is_decorate" => 1,'city'=>$city,'category'=>$category])
            ->order(array('releasetime' => 'DESC'))
            ->paginate(10);
        $href = HrefManage::order('sort', 'desc')
            ->select()->toArray();
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
        $workFormat = WorkShopFormat::getInstance();
        $this->assign([
            'recommend' => $workFormat->formatList($recommend),
            'floor1' => $floor1,
            'floor2' => $floor2,
            'floor3' => $floor3,
            'cityInfo' => $cityInfo,
            'areaInfo' => $areaInfo,
            'cityList' => $cityList,
            'href' => $href,
            'ad' => $adList,
            'category' => $category,
            'tag' => $cityInfo['name'] . $tagName,
            'category_type_name' => $tagName ? mb_substr($tagName, 0, 2, 'utf8') : '厂房',

        ]);

        return $this->fetch('rentalofworkshop');
    }

    public function workshopdetail(Request $request)
    {
        $validate = Validate::make([
            'id' => 'require|integer'
        ]);
        if (!$validate->check($request->param())) {
            $this->error($validate->getError());
        }

        $id = $request->param('id/d', '');
        $category = request()->param('category');
        $info = Workshop::where(['id' => $id])
            ->with(['belongsToOneCity', 'belongsToOneArea'])
            ->find();

        $cityInfo = Db::name('city')
            ->where('id', '=', $info['city'])->find();
        $city = $info['city'];
        $areaInfo = Db::name('area')
            ->where('parentId', 'in', $info['city'])->select();

        $cityList = Db::name('city')
            ->where('id', 'not in', $info['city'])->select();

        $recommend = Workshop::where(["type" => 1])
            ->order(array('releasetime' => 'DESC'))
            ->paginate(10);
        $floor1 = Db::name('workshop')
            ->where(["floor" => 3,'city'=>$city,'category'=>$category])
            ->order(['releasetime' => 'DESC'])
            ->paginate(10);
        $floor2 = Db::name('workshop')->where(["floor" => 1,'city'=>$city,'category'=>$category])
            ->order(array('releasetime' => 'DESC'))->paginate(10);
        $floor3 = Db::name('workshop')
            ->where(["is_decorate" => 1,'city'=>$city,'category'=>$category])
            ->order(array('releasetime' => 'DESC'))
            ->paginate(10);
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
        $tagName = in_array($category, [1, 2, 3]) ? Workshop::CATEGORY_CONFIG[$category] : '';
        $workInstance = WorkShopFormat::getInstance();
        $this->assign([
            'cityList' => $cityList,
            'info' => $workInstance->formatDetail($info),
            'cityInfo' => $cityInfo,
            'areaInfo' => $areaInfo,
            'recommend' => $workInstance->formatList($recommend),
            'floor1' => $floor1,
            'floor2' => $floor2,
            'floor3' => $floor3,
            'ad' => $adList,
            'category' => $category,
            'tag' => $cityInfo[0]['name'] . $tagName,
            'category_type_name' => $tagName ? mb_substr($tagName, 0, 2, 'utf8') : '厂房',
        ]);
        return $this->fetch('workshopdetail');
    }

    public function officebuilding(Request $request)
    {

        $city = getCity();
        $cityInfo = Db::name('city')->where('id', '=', $city)->find();
        $areaInfo = Db::name('area')->where('parentId', 'in', $city)->select();
        $cityList = Db::name('city')->where('id', 'not in', $city)->select();

        $recommend = Officebuilding::where(["type" => 1])->order(array('releasetime' => 'DESC'))
            ->limit(0, 10)->select();
        $new = Officebuilding::order(array('releasetime' => 'DESC'))
            ->limit(0, 10)->select();
        $hot = Officebuilding::where(["type" => 2])
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
            'recommend' => $this->formatOffice($recommend),
            'new' => $this->formatOffice($new),
            'hot' => $this->formatOffice($hot),
            'cityInfo' => $cityInfo,
            'areaInfo' => $areaInfo,
            'cityList' => $cityList,
            'empty' => '<span class="empty">没有数据</span>',
            'href' => $href,
            'ad' => $adList,
        ]);
        return $this->fetch('officebuilding');

    }

    /**
     * 格式化写字楼用于列表展示
     * @param $data
     * @return array
     */
    public function formatOffice($data)
    {
        $rtn = [];
        foreach ($data as $row) {
            $rtn[] = [
                'id' => $row['id'],
                'region' => $row['region'],
                'measurearea' => $row['measurearea'],
                'releasetime' => $row['releasetime'],
                'plantrent' => $row['plantrent'],
                'newolddegree' => $row['newolddegree'],
                'name' => $row['name'],
                'tel' => $row['tel'],
                'detail' => $row['detail'],
                'tag' => $row['tag'],
                'imgs' => $row['imgs'] ? $row['imgs'][0] : '',
                'buildingname' => $row['buildingname'],
                'address' => $row['address'],
                'managementfee' => $row['managementfee'],
                'decoration' => $row['decoration'],
                'city' => $row['city'],
                'area' => $row['area'],
                'type' => $row['type'],
                'title' => $row['title']];
        }
        return $rtn;
    }

    public function offbuilddetail()
    {
        $id = $this->request->param('id/d', '');
        $info = Officebuilding::where(['id' => $id])->find();
        $cityInfo = Db::name('city')->where('id', '=', $info['city'])->find();
        $areaInfo = Db::name('area')->where('parentId', 'in', $info['city'])->select();
        $cityList = Db::name('city')->where('id', 'not in', $info['city'])->select();
        $recommend = Officebuilding::where(["type" => 1])->order(array('releasetime' => 'DESC'))
            ->limit(0, 10)->select();
        $floor1 = Officebuilding::where('city','=',$cityInfo['id'])
            ->order(array('releasetime' => 'DESC'))
            ->limit(0, 10)->select();

        $floor2 = Officebuilding::where(["type" => 2])
            ->where('city','=',$cityInfo['id'])
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
            'recommend' => $this->formatOffice($recommend),
            'floor1' => $this->formatOffice($floor1),
            'floor2' => $this->formatOffice($floor2),
            'cityInfo' => $cityInfo,
            'areaInfo' => $areaInfo,
            'cityList' => $cityList,
            'href' => $href,
            'ad' => $adList,
            'info' => OfficeBuildFormat::getInstance()->formatDetail($info)
        ]);
        return $this->fetch('offbuilddetail');
    }

    /**
     * 获取数据
     * @param Workshop_Model $workshop
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function ajaxSearchWs(Workshop $workshop)
    {
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
        $data = $officebuilding->getOfficeBuild();
        return json([
            'data' => OfficeBuildFormat::getInstance()->formatAjaxList($data),
            'page' => paginate($data)
        ]);
    }

    public function landList()
    {
        $city = getCity();
        $cityInfo = Db::name('city')->where('id', '=', $city)->find();
        $areaInfo = Db::name('area')->where('parentId', 'in', $city)->select();
        $cityList = Db::name('city')->where('id', 'not in', $city)->select();

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
        ]);
        return $this->fetch('landlist');
    }

    public function landdetail()
    {
        $id = $this->request->param('id/d', '');
        $info = LandManage::where(['id' => $id])->find();
        $cityInfo = Db::name('city')->where('id', '=', $info['city'])->find();
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
        return $this->fetch('land_detail');
    }

    public function shopList()
    {
        $city = getCity();
        $cityInfo = Db::name('city')->where('id', '=', $city)->find();
        $areaInfo = Db::name('area')->where('parentId', 'in', $city)->select();
        $cityList = Db::name('city')->where('id', 'not in', $city)->select();

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
        ]);
        return $this->fetch('shoplist');
    }

    public function shopdetail()
    {
        $id = $this->request->param('id/d', '');
        $info = ShopManage::where(['id' => $id])->find();
        $cityInfo = Db::name('city')->where('id', '=', $info['city'])->find();
        $areaInfo = Db::name('area')->where('parentId', 'in', $info['city'])->select();
        $cityList = Db::name('city')->where('id', 'not in', $info['city'])->select();
        $recommend = ShopManage::where(["category" => 1])->order(array('releasetime' => 'DESC'))
            ->limit(0, 10)->select();
        $floor1 = ShopManage::order(array('releasetime' => 'DESC'))
            ->limit(0, 10)->select();
        $floor2 = ShopManage::where(["category" => 2])
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
            'floor1' => ShopFormat::getInstance()->formatList($floor1),
            'floor2' => ShopFormat::getInstance()->formatList($floor2),
            'cityInfo' => $cityInfo,
            'areaInfo' => $areaInfo,
            'cityList' => $cityList,
            'href' => $href,
            'ad' => $adList,
            'info' => ShopFormat::getInstance()->formatDetail($info)
        ]);
        return $this->fetch('shop_detail');
    }

    /**
     * 获取土地信息
     * @param LandManage $landManage
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function ajaxSearchLand(LandManage $landManage)
    {
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
        $data = $shopManage->getShopBuild();
        return json([
            'data' => ShopFormat::getInstance()->formatList($data),
            'page' => paginate($data)
        ]);
    }
}
