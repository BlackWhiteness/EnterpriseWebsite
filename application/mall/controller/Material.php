<?php
namespace app\mall\controller;

use app\admin\model\AdminUser as Admin_User;
use app\mall\model\Material as Material_Model;
use app\common\controller\Adminbase;
use think\Db;

/**
 * 管理员管理
 */
class Material extends Adminbase
{
    protected function initialize()
    {
        parent::initialize();
        $this->Admin_User = new Admin_User;
    }

    /**
     * 管理员管理列表
     */
    public function index()
    {
         $info = Material_Model::get(1);
         $this->assign([
                'id' => $info['id'],
                'type' => $info['type'],
                'url' => $info['url'],
		'store' => $info['store'],
         ]);
        return $this->fetch();
    }

    /**
     * 管理员编辑
     */
    public function edit()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if (Material_Model::update($data)) {
                $this->success('编辑成功~', url('index',['menuid'=>59]));
            } else {
                $this->error('编辑失败！');
            }
        } else {
$this->error('编辑失败！');
}

    }
}
