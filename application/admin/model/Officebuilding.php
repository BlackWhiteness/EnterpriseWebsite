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
// | 后台菜单模型
// +----------------------------------------------------------------------
namespace app\admin\model;

use app\admin\model\AuthRule;
use app\admin\service\User;
use \think\Db;
use \think\Model;

class Officebuilding extends Model
{
    protected $type = [
        'imgs' => 'serialize',
    ];

    public function del($id)
    {
        $id = (int)$id;
        if (empty($id)) {
            $this->error = '请指定需要删除的ID！';
            return false;
        }
        if (false !== $this->where(array('id' => $id))->delete()) {
            return true;
        } else {
            $this->error = '删除失败！';
            return false;
        }
    }
}
