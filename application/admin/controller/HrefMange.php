<?php

namespace app\admin\controller;

use app\admin\model\HrefManage;
use app\common\controller\Adminbase;
use think\Request;


/**
 * 外链管理控制类
 * Class HrefMange
 * @package app\admin\controller
 */
class HrefMange extends Adminbase
{

    protected function initialize()
    {
        parent::initialize();
    }

    /**
     * 后台菜单首页
     * @param Request $request
     * @return mixed|\think\response\Json
     * @throws \think\exception\DbException
     */
    public function index(Request $request)
    {
        if ($this->request->isAjax()) {
            $result = HrefManage::order(array('sort' => 'DESC'))->
                paginate($request->param('limit'));
            $total = $result->total();
            $result = array("code" => 0, "count" => $total, "data" => $result->items());
            return json($result);
        }
        return $this->fetch();
    }

    /**
     * 后台添加厂房
     * @param \think\Request $request
     * @return mixed|void
     */
    public function add(\think\Request $request)
    {
        if ($request->isPost()) {
            $data = $request->param();

            if (empty($data['title']) || empty($data['href'])) {
                $this->error('参数错误！');
            }
            $data['sort'] = (int)$data['sort'];
            $result = HrefManage::create($data);
            if (intval($result->id)) {
                $this->success("添加成功！", url("index"));
            } else {
                $this->error('添加失败！');
            }
        } else {
            return $this->fetch();
        }
    }

    /**
     * 编辑后台菜单
     * @param \think\Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function edit(\think\Request $request)
    {
        if ($request->isPost()) {
            $data = $request->param();

            if (empty($data['title']) || empty($data['href'])) {
                $this->error('参数错误！');
            }
            $data['sort'] = (int)$data['sort'];
            $result = HrefManage::update($data);
            if (intval($result->id)) {
                $this->success("修改成功！", url("index"));
            } else {
                $this->error('修改失败！');
            }
        } else {
            $id = $this->request->param('id/d', '');
            $rs = HrefManage::where(["id" => $id])->find();

            $this->assign("data", $rs);
            $this->assign("id", $id);
            return $this->fetch();
        }

    }

    /**
     * 删除
     * @param \think\Request $request
     */
    public function del(\think\Request $request)
    {
        $id = $request->param('id/d');
        if (empty($id)) {
            $this->error('ID错误');
        }
        $status = HrefManage::destroy($id);
        if ($status) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }

}

