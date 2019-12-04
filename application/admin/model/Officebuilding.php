<?php

namespace app\admin\model;

use app\admin\model\AuthRule;
use app\admin\service\User;
use \think\Db;
use \think\Model;

/**
 * Class Officebuilding
 * @package app\admin\model
 */
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

    /**
     * 通用过滤
     * @return \think\db\Query
     */
    public function filterCommon()
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
        if (!empty($measurearea)) {
            $top = strpos($measurearea, '-');
            if ($top == false) {
                $query = $query->where('measurearea', '>=', 1000);
            } else {
                $range = explode('-', $measurearea);
                $query = $query->where('measurearea', '>=', $range[0])
                    ->where('measurearea', '<', $range[1]);
            }
        }

        $title = request()->param('title');
        if (!empty($title)) {
            $query = $query->where('title', 'like', '%' . $title . '%');
        }

        $query = $query->order(array('releasetime' => 'DESC'));

        return $query;
    }

    /**
     * 获取写字楼列表
     * @return \think\db\Query|\think\Paginator
     * @throws \think\exception\DbException
     */
    public function getOfficeBuild()
    {
        $query = $this->filterCommon();
        $query = $query->paginate(20);
        return $query;
    }
}
