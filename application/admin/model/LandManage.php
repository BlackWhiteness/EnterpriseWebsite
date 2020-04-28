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

        $rent = request()->param('rent',0);
        if(!empty($rent)){
            $top = strpos($rent, '-');
            if ($top == false) {
                $query = $query->where('rent', '>=', $rent);
            } else {
                $range = explode('-', $rent);
                $query = $query->where('price', '>=', $range[0])
                    ->where('price', '<', $range[1]);
            }
        }

        $title = request()->param('title');
        if (!empty($title)) {
            $query = $query->where('title', 'like', '%' . $title . '%');
        }
        $type = request()->param('type');
        if (is_numeric($type)&&$type) {
            $query = $query->where('type', '=', $type);
        }
        $tag = request()->param('tag');
        if (!empty($tag)) {
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

    /**
     * 获取第一页
     * @param $city
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getFirstPage($city)
    {
        $list = LandManage::where('city', '=', $city)
            ->order('releasetime', 'desc')
            ->page(1, 5)
            ->select();
        return $list;
    }

    /**
     * 推荐
     *
     * @param $city
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getRecommend($city)
    {
        return LandManage::where(['city'=>$city])->limit(0, 10)->select();
    }

}
