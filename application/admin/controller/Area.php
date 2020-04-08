<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2007 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 御宅男 <530765310@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\Controller;

use app\admin\model\Area as Area_Model;
use app\admin\model\City as City_Model;
use app\common\controller\Adminbase;

/**
 * 权限管理控制器
 */
class Area extends Adminbase
{

    public function index()
    {
        if ($this->request->isAjax()) {
            $result = (new Area_Model())->gets();
            $result = array("code" => 0, "data" => $result);
            return json($result);
        } else {
            return $this->fetch();
        }

    }

    //权限管理首页

    public function getByParentId()
    {
        $id = $this->request->param('id/d');
        if (empty($id)) {
            $this->error('ID错误');
        }
        if ($this->request->isAjax()) {
            $result = $this->Area_Model->where(["parentId" => $id])->select()->toArray();
            $result = array("code" => 0, "data" => $result);
            return json($result);
        } else {
            return $this->fetch();
        }

    }

    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            /**if (!isset($data['status'])) {
             * $data['status'] = 0;
             * } else {
             * $data['status'] = 1;
             * }**/

            //$result = $this->validate($data, 'Menu.add');var_dump($result);exit;
            if (!$data) {
                return $this->error($data);
            }
            if (Area_Model::create($data)) {
                $this->success("添加成功！", url("index"));
            } else {
                $this->error('添加失败！');
            }
        } else {
            $result = $this->City_Model->order(array('id' => 'DESC'))->select()->toArray();
            $citys = '';
            foreach ($result as $r) {
                $citys .= "<option value='" . $r["id"] . "'>  " . $r["name"] . "</option>";
            }

            $this->assign("citys", $citys);//var_dump($citys);exit;
            return $this->fetch();
        }
    }

    //添加后台菜单

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
            if (Area_Model::update($data)) {
                $this->success("编辑成功！", url("index"));
            } else {
                $this->error('编辑失败！');
            }
        } else {
            $id = $this->request->param('id/d', '');
            $area = Area_Model::where(["id" => $id])->find()->toArray();
            $this->assign("area", $area);
            $result = $this->City_Model->order(array('id' => 'DESC'))->select()->toArray();
            $citys = '';
            foreach ($result as $r) {
                $selected = $r['id'] == $area['parentId'] ? 'selected' : '';
                $citys .= "<option value='" . $r["id"] . "' " . $selected . "> " . $r['name'] . "</option>";
            }

            $this->assign("citys", $citys);
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
        $Area = new Area_Model();
        if ($Area->del($id) !== false) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }

    protected function initialize()
    {
        parent::initialize();
        $this->Area_Model = new Area_Model();
        $this->City_Model = new City_Model();
    }

}
