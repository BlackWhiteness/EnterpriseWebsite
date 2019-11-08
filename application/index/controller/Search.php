<?php

namespace app\index\controller;

use app\common\controller\Homebase;
use app\admin\model\Workshop as Workshop_Model;
use app\search\model\Workshopsearch as Workshopsearch;
use app\search\model\Offbuildingsearch as Offbuildingsearch;
use app\admin\model\City;
use app\admin\model\Area;
use \think\Db;

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
        $data = $this->request->param();

        $city = isset($_COOKIE['city']) ? $_COOKIE['city'] : 8;
        $cityList = Db::name('city')->where('id', 'not in', $city)->select();
        $cityInfo = Db::name('city')->where('id', 'in', $city)->select();
        $areaInfo = Db::name('area')->where('parentId', 'in', $city)->select();
        $keywords = 1;

        $d = $this->Workshopsearch->searchDoc($data, 1, 10);
        $ids = array();
        if (count($d['hits']['hits'])) {
            foreach ($d['hits']['hits'] as $value) {
                array_push($ids, $value['_id']);
            }
        }

        $id_str = implode(',', $ids);
        $where['id'] = array('in', $id_str);
        $list = Db::name('workshop')->where('id', 'in', $id_str)->order(array('releasetime' => 'DESC'))->paginate(2, false, [
            'query' => $this->request->param(),//不丢失已存在的url参数
        ]);
        $this->assign("cityInfo", $cityInfo);
        $this->assign("areaInfo", $areaInfo);
        $this->assign("cityList", $cityList);

        $this->assign("list", $list);

        $recommend = Db::name('workshop')->where(["type" => 1])->order(array('releasetime' => 'DESC'))->paginate(10);
        $this->assign("recommend", $recommend);
        $floor1 = Db::name('workshop')->where(["floor" => 1])->order(array('releasetime' => 'DESC'))->paginate(10);
        $this->assign("floor1", $floor1);
        $floor2 = Db::name('workshop')->where(["floor" => 2])->order(array('releasetime' => 'DESC'))->paginate(10);
        $this->assign("floor2", $floor2);
        $floor3 = Db::name('workshop')->where(["floor" => 3])->order(array('releasetime' => 'DESC'))->paginate(10);
        $this->assign("floor3", $floor3);
        return $this->fetch('rentalofworkshop');
    }

    public function workshopdetail()
    {
        $id = $this->request->param('id/d', '');
        $info = Db::name('workshop')->where(['id' => $id])->find();//var_dump($info);exit;
        $cityInfo = Db::name('city')->where('id', 'in', $info['city'])->select();
        $areaInfo = Db::name('area')->where('parentId', 'in', $info['city'])->select();
        $cityList = Db::name('city')->where('id', 'not in', $info['city'])->select();
        $this->assign("cityList", $cityList);

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

        $list = [];
        foreach ($data as $row) {
            $list[] = [
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
        $page = $data->render();
        $recommend = Db::name('officebuilding')
            ->where(["type" => 1])->order(array('releasetime' => 'DESC'))
            ->paginate(10);
        $new = Db::name('officebuilding')->order(array('releasetime' => 'DESC'))->paginate(10);
        $hot = Db::name('officebuilding')->where(["type" => 2])
            ->order(array('releasetime' => 'DESC'))->paginate(10);
        $this->assign([
            'recommend' => $recommend,
            'new' => $new,
            'hot' => $hot,
            'list' => $list,
            'page' => $page,
            'cityInfo' => $cityInfo,
            'areaInfo' => $areaInfo,
            'cityList' => $cityList
        ]);
        return $this->fetch('officebuilding');

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
}
