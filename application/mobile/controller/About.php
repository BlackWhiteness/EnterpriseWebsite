<?php
namespace app\mobile\controller;

use app\common\controller\MobileBase;
use think\Db;

class About extends MobileBase
{
        //初始化
    protected function initialize()
    {
        parent::initialize();
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

}
