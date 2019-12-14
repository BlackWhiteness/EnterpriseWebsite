<?php
namespace app\mobile\controller;
use app\admin\model\Workshop as Workshop_Model;
use app\common\controller\MobileBase;
use app\admin\model\Workshop;
use app\search\model\Workshopsearch as Workshopsearch;
use app\search\model\Offbuildingsearch as Offbuildingsearch;
use app\admin\model\City;
use app\admin\model\Area;
use \think\Db;
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

    //会员中心首页
    public function workshop()
    {    
        $data = $this->request->param();
        $city = isset($data['city'])?$data['city']:6;
        $type = isset($data['type'])?$data['type']:1;
        $page = isset($data['wTem']['p'])?$data['wTem']['p']:1;
        $cityInfo = Db::name('city')->where('id','in', $city)->select();
        $areaInfo = Db::name('area')->where('parentId','in', $city)->select();
        $keywords = 1;
        $d = $this->Workshopsearch->searchDoc($data,$page, 10);
        $ids = array();
        if(count($d['hits']['hits'])){
            foreach ($d['hits']['hits'] as $value) {
                array_push($ids, $value['_id']);
            }
        }
        $id_str = implode(',', $ids);
        $where['id'] = array('in', $id_str);
        $list = (new Workshop)->where('id','in', $id_str)->append(['city_name','add','url'])->order(array('releasetime' => 'DESC'))->paginate(10, false, [
                'query' => $this->request->param(),'page' => $this->request->param('wTem.p')//不丢失已存在的url参数
            ])->toArray();

        if($this->request->isAjax()){
           exit(json_encode(array('count'=>count($list['data']),'list'=>$list['data'])));
        }
        $this->assign("cityInfo", $cityInfo);
        $this->assign("areaInfo", $areaInfo);
        $this->assign('city',$city);
        $this->assign('type',$type);
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
        $info = Db::name('workshop')->where(['id'=> $id])->find();
        $cityInfo = Db::name('city')->where('id','in', $info['city'])->select();
        $areaInfo = Db::name('area')->where('parentId','in', $info['city'])->select();
        
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
        $city = isset($data['city'])?$data['city']:8;
        $cityInfo = Db::name('city')->where('id','in', $city)->select();
        $areaInfo = Db::name('area')->where('parentId','in', $city)->select();
        $keywords = 1;
        $d = $this->Offbuildingsearch->searchDoc($data,1, 10);
        $ids = array();
        if(count($d['hits']['hits'])){
            foreach ($d['hits']['hits'] as $value) {
                array_push($ids, $value['_id']);
            }
        }
        $id_str = implode(',', $ids);
        $where['id'] = array('in', $id_str);
        $list = Db::name('officebuilding')->where('id','in', $id_str)->order(array('releasetime' => 'DESC'))->paginate(2, false, [
                'query' => $this->request->param(),//不丢失已存在的url参数
            ]);
        $this->assign("cityInfo", $cityInfo);
        $this->assign("areaInfo", $areaInfo);

        $this->assign("list", $list);
        $recommend = Db::name('officebuilding')->where(["type" => 1])->order(array('releasetime' => 'DESC'))->paginate(10);
        $this->assign("recommend", $recommend);
        $new = Db::name('officebuilding')->order(array('releasetime' => 'DESC'))->paginate(10);
        $this->assign("new", $new);
        $hot = Db::name('officebuilding')->where(["type" => 2])->order(array('releasetime' => 'DESC'))->paginate(10);
        $this->assign("hot", $hot);
        
        return $this->fetch('officebuilding');
        
    }

    public function createUrl()
    {
        $param = $this->request->param();
        exit(url('index/search/workshop',$param));
    }

    public function offbuilddetail()
    { 
        $id = $this->request->param('id/d', '');
        $info = Db::name('officebuilding')->where(['id'=> $id])->find();
        $cityInfo = Db::name('city')->where('id','in', $info['city'])->select();
        $areaInfo = Db::name('area')->where('parentId','in', $info['city'])->select();
        
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
