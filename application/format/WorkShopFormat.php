<?php

namespace app\format;

use app\admin\model\Area;
use app\admin\model\Workshop;

/**
 * 工厂数据格式化
 * Class WorkShopFormat
 * @package app\format
 */
class WorkShopFormat
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

    /**
     * 格式化用于列表
     * @param $data
     * @return array
     */
    public function formatList($data)
    {
        $rtn = [];
        foreach ($data as $row) {
            $area = Area::where('id', '=', $row['area'])->find();
            $rtn[] = [
                'imgs' => $row['imgs'] ? $row['imgs'][0] : '',
                'applicableindustries' => $row['applicableindustries'],
                'area' => $row['area'],
                'canteen' => $row['canteen'],
                'category' => $row['category'],
                'city' => $row['city'],
                'detail' => $row['detail'],
                'distributioncapacity' => $row['distributioncapacity'],
                'dormitory_area' => $row['dormitory_area'],
                'dormitoryarea' => $row['dormitoryarea'],
                'elevator' => $row['elevator'],
                'expire' => $row['expire'],
                'firecontrol' => $row['firecontrol'],
                'floor' => $row['floor'],
                'id' => $row['id'],
                'layernumber' => $row['layernumber'],
                'lengthwidth' => $row['lengthwidth'],
                'measurearea' => $row['measurearea'],
                'name' => $row['name'],
                'newolddegree' => $row['newolddegree'],
                'numberbuildings' => $row['numberbuildings'],
                'officespace' => $row['officespace'],
                'plantrent' => $row['plantrent'],
                'region' => $row['region'],
                'releasetime' => $row['releasetime'],
                'structure' => $row['structure'],
                'tag' => $row['tag'],
                'tel' => $row['tel'],
                'title' => $row['title'],
                'type' => $row['type'],
                'area_name' => $area->name . '区'
            ];
        }
        return $rtn;
    }

    /**
     * 格式化
     * @param $detail
     * @return array
     */
    public function formatDetail($detail)
    {
        return [
            'id' => $detail['id'],
            'region' => $detail['region'],
            'measurearea' => $detail['measurearea'],
            'floor' => $detail['floor'],
            'structure' => Workshop::STRUCT_CONFIG[$detail['structure']],
            'releasetime' => $detail['releasetime'],
            'expire' => $detail['expire'],
            'officespace' => $detail['officespace'],
            'plantrent' => $detail['plantrent'],
            'dormitory_area' => $detail['dormitory_area'],
            'numberbuildings' => $detail['numberbuildings'],
            'layernumber' => $detail['layernumber'],
            'distributioncapacity' => $detail['distributioncapacity'],
            'lengthwidth' => $detail['lengthwidth'],
            'canteen' => $detail['canteen'],
            'newolddegree' => $detail['newolddegree'],
            'elevator' => $detail['elevator'],
            'firecontrol' => $detail['firecontrol'],
            'applicableindustries' => $detail['applicableindustries'],
            'name' => $detail['name'],
            'tel' => $detail['tel'],
            'detail' => $detail['detail'],
            'tag' => $detail['tag'],
            'imgs' => $detail['imgs'],
            'title' => $detail['title'],
            'city' => $detail['city'],
            'area' => $detail['area'],
            'dormitoryarea' => $detail['dormitoryarea'],
            'type' => $detail['type'],
            'category' => Workshop::CATEGORY_CONFIG[$detail['category']],
        ];
    }
}