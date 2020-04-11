<?php

namespace app\mobile\controller;

use app\admin\model\AdManage;
use app\admin\model\City;
use app\admin\model\HrefManage;
use app\admin\model\PrivateHomeManage;
use app\common\controller\MobileBase;
use app\format\PrivateHomeFormat;
use think\Db;
use think\facade\Validate;
use think\Request;

class PrivateHome extends MobileBase
{
    public $City;

    //初始化
    protected function initialize()
    {
        parent::initialize();
        $this->City = new City();
    }

    //会员中心首页
    public function index(Request $request)
    {
        $city = getCity($request);
        $cityInfo = Db::name('city')->where('id', 'in', $city)->select();
        $areaInfo = Db::name('area')->where('parentId', 'in', $city)->select();
        $cityList = Db::name('city')->where('id', 'not in', $city)->select();
        $title = $cityInfo[0]['name'].'民宅';
        $recommend = PrivateHomeManage::where(["type" => 1])
            ->order(array('releasetime' => 'DESC'))
            ->limit(0, 10)
            ->select();
        $new = PrivateHomeManage::order(array('releasetime' => 'DESC'))
            ->limit(0, 10)
            ->select();
        $hot = PrivateHomeManage::where(["type" => 2])
            ->order(array('releasetime' => 'DESC'))
            ->limit(10)
            ->select();
        $href = HrefManage::order('sort', 'desc')
            ->select()
            ->toArray();
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

        $formatInstance = PrivateHomeFormat::getInstance();
        $this->assign([
            'recommend' => $formatInstance->formatList($recommend),
            'newList' => $formatInstance->formatList($new),
            'hotList' => $formatInstance->formatList($hot),
            'cityInfo' => $cityInfo,
            'areaInfo' => $areaInfo,
            'cityList' => $cityList,
            'empty' => '<span class="empty">没有数据</span>',
            'href' => $href,
            'ad' => $adList,
            'categoryList' => PrivateHomeManage::CATEGORY_CONFIG,
            'struckList' => PrivateHomeManage::STRUCT_CONFIG,
            'floorType' => PrivateHomeManage::FLOOR_TYPE,
            'measureList' => PrivateHomeManage::MEASURE_LIST,
            'title' => $title
        ]);

        return $this->fetch('index');
    }

    public function detail(Request $request)
    {
        $validate = Validate::make([
            'id' => 'require|integer'
        ]);
        if (!$validate->check($request->param())) {
            $this->error($validate->getError());
        }

        $id = $request->param('id/d', '');
        $category = request()->param('category');

        $info = PrivateHomeManage::where(['id' => $id])
            ->with(['belongsToOneCity', 'belongsToOneArea'])
            ->find();

        $cityInfo = Db::name('city')
            ->where('id', 'in', $info['city'])->select();
        $city = $cityInfo[0]['id'];
        $areaInfo = Db::name('area')
            ->where('parentId', 'in', $info['city'])->select();

        $cityList = Db::name('city')
            ->where('id', 'not in', $info['city'])->select();

        $recommend =PrivateHomeManage::where(["type" => 1])
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
        $formatInstance = PrivateHomeFormat::getInstance();

        $this->assign([
            'cityList' => $cityList,
            'info' => $formatInstance->formatDetail($info),
            'cityInfo' => $cityInfo,
            'areaInfo' => $areaInfo,
            'recommend' => $formatInstance->formatList($recommend),
            'ad' => $adList,
            'category' => $category,
        ]);
        return $this->fetch('detail');
    }


    /**
     * 获取数据
     * @param PrivateHomeManage $privateHomeManage
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function ajaxSearchHome(PrivateHomeManage $privateHomeManage)
    {
        $data = $privateHomeManage->getWorkShopBySearch();
        return json([
            'data' => PrivateHomeFormat::getInstance()->formatList($data),
            'page' => paginate($data)
        ]);
    }
}
