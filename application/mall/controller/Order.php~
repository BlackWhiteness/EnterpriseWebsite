<?php
namespace app\mall\controller;

use app\admin\model\AdminUser as Admin_User;
use app\mall\model\Order as order_Model;
use app\common\controller\Adminbase;
use think\Db;

/**
 * 管理员管理
 */
class Order extends Adminbase
{
    protected function initialize()
    {
        parent::initialize();
        $this->order_Model = new order_Model;
    }

    /**
     * 管理员管理列表
     */
    public function index()
    {
       
        if ($this->request->isAjax()) {
            $limit = $this->request->param('limit/d', 10);
            $page = $this->request->param('page/d', 1);
            $data = $this->order_Model
                ->page($page, $limit)
                ->order('id', 'desc')
                ->select();
            $total = $this->order_Model->order('id', 'desc')->count();
            $result = array("code" => 0, "count" => $total, "data" => $data);//var_dump($result);exit;
            return json($result);
        }
        return $this->fetch();
    }

}
