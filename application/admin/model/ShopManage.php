<?php

namespace app\admin\model;

use app\admin\model\AuthRule;
use app\admin\service\User;
use \think\Db;
use \think\Model;

/**
 * 商铺
 * Class ShopManage
 * @package app\admin\model
 */
class ShopManage extends Model
{
    protected $pk = 'id';
    protected $table = 'search_shop';

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
