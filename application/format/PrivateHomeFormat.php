<?php

namespace app\format;


use app\admin\model\Area;
use app\admin\model\PrivateHomeManage;
use app\admin\model\ShopManage;

/**
 * shop
 * Class ShopFormat
 * @package app\format
 */
class PrivateHomeFormat
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

    public function formatList($data)
    {
        $rtn = [];
        foreach ($data as $row) {

             $city = $row->belongsToOneCity;
            $area = $row->belongsToOneArea;
            $rtn[] = [
                'id' => $row['id'],
                'city'=>$row['city'],
                'area'=>$row['area'],
                'city_name' => $city ? $city->name : '',
                'area_name' => $area ? $area->name : '',
                'name' => $row['name'],
                'tel' => $row['tel'],
                'title' => $row['title'],
                'address' => $row['address'],
                'imgs' => $row['imgs'],
                'measurearea' => $row['measurearea'],
                'category' => $row['category'],
                'category_name' =>PrivateHomeManage::CATEGORY_CONFIG[$row['category']],
                'floor_type' => $row['floor_type'],
                'floor_type_name' =>PrivateHomeManage::FLOOR_TYPE[$row['floor_type']],
                'structure' => $row['floor_type'],
                'structure_name' =>PrivateHomeManage::STRUCT_CONFIG[$row['structure']],
                'detail' => $row['detail'],
                'single_area' => $row['single_area'],
                'toward' => $row['toward'],
                'fit_up' => $row['fit_up'],
                'build_year' => $row['build_year'],
                'floor' => $row['floor'],
                'certify' => $row['certify'],
                'price' => $row['price'],
                'releasetime' => $row['releasetime'],
            ];
        }
        return $rtn;
    }

    public function formatDetail($row)
    {
        $city = $row->belongsToOneCity;
        $area = $row->belongsToOneArea;
        return [
            'id' => $row['id'],
            'city'=>$row['city'],
            'area'=>$row['area'],
            'city_name' => $city ? $city->name : '',
            'area_name' => $area ? $area->name : '',
            'name' => $row['name'],
            'tel' => $row['tel'],
            'title' => $row['title'],
            'address' => $row['address'],
            'imgs' => $row['imgs'],
            'measurearea' => $row['measurearea'],
            'category' => $row['category'],
            'category_name' =>PrivateHomeManage::CATEGORY_CONFIG[$row['category']],
            'floor_type' => $row['floor_type'],
            'floor_type_name' =>PrivateHomeManage::FLOOR_TYPE[$row['floor_type']],
            'structure' => $row['floor_type'],
            'structure_name' =>PrivateHomeManage::STRUCT_CONFIG[$row['structure']],
            'detail' => $row['detail'],
            'single_area' => $row['single_area'],
            'toward' => $row['toward'],
            'fit_up' => $row['fit_up'],
            'build_year' => $row['build_year'],
            'floor' => $row['floor'],
            'certify' => $row['certify'],
            'price' => $row['price'],
            'releasetime' => $row['releasetime'],
        ];

    }

    private function __clone()
    {

    }

}