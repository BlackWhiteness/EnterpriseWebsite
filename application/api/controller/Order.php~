<?php
namespace app\api\controller;

use app\common\controller\Base;
use app\mall\model\Order as order_Model;
use think\Db;
class Order extends Base
{
    /**
     * 初始化
     */
    protected function initialize()
    {
error_reporting(0);
//报告运行时错误
error_reporting(E_ERROR | E_WARNING | E_PARSE);
//报告所有错误
error_reporting(E_ALL);
        $this->order_Model = new order_Model();
        parent::initialize();
    }
	
    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post('');
            if ($this->order_Model->createOrder($data)) {
                $this->success("添加成功！", '/index');
            } else {
                $error = $this->order_Model->getError();
                $this->error($error ? $error : '添加失败！');
            }

        } else {
            $this->error('添加失败！', '/index');
        }
    }

public function list(){
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

}
