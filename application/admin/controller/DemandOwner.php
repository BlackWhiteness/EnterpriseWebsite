<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 御宅男 <530765310@qq.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 后台菜单管理
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\model\CustomerDemand;
use app\admin\model\OwnerDemand;
use app\common\controller\Adminbase;
use app\admin\model\City as City_Model;
use app\admin\model\Area as Area_Model;

class DemandOwner extends Adminbase
{
    protected function initialize()
    {
        parent::initialize();
        $this->City_Model = new City_Model();
        $this->Area_Model = new Area_Model();
    }

    //后台菜单首页
    public function index()
    {
        if ($this->request->isAjax()) {
            $result = OwnerDemand::order(array('id' => 'DESC'))->select()->toArray();
            $total = count($result);
            $result = array("code" => 0, "count" => $total, "data" => $result);
            return json($result);
        }
        return $this->fetch();

    }

    //添加后台菜单
    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            /**if (!isset($data['status'])) {
                $data['status'] = 0;
            } else {
                $data['status'] = 1;
            }**/

            //$result = $this->validate($data, 'Menu.add');var_dump($result);exit;
            if (!$data) {
                return $this->error($data);
            }
            if (OwnerDemand::create($data)) {
                $this->success("添加成功！", url("index"));
            } else {
                $this->error('添加失败！');
            }
        } else {

            $result = $this->City_Model->order(array('id' => 'DESC'))->select()->toArray();
            $citys = '';
            foreach ($result as $r) {
                $citys .= "<option value='". $r["id"] ."'>  ".$r["name"]."</option>";
            }

            $this->assign("citys", $citys);//var_dump($citys);exit;
            return $this->fetch();
        }
    }

    /**
     *编辑后台菜单
     */
    public function edit()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            if (!isset($data['status'])) {
                $data['status'] = 0;
            } else {
                $data['status'] = 1;
            }
            //$result = $this->validate($data, 'Menu.edit');
            if (!$data) {
                return $this->error($result);
            }
            if (OwnerDemand::update($data)) {
                $this->success("编辑成功！", url("index"));
            } else {
                $this->error('编辑失败！');
            }
        } else {
            $id = $this->request->param('id/d', '');
            $rs = OwnerDemand::where(["id" => $id])->find();
            $result = OwnerDemand::order(array('id' => 'DESC'))->select()->toArray();
            $this->assign("data", $rs);
            $this->assign("select_categorys", $result);
            $result = $this->City_Model->order(array('id' => 'DESC'))->select()->toArray();
            $citys = '';
            foreach ($result as $r) {
                $citys .= "<option value='". $r["id"] ."' ". ($r['id'] == $rs['city'] ? 'checked' : '') ." >  ".$r["name"]."</option>";
            }
            $result = $this->Area_Model->where(array("parentId"=>$rs['city']))->order(array('id' => 'DESC'))->select()->toArray();
            $areas = '';
            foreach ($result as $r) {
                $areas .= "<option value='". $r["id"] ."' ". ($r['id'] == $rs['area'] ? 'checked' : '') ." >  ".$r["name"]."</option>";
            }
            $this->assign("id", $id);
            $this->assign("citys", $citys);
            $this->assign("areas", $areas);
            return $this->fetch();
        }

    }

    /**
     * 菜单删除
     */
    public function del()
    {
        $id = $this->request->param('id/d');
        if (empty($id)) {
            $this->error('ID错误');
        }
	$Demand= new OwnerDemand();
        if ($Demand->del($id) !== false) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }

    /**
     * 菜单排序
     */
    public function listorder()
    {
        $id = $this->request->param('id/d', 0);
        $listorder = $this->request->param('value/d', 0);
        $rs = OwnerDemand::update(['id' => $id, 'listorder' => $listorder]);
        if ($rs) {
            $this->success("菜单排序成功！");
        } else {
            $this->error("菜单排序失败！");
        }
    }

    /**
     * 菜单状态
     */
    public function setstate()
    {
        $id = $this->request->param('id/d');
        empty($id) && $this->error('参数不能为空！');
        $status = $this->request->param('status/d');
        if (OwnerDemand::update(['status' => $status], ['id' => $id])) {
            $this->success('操作成功！');
        } else {
            $this->error('操作失败！');
        }
    }

}
