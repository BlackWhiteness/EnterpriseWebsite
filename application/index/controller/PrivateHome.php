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

class PrivateHome extends Homebase
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
        $city = getCity($request);
        $cityList = Db::name('city')->where('id', '<>', $city)->select();
        $cityInfo = Db::name('city')->where('id', '=', $city)->select();
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
            'tag' => $cityInfo[0]['name'] . $tagName,
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
            ->where('id', 'in', $info['city'])->select();
        $city = $cityInfo[0]['id'];
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
}
