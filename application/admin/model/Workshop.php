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

class Workshop extends Model
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

    /**
     * 通用过滤
     * @return \think\db\Query
     */
    public function filterComon()
    {
        $query = self::getModel();
        $city = request()->param('city');

        if (!empty($city)) {
            $query = $query->where('city', '=', $city);
        }

        $area = request()->param('area');
        if (!empty($area)) {
            $query = $query->where('area', '=', $area);
        }
        $measurearea = request()->param('measurearea');
        if (empty($measurearea)) {
            $top = strpos($measurearea, '-');
            if ($top == false) {
                $query = $query->where('measurearea', '>=', 10000);
            } else {
                $range = explode('-', $measurearea);
                $query = $query->where('measurearea', '>=', $range[0])
                    ->where('measurearea', '<', $range[1]);
            }
        }
        $floor = request()->param('floor');
        if (is_numeric($floor)) {
            $query = $query->where('floor', '=', $floor);
        }

        $structure = request()->param('structure');
        if (is_numeric($structure)) {
            $query = $query->where('structure', '=', $structure);
        }
        $title = request()->param('title');
        if (!empty($title)) {
            $query = $query->where('title', 'like', '%' . $title . '%');
        }

        $category = request()->param('category');
        if (is_numeric($category)) {
            $query = $query->where('category', '=', $category);
        }

        $query = $query->order(array('releasetime' => 'DESC'));

        return $query;
    }

    /**
     * @return \think\db\Query|\think\Paginator
     * @throws \think\exception\DbException
     */
    public function getWorkShopBySearch()
    {
        $query = $this->filterComon();
        $query = $query->paginate(20);
        return $query;
    }

}
