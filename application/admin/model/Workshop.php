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

/**
 * Class Workshop
 * @package app\admin\model
 */
class Workshop extends Model
{
    protected $pk = 'id';
    protected $table = 'search_workshop';
    const CATEGORY_CONFIG = [
        1 => '厂房出租',
        2 => '厂房出售',
        3 => '仓库出租'
    ];

    const STRUCT_CONFIG = [
        1 => '标准',
        2 => '钢构结构',
        3 => '简易(铁皮房)',
        4 => '各类型仓库(单层仓库/多层仓库)'
    ];

    protected $type = [
        'imgs' => 'serialize',
    ];

    /**
     * 对应一个城市
     * @return \think\model\relation\BelongsTo
     */
    public function belongsToOneCity()
    {
        return $this->belongsTo('City','city','id');
    }
    /**
     * 对应一个地区
     * @return \think\model\relation\BelongsTo
     */
    public function belongsToOneArea()
    {
        return $this->belongsTo('Area','area','id');
    }

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
        if (!empty($measurearea)) {
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

    /**
     * 第一页
     * @param $city
     * @param $type
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getFirstPage($city, $type)
    {
        $list = Workshop::where('city', '=', $city)
            ->order('releasetime', 'desc')
            ->where('category', '=', $type)
            ->page(1, 5)
            ->select();
        return $list;
    }

    //--------------------------以下为mobile合并过来的
    public function getFloorAttr($value)
    {
        $floor = [1 => '一楼厂房', 2 => '二楼以上', 3 => '独院厂房', 4 => '独栋厂房'];
        return $floor[$value];
    }

    public function getStructureAttr($value)
    {
        $structure = [1 => '标准厂房 ', 2 => '钢构结构厂房', 3 => '简易厂房(铁皮房) ', 4 => '各类型仓库(单层仓库/多层仓库)'];
        return $structure[$value];
    }

    public function getAddAttr($value, $data)
    {
        return getCityName($data['city'], $data['area']);
    }


    public function getCityNameAttr($value, $data)
    {
        return getCityName($data['city']);
    }

    public function getUrlAttr($value, $data)
    {
        return url('index/search/workshopdetail', 'id=' . $data['id']);
    }

}
