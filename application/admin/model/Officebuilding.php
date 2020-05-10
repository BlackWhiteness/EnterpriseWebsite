<?php

namespace app\admin\model;

use think\Model;

/**
 * Class Officebuilding
 * @package app\admin\model
 */
class Officebuilding extends Model
{
    const TAG_CONFIG = [
        0 => '不限',
        1 => '可注册',
        2 => '整栋',
        3 => '可分割',
        4 => '带装修',
    ];
    const FLOOR_CONFIG = [
        0 => '不限',
        1 => '一楼',
        2 => '楼上',
        3 => '独栋',
        4 => '独院',
    ];
    const SALE_LIST = [
        0 => '不限',
        1 => '出租',
        2 => '出售',
    ];
    const INDUS_TYPE = [
        0 => '不限',
        1 => '纯写字楼',
        2 => '商业综合体',
        3 => '商务公寓',
        4 => '商务酒店',
        5 => '厂房办公'
    ];
    protected $pk = 'id';
    protected $table = 'search_officebuilding';
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
     * 获取写字楼列表
     * @return \think\db\Query|\think\Paginator
     * @throws \think\exception\DbException
     */
    public function getOfficeBuild()
    {
        $query = $this->filterCommon()->order('type', 'desc')
            ->order('releasetime','desc');
        $query = $query->paginate(20);
        return $query;
    }

    /**
     * 通用过滤
     * @return \think\db\Query
     */
    public function filterCommon()
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

        $indusType = request()->param('indus_type');
        if (!empty($indusType)) {
            $query = $query->where('indus_type', '=', $indusType);
        }
        $tag = request()->param('tag');
        if (!empty($tag)) {
            $query = $query->where('tag', '=', $tag);
        }
        $floorType = request()->param('floor_type');
        if (!empty($floorType)) {
            $query = $query->where('floor_type', '=', $floorType);
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
        $category = request()->param('category');

        if (!empty($category)) {
            $query = $query->where('category', '=', $category);
        }

        $title = request()->param('title');
        if (!empty($title)) {
            $query = $query->where('title', 'like', '%' . $title . '%');
        }


        return $query;
    }

    /**
     * 第一页
     * @param $city
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getFirstPage($city)
    {
        $list = Officebuilding::where('city', '=', $city)
            ->order('releasetime', 'desc')
            ->page(1, 5)
            ->select();
        return $list;
    }

    /**
     * 推荐
     *
     * @param $id
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getRecommend($id)
    {
        return Officebuilding::where(["area" => $id])
            ->page(1, 5)->select();
    }
}
