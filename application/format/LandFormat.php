<?php

namespace app\format;


use app\admin\model\Area;

/**
 * Class LandFormat
 * @package app\format
 */
class LandFormat
{

    private static $instance = null;

    private function __construct()
    {

    }

    private function __clone()
    {

    }

    /**
     * @return self
     */
    public static function getInstance()
    {
        if (!static::$instance instanceof self) {
            static::$instance = new self();
        }
        return static::$instance;
    }

    public function formatList($data)
    {
        $rtn = [];
        foreach ($data as $row) {
            $area = Area::where('id', '=', $row['area'])->find();
            $rtn[] = [
                'id' => $row['id'],
                'title' => $row['title'],
                'land_use' => $row['land_use'] . '年',
                'city' => $row['city'],
                'area' => $row['area'],
                'region' => $row['region'],
                'type' => $row['type'] == 1 ? '出租' : '出售',
//                'detail' => $row['detail'],
                'address' => $row['address'],
                'measurearea' => $row['measurearea'] . 'm²',
                'releasetime' => $row['releasetime'],
                'price' => $row['price'] . '元',
                'name' => $row['name'],
                'tel' => $row['tel'],
                'tag' => $row['tag'],
                'imgs' => $row['imgs'] ? $row['imgs'][0] : '',
                'land_type' => $row['land_type'],
                'land_card' => $row['land_card'] == 0 ? '有' : '无',
                'area_name' => $area->name . '区',
                'detail' => mb_substr(strip_tags($row['detail']), 0, 300)
            ];
        }
        return $rtn;
    }

    /**
     * 格式化
     * @param $detail
     * @return array
     */
    public function formatDetail($row)
    {
        $city = $row->belongsToOneCity;
        $area = $row->belongsToOneArea;

        return [
            'id' => $row['id'],
            'title' => $row['title'],
            'land_use' => $row['land_use'] . '年',
            'city' => $row['city'],
            'area' => $row['area'],
            'region' => $row['region'],
            'type' => $row['type'] == 1 ? '出租' : '出售',
            'address' => $row['address'],
            'measurearea' => $row['measurearea'] . 'm²',
            'releasetime' => $row['releasetime'],
            'price' => $row['price'] . '元',
            'name' => $row['name'],
            'tel' => $row['tel'],
            'tag' => $row['tag']?:'',
            'imgs' => $row['imgs'] ?: [],
            'land_type' => $row['land_type'],
            'land_card' => $row['land_card'] == 0 ? '有' : '无',
            'detail' => $row['detail'],
            'city_name' => $city ? $city->name : '',
            'area_name' => $area ? $area->name : '',
        ];
    }

}