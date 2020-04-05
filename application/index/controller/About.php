<?php
namespace app\index\controller;
use app\admin\model\City as City_Model;
use app\admin\model\Workshop as Workshop_Model;
use app\common\controller\Homebase;
use think\Db;

class About extends Homebase
{
        //初始化
    protected function initialize()
    {
        parent::initialize();
        $this->City_Model = new City_Model;
    }

    //会员中心首页
    public function index()
    {
        $about = Db::table('search_about')->where(['code'=>1,'status'=>0])->find();
        $contact = Db::table('search_about')->where(['code'=>2,'status'=>0])->find();
        $this->assign(compact('about','contact'));
        return $this->fetch();
    }

    public function lx()
    {
        $about = Db::table('search_about')->where(['code'=>1,'status'=>0])->find();
        $contact = Db::table('search_about')->where(['code'=>2,'status'=>0])->find();
        $this->assign(compact('about','contact'));
        return $this->fetch();
    }

    public function map()
    {

        $data = $this->request->param();
        $city = isset($_COOKIE['city']) ? $_COOKIE['city'] : 8;
        $cityInfo = Db::name('city')->where('id', 'in', $city)->select();
        $areaInfo = Db::name('area')->where('parentId', 'in', $city)->select();
        $cityList = Db::name('city')->where('id', 'not in', $city)->select();
        $newworkshop = [];
        $firstcity = Db::name('city')->order(array('id' => 'ASC'))->page(1, 9)->select();
        $recommend = Workshop_Model::where(array('type' => 1))->order(array('releasetime' => 'DESC'))->page(1,
            10)->select()->toArray();
        $this->assign("recommend", $recommend);
        return $this->fetch();
    }
}
