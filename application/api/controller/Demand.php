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
namespace app\api\controller;

use app\common\controller\Base;
use app\admin\model\OwnerDemand;
use app\admin\model\CustomerDemand;

use think\Db;

class Demand extends Base
{
    //添加后台菜单
    public function owner()
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
                $this->success("添加成功！", url("/index/publish/owner"));
            } else {
                $this->error('添加失败！');
            }
        } else {
            return $this->fetch();
        }
    }

    public function customer()
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
            if (CustomerDemand::create($data)) {
                $this->success("添加成功！", url("/index/publish/customer"));
            } else {
                $this->error('添加失败！');
            }
        } else {
            return $this->fetch();
        }
    }


}
