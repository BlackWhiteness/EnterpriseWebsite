<?php

namespace app\index\controller;

use app\admin\model\AdManage;
use app\admin\model\HrefManage;
use app\admin\model\Workshop;
use app\common\controller\Homebase;
use app\format\WorkShopFormat;
use app\admin\model\City;
use \think\Db;
use think\facade\Validate;
use think\Request;

class PrivateHome extends Homebase
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

        return $this->fetch('rentalofworkshop');
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
