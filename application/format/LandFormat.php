<?php

namespace app\format;


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
            ];
        }
        return $rtn;
    }

}