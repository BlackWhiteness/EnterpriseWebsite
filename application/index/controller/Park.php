<?php

namespace app\index\controller;

use app\admin\model\AdManage;
use app\admin\model\HrefManage;
use app\admin\model\ParkManage;
use app\common\controller\Homebase;
use app\format\ParkFormat;
use app\admin\model\City;
use \think\Db;
use think\facade\Validate;
use think\Request;

class Park extends Homebase
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
        $cityList = Db::name('city')->where('id', '<>', $city)->select();
        $cityInfo = Db::name('city')->where('id', '=', $city)->find();
        $areaInfo = Db::name('area')->where('parentId', 'in', $city)->select();
        $recommend = ParkManage::where('type','=',1)
            ->order(array('releasetime' => 'DESC'))
            ->page(1, 10)->select();
        $hotList = ParkManage::where('type','=',2)
            ->where('city','=',$city)
            ->order(array('releasetime' => 'DESC'))
            ->page(1, 10)->select();

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
        $formatInstance = ParkFormat::getInstance();

        $this->assign([
            'recommend' => $formatInstance->formatList($recommend),
            'cityInfo' => $cityInfo,
            'areaInfo' => $areaInfo,
            'cityList' => $cityList,
            'href' => $href,
            'ad' => $adList,
            'hotList' =>$hotList
        ]);
        return $this->fetch('index');
    }

    public function detail(Request $request)
    {
        $validate = Validate::make([
            'id' => 'require'
        ]);
        if (!$validate->check($request->param())) {
            $this->error($validate->getError());
        }

        $id = $request->param('id/d', '');
        $info = ParkManage::where(['id' => $id])
            ->with(['belongsToOneCity', 'belongsToOneArea'])
            ->find();

        $cityInfo = Db::name('city')
            ->where('id', '=', $info['city'])->find();
        $city = $cityInfo['id'];
        $areaInfo = Db::name('area')
            ->where('parentId', 'in', $info['city'])->select();

        $cityList = Db::name('city')
            ->where('id', 'not in', $info['city'])->select();

        $recommend = ParkManage::where('type','=',1)
            ->order(array('releasetime' => 'DESC'))
            ->page(1, 10)->select();
        $hotList = ParkManage::where('type','=',2)
            ->where('city','=',$city)
            ->order(array('releasetime' => 'DESC'))
            ->page(1, 10)->select();
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
        $formatInstance = ParkFormat::getInstance();
        $this->assign([
            'cityList' => $cityList,
            'info' => $formatInstance->formatDetail($info),
            'cityInfo' => $cityInfo,
            'areaInfo' => $areaInfo,
            'recommend' => $formatInstance->formatList($recommend),
            'hotList' => $formatInstance->formatList($hotList),
            'ad' => $adList,
        ]);
        return $this->fetch('detail');
    }


    /**
     * 获取数据
     *
     * @param ParkManage $parkManage
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function ajaxSearchPark(ParkManage $parkManage)
    {
        $data = $parkManage->getList();
        return json([
            'data' => ParkFormat::getInstance()->formatList($data),
            'page' => paginate($data)
        ]);
    }
}
