<?php
namespace app\api\controller;

use app\common\controller\Base;
use app\mall\model\Pingjia as Pingjia_Model;
use think\Db;
class Pingjia extends Base
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
        $this->Pingjia_Model = new Pingjia_Model();
        parent::initialize();
    }
	
    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post('');
            if ($this->Pingjia_Model->createPingjia($data)) {
                $this->success("添加成功！", '/index');
            } else {
                $error = $this->order_Model->getError();
                $this->error($error ? $error : '添加失败！');
            }

        } else {
            $this->error('添加失败！', '/index');
        }
    }

}
