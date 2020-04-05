<?php

namespace app\admin\controller;


use app\admin\model\AboutManage;
use app\common\controller\Adminbase;
use think\Db;
use think\Request;


/**
 * Class About
 * @package app\admin\controller
 */
class About extends Adminbase
{
    public function index(Request $request)
    {
        if ($this->request->isAjax()) {
            $result = Db::table('search_about')
                ->where('status', '=', 0)
                ->order(['id' => 'asc'])
                ->paginate($request->param('limit'));
            $total = $result->total();
            $rtn=[];
            foreach ($result as $row) {
                $rtn[] = [
                    'id' => $row['id'],
                    'title'=> $row['title'],
                    'code'=> $row['code'],
                    'update_at' => date('Y-m-d H:i:s')
                ];
            }
            $result = array("code" => 0, "count" => $total,
                "data" => $rtn);
            return json($result);
        }
        return $this->fetch();

    }

    public function edit(Request $request)
    {

        if ($request->isPost()) {
            $data = $request->param();

            $data['update_at'] = time();
            if (!$data) {
                return $this->error($data);
            }
            if (AboutManage::where('code','=',$data['code'])->update($data)) {
                $this->success("编辑成功！", url("index"));
            } else {
                $this->error('编辑失败！');
            }
        }else {
            $code = $request->param('code', '');

            $rs = AboutManage::where(["code" => $code])->find();
            $this->assign("data", $rs);

            $this->assign("code", $code);
            return $this->fetch();
        }

    }

    /**
     *
     */
    protected function initialize()
    {
        parent::initialize();
    }
}
