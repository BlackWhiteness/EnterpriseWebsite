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

        $struck = request()->param('struck');
        if (!empty($struck)) {
            $query = $query->where('struck', '=', $struck);
        }
        $floorType = request()->param('floor_type');
        if (!empty($floorType)) {
            $query = $query->where('floor_type', '=', $floorType);
        }
        $isSale = request()->param('is_sale');

        if(!empty($isSale)){
            $query = $query->where('is_sale', '=' . $isSale);
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
    public function getShopBuild()
    {
        $query = $this->filterCommon();
        $query = $query->paginate(20);
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
        $list = ShopManage::where('city', '=', $city)
            ->order('releasetime', 'desc')
            ->page(1, 5)
            ->select();
        return $list;
    }

}
