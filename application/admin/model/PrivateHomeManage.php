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

use think\Model;

/**
 * Class Workshop
 * @package app\admin\model
 */
class PrivateHomeManage extends Model
{
    //1出租 2出售
    const CATEGORY_CONFIG = [
        1 => '出租',
        2 => '出售',
    ];

    const STRUCT_CONFIG = [
        1 => '钢混结构',
        2 => '钢结构',
        3 => '简易结构',
    ];
    //楼层类型 1:一层 2:2层 3:3层 4:4层 5:5层以上
    const FLOOR_TYPE = [
        1 => '一层',
        2 => '二层',
        3 => '三层',
        4 => '四层',
        5 => '五层以上',
    ];

    const TYPE_CONFIG = [
        1 => '推荐',
        2 => '热门'
    ];

    const MEASURE_LIST = [
        '0-50' => '50平米以下',
        '50-100' => '50-100平米',
        '100-200' => '100-200平米',
        '200-300' => '200-300平米',
        '300-400' => '300-400平米',
        '400-500' => '400-500平米',
        '600' => '600平米以上',
    ];
    protected $pk = 'id';
    protected $table = 'search_private_home';
    protected $type = [
        'imgs' => 'serialize',
    ];

    /**
     * 对应一个城市
     * @return \think\model\relation\BelongsTo
     */
    public function belongsToOneCity()
    {
        return $this->belongsTo('City', 'city', 'id');
    }

    /**
     * 对应一个地区
     * @return \think\model\relation\BelongsTo
     */
    public function belongsToOneArea()
    {
        return $this->belongsTo('Area', 'area', 'id');
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
     * @return \think\db\Query|\think\Paginator
     * @throws \think\exception\DbException
     */
    public function getWorkShopBySearch()
    {
        $query = $this->filterComon();
        $query = $query->paginate(5);
        return $query;
    }

    /**
     * 通用过滤
     * @return \think\db\Query
     */
    public function filterComon()
    {
        $query = self::getModel();
        $city = getCity();
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
        $floorType = request()->param('floor_type');
        if (is_numeric($floorType) && $floorType) {
            $query = $query->where('floor_type', '=', $floorType);
        }

        $structure = request()->param('structure');
        if (is_numeric($structure) && $structure) {
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
        $list = self::where('city', '=', $city)
            ->order('releasetime', 'desc')
            ->where('category', '=', $type)
            ->page(1, 5)
            ->select();
        return $list;
    }

    /**
     * 获取推荐
     *
     * @param $city
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getRecommend($city)
    {
        return PrivateHomeManage::where(["city" => $city])
            ->page(1, 5)
            ->select();
    }

}
