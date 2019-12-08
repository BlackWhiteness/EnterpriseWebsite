<?php

namespace app\admin\model;

use app\admin\model\AuthRule;
use app\admin\service\User;
use \think\Db;
use \think\Model;

/**
 * 土地
 * Class LandModel
 * @package app\admin\model
 */
class LandManage extends Model
{
    protected $pk = 'id';
    protected $table = 'search_land';

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
                $query = $query->where('measurearea', '>=', $measurearea);
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
        $type = request()->param('type');
        if (is_numeric($type)) {
            $query = $query->where('type', '=', $type);
        }
        $tag = request()->param('tag');
        if (is_numeric($tag)) {
            $query = $query->where('tag', 'like', '%' . $tag . '%');
        }
        $query = $query->order(array('releasetime' => 'DESC'));

        return $query;
    }

    /**
     * 获取写字楼列表
     * @return \think\db\Query|\think\Paginator
     * @throws \think\exception\DbException
     */
    public function getLandSearch()
    {
        $query = $this->filterCommon();
        $query = $query->paginate(20);
        return $query;
    }

}
