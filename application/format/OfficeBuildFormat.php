<?php

namespace app\format;

/**
 * 写字楼格式化
 * Class OfficeBuildFormat
 * @package app\format
 */
class OfficeBuildFormat
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
            $rtn[] = [
                'id' => $row['id'],
                'region' => $row['region'],
                'measurearea' => $row['measurearea'],
                'releasetime' => $row['releasetime'],
                'plantrent' => $row['plantrent'],
                'newolddegree' => $row['newolddegree'],
                'name' => $row['name'],
                'tel' => $row['tel'],
                'tag' => $row['tag'],
                'imgs' => $row['imgs'][0] ?: '',
                'buildingname' => $row['buildingname'],
                'address' => $row['address'],
                'managementfee' => $row['managementfee'],
                'decoration' => $row['decoration'],
                'city' => $row['city'],
                'area' => $row['area'],
                'type' => $row['type'],
                'title' => $row['title']];
        }
        return $rtn;
    }

    /**
     * 格式化用于列表
     * @param $data
     * @return array
     */
    public function formatAjaxList($data)
    {
        $rtn = [];

        foreach ($data as $row) {
            $rtn[] = [
                'id' => $row['id'],
                'region' => $row['region'],
                'measurearea' => $row['measurearea'],
                'releasetime' => $row['releasetime'],
                'plantrent' => $row['plantrent'],
                'newolddegree' => $row['newolddegree'],
                'name' => $row['name'],
                'tel' => $row['tel'],
                'tag' => $row['tag'],
                'imgs' => $row['imgs'] ? $row['imgs'][0] : '',
                'buildingname' => $row['buildingname'],
                'address' => $row['address'],
                'managementfee' => $row['managementfee'],
                'decoration' => $row['decoration'],
                'city' => $row['city'],
                'area' => $row['area'],
                'type' => $row['type'],
                'title' => $row['title']];
        }
        return $rtn;
    }


}