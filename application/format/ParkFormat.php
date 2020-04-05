<?php

namespace app\format;



/**
 * park format
 *
 * @package app\format
 */
class ParkFormat
{

    private static $instance = null;

    private function __construct()
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

    /**
     * 格式化列表
     *
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function formatList($data)
    {
        $rtn = [];
        foreach ($data as $row) {
            $city = $row->belongsToOneCity;
            $area = $row->belongsToOneArea;
            $rtn[] = [
                'id' => $row['id'],
                'releasetime' => $row['releasetime'],
                'address' => $row['address'],
                'name' => $row['name'],
                'tel' => $row['tel'],
                'imgs' => $row['imgs'],
                'detail' => $row['detail'],
                'title' => $row['title'],
                'park_name' => $row['park_name'],
                'city'=>$row['city'],
                'area'=>$row['area'],
                'city_name' => $city ? $city->name : '',
                'area_name' => $area ? $area->name : '',
            ];
        }
        return $rtn;
    }

    /**
     * 格式化详情
     *
     * @param $row
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function formatDetail($row)
    {
        $city = $row->belongsToOneCity;
        $area = $row->belongsToOneArea;
        return [
            'id' => $row['id'],
            'releasetime' => $row['releasetime'],
            'address' => $row['address'],
            'name' => $row['name'],
            'tel' => $row['tel'],
            'imgs' => $row['imgs'],
            'detail' => $row['detail'],
            'title' => $row['title'],
            'park_name' => $row['park_name'],
            'city'=>$row['city'],
            'area'=>$row['area'],
            'city_name' => $city ? $city->name : '',
            'area_name' => $area ? $area->name : '',
        ];

    }

    private function __clone()
    {

    }

}