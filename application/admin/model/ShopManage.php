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

    const FLOOR_CONFIG = [
        0=>'不限',
        1=>'一楼',
        2=>'独栋',
        3=>'独院',
    ];
    const STRUCK_CONFIG = [
        0=>'不限',
        1=>'钢混结构',
        2=>'钢结构',
        3=>'简易结构',
    ];

    const SALE_CONFIG = [
        -1 => '不限',
        0 => '出租',
        1 => '出售',
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


        return $query;
    }

    /**
     * 获取写字楼列表
     * @return \think\db\Query|\think\Paginator
     * @throws \think\exception\DbException
     */
    public function getShopBuild()
    {
        $query = $this->filterCommon()->order('type', 'desc')
            ->order('releasetime','desc');;
        $query = $query->paginate(request()->param('per_page',20));
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

    /**
     * 获取推荐
     *
     * @param $id
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getRecommend($id)
    {
        return ShopManage::where(["area" => $id])
            ->page(1, 5)->select();
    }


}
