<?php

namespace app\index\controller;

use app\admin\model\AdManage;
use app\admin\model\HrefManage;
use app\admin\model\Officebuilding;
use app\admin\model\Workshop;
use app\common\controller\Homebase;
use app\admin\model\Workshop as Workshop_Model;
use app\format\OfficeBuildFormat;
use app\format\WorkShopFormat;
use app\search\model\Workshopsearch as Workshopsearch;
use app\search\model\Offbuildingsearch as Offbuildingsearch;
use app\admin\model\City;
use app\admin\model\Area;
use \think\Db;
use think\facade\Validate;
use think\Request;

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
    public function workshop()
    {
        $city = isset($_COOKIE['city']) ? $_COOKIE['city'] : 8;
        $cityList = Db::name('city')->where('id', 'not in', $city)->select();
        $cityInfo = Db::name('city')->where('id', 'in', $city)->select();
        $areaInfo = Db::name('area')->where('parentId', 'in', $city)->select();

        $recommend = Db::name('workshop')
            ->where(["type" => 1])->order(array('releasetime' => 'DESC'))
            ->paginate(10);
        $floor1 = Db::name('workshop')->where(["floor" => 1])
            ->order(array('releasetime' => 'DESC'))->paginate(10);
        $floor2 = Db::name('workshop')->where(["floor" => 2])
            ->order(array('releasetime' => 'DESC'))->paginate(10);
        $floor3 = Db::name('workshop')->where(["floor" => 3])
            ->order(array('releasetime' => 'DESC'))->paginate(10);
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
        $officeInstance = OfficeBuildFormat::getInstance();
        $this->assign([
            'recommend' => $officeInstance->formatList($recommend),
            'floor1' => $floor1,
            'floor2' => $floor2,
            'floor3' => $floor3,
            'cityInfo' => $cityInfo,
            'areaInfo' => $areaInfo,
            'cityList' => $cityList,
            'href' => $href,
            'ad' => $adList,

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
        $info = Workshop::where(['id' => $id])->find();

        $cityInfo = Db::name('city')
            ->where('id', 'in', $info['city'])->select();
        $areaInfo = Db::name('area')
            ->where('parentId', 'in', $info['city'])->select();

        $cityList = Db::name('city')
            ->where('id', 'not in', $info['city'])->select();

        $recommend = Workshop::where(["type" => 1])
            ->order(array('releasetime' => 'DESC'))
            ->paginate(10);
        $floor1 = Db::name('workshop')
            ->where(["floor" => 1])->order(array('releasetime' => 'DESC'))
            ->paginate(10);
        $floor2 = Db::name('workshop')
            ->where(["floor" => 2])->order(array('releasetime' => 'DESC'))
            ->paginate(10);
        $floor3 = Db::name('workshop')
            ->where(["floor" => 3])->order(array('releasetime' => 'DESC'))
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
        ]);
        return $this->fetch('workshopdetail');
    }

    public function officebuilding()
    {
        $data = $this->request->param();
        $city = isset($_COOKIE['city']) ? $_COOKIE['city'] : 8;
//        dump($city);die;
        $cityInfo = Db::name('city')->where('id', 'in', $city)->select();
        $areaInfo = Db::name('area')->where('parentId', 'in', $city)->select();
        $cityList = Db::name('city')->where('id', 'not in', $city)->select();
        $keywords = 1;
        $d = $this->Offbuildingsearch->searchDoc($data, 1, 10);
        $ids = array();
        if (count($d['hits']['hits'])) {
            foreach ($d['hits']['hits'] as $value) {
                array_push($ids, $value['_id']);
            }
        }
        $id_str = implode(',', $ids);
        $where['id'] = array('in', $id_str);
        $data = Db::name('officebuilding')->where('id', 'in', $id_str)->order(array('releasetime' => 'DESC'))
            ->paginate(10, false, ['query' => $this->request->param(),//不丢失已存在的url参数
            ]);
        $page = $data->render();
        $recommend = Db::name('officebuilding')
            ->where(["type" => 1])->order(array('releasetime' => 'DESC'))
            ->paginate(10);
        $new = Db::name('officebuilding')->order(array('releasetime' => 'DESC'))
            ->limit(10)->select();
        $hot = Db::name('officebuilding')->where(["type" => 2])
            ->order(array('releasetime' => 'DESC'))->limit(10)->select();

        $this->assign([
            'recommend' => $this->formatOffice($recommend),
            'new' => $this->formatOffice($new),
            'hot' => $this->formatOffice($hot),
            'list' => $this->formatOffice($data),
            'page' => $page,
            'cityInfo' => $cityInfo,
            'areaInfo' => $areaInfo,
            'cityList' => $cityList,
            'empty' => '<span class="empty">没有数据</span>'
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
                'imgs' => $row['imgs'] ? explode(',', $row['imgs'])[0] : '',
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
        $info = Db::name('officebuilding')->where(['id' => $id])->find();
        $cityInfo = Db::name('city')->where('id', 'in', $info['city'])->select();
        $areaInfo = Db::name('area')->where('parentId', 'in', $info['city'])->select();
        $cityList = Db::name('city')->where('id', 'not in', $info['city'])->select();
        $this->assign("cityList", $cityList);

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

}
