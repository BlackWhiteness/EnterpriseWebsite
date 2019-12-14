<?php
namespace app\mobile\controller;
use app\admin\model\CustomerDemand;
use app\admin\model\OwnerDemand;
use app\common\controller\Homebase;
use app\index\validate\Customer;
use \think\Db;
class Publish extends Homebase
{
        //初始化
    protected function initialize()
    {
        parent::initialize();
    }

    //会员中心首页
    public function owner()
    {
        if($this->request->isAjax())
        {
            $para = $this->request->post();
            if(OwnerDemand::create($para))
            {
                return 1;
            }else{
                return 2;
            }
        }

        $recommend = Db::name('workshop')->order(array('releasetime' => 'DESC'))->paginate(1);
    $this->assign("recommend", $recommend);
        return $this->fetch("toufang");
    }
    public function customer()
    {
        if($this->request->isAjax())
        {
            $para= $this->request->post();
            $CustomerValidate = new Customer();
            $result = $CustomerValidate->check($para);
            if(!$result)
            {
                return $this->error($CustomerValidate->getError());
            }
            if (CustomerDemand::create($para)) {
               return 1;
            } else {
                return 2;
            }
        }
        $recommend = Db::name('workshop')->order(array('releasetime' => 'DESC'))->paginate(1);
    $this->assign("recommend", $recommend);
        return $this->fetch("customer");
    }

}
