<?php

namespace app\format;

use app\admin\model\Area;
use app\admin\model\Officebuilding;

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
     *
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @since  2020/4/2
     * @author WangShuai
     */
    public function formatList($data)
    {
        $rtn = [];

        foreach ($data as $row) {
            $area = Area::where('id', '=', $row['area'])->find();
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
                'title' => $row['title'],
                'detail' => $row['detail'],
                'area_name' => $area->name ,
            ];

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
            $area = Area::where('id', '=', $row['area'])->find();
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
                'title' => $row['title'],
                'area_name' => $area->name,
                'detail' => mb_substr(strip_tags($row['detail']), 0, 300)
            ];
        }
        return $rtn;
    }

    /**
     * 格式化详情
     * @param $data
     * @return array
     */
    public function formatDetail($data)
    {
        $city = $data->belongsToOneCity;
        $area = $data->belongsToOneArea;
        return [
            'id' => $data['id'],
            'region' => $data['region'],
            'measurearea' => $data['measurearea'],
            'releasetime' => $data['releasetime'],
            'plantrent' => $data['plantrent'],
            'newolddegree' => $data['newolddegree'],
            'name' => $data['name'],
            'tel' => $data['tel'],
            'detail' => $data['detail'],
            'tag' => $data['tag'],
            'tag_name' => $data['tag']?Officebuilding::TAG_CONFIG[$data['tag']]:'',
            'imgs' => $data['imgs'],
            'buildingname' => $data['buildingname'],
            'address' => $data['address'],
            'managementfee' => $data['managementfee'],
            'decoration' => $data['decoration'],
            'city' => $data['city'],
            'area' => $data['area'],
            'type' => $data['type'],
            'title' => $data['title'],
            'floor' => $data['floor'],
            'floor_type' => empty($data['floor_type'])?'':Officebuilding::FLOOR_CONFIG[$data['floor_type']],
            'is_sep' => $data['is_sep']==1?'是':'否',
            'pay_type' => $data['pay_type'],
            'use_year' => $data['use_year'],
            'level' => $data['level'],
            'card' => $data['card'],
            'other' => $data['other'],
            'city_name' => $city ? $city->name : '',
            'area_name' => $area ? $area->name : '',
            'category' => $data['category'],
            'category_name' => !empty($data['category'])?Officebuilding::SALE_LIST[$data['category']]:'',
            'indus_type' => empty($data['indus_type'])?'':Officebuilding::INDUS_TYPE[$data['indus_type']],
            'video' => $data['video'],
        ];
    }


}
