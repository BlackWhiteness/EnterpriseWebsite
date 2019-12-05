<?php


namespace app\admin\controller;


use app\admin\model\City as City_Model;
use app\admin\model\Area as Area_Model;
use app\admin\model\ShopManage;
use app\common\controller\Adminbase;
use app\format\LandFormat;
use app\format\ShopFormat;
use think\Request;


/**
 * Class Shop
 * @package app\admin\controller
 */
class Shop extends Adminbase
{
    public $City_Model;
    public $Area_Model;

    /**
     *
     */
    protected function initialize()
    {
        parent::initialize();
        $this->City_Model = new City_Model();
        $this->Area_Model = new Area_Model();
    }


    /**
     * 首页
     * @param Request $request
     * @return mixed|\think\response\Json
     * @throws \think\exception\DbException
     */
    public function index(Request $request)
    {
        if ($this->request->isAjax()) {
            $result = ShopManage::order(array('id' => 'DESC'))
                ->paginate($request->param('limit'));
            $total = $result->total();
            $result = array("code" => 0, "count" => $total,
                "data" => ShopFormat::getInstance()->formatList($result->items()));
            return json($result);
        }
        return $this->fetch();
    }

    /**
     * 后台添加厂房
     * @param Request $request
     * @return mixed|void
     */
    public function add(Request $request)
    {
        if ($request->isPost()) {
            $data = $request->param();
            if (!$data) {
                return $this->error($data);
            }
            $result = ShopManage::create($data);
            if (intval($result->id)) {
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
            $this->assign("citys", $citys);
            return $this->fetch();
        }
    }

    /**
     *编辑后台菜单
     * @param Request $request
     * @return mixed|void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function edit(Request $request)
    {
        if ($request->isPost()) {
            $data = $request->param();
            if (!isset($data['status'])) {
                $data['status'] = 0;
            } else {
                $data['status'] = 1;
            }
            if (!$data) {
                return $this->error($data);
            }
            if (ShopManage::update($data)) {
                $this->success("编辑成功！", url("index"));
            } else {
                $this->error('编辑失败！');
            }
        } else {
            $id = $request->param('id/d', '');
            $rs = ShopManage::where(["id" => $id])->find();
            $this->assign("data", $rs);
            $result = $this->City_Model->order(array('id' => 'DESC'))->select()->toArray();
            $citys = '';
            foreach ($result as $r) {
                $citys .= "<option value='" . $r["id"] . "' " . ($r['id'] == $rs['city'] ? 'checked' : '') . " >  " . $r["name"] . "</option>";
            }
            $result = $this->Area_Model->where(array("parentId" => $rs['city']))->order(array('id' => 'DESC'))->select()->toArray();
            $areas = '';
            foreach ($result as $r) {
                $areas .= "<option value='" . $r["id"] . "' " . ($r['id'] == $rs['area'] ? 'checked' : '') . " >  " . $r["name"] . "</option>";
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
        $workshop = new LandManage();
        if ($workshop->del($id) !== false) {

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
        $rs = ShopManage::update(['id' => $id, 'listorder' => $listorder]);
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
        if (ShopManage::update(['status' => $status], ['id' => $id])) {
            $this->success('操作成功！');
        } else {
            $this->error('操作失败！');
        }
    }

}
