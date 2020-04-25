<?php

namespace app\admin\model;

use think\Model;


/**
 * 招商模型类
 *
 * @package app\admin\model
 */
class ParkManage extends Model
{
    const TYPE_CONFIG = [
        1 => '推荐',
        2 => '热门'
    ];
    protected $pk = 'id';
    protected $table = 'search_park';
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
     * 获取列表
     *
     * @return \think\db\Query|\think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList()
    {
        $query = $this->filterCommon();
        $query = $query->paginate(request()->param('per_page', 20));
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

        $query = $query->order(array('releasetime' => 'DESC'));

        return $query;
    }

    /**
     * 第一页
     *
     * @param $city
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getFirstPage($city)
    {
        $list = self::where('city', '=', $city)
            ->order('releasetime', 'desc')
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
        return self::where('city', '=', $city)
            ->page(1, 10)->select();
    }

}
