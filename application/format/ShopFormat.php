<?php

namespace app\format;


/**
 * shop
 * Class ShopFormat
 * @package app\format
 */
class ShopFormat
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
                'id'=>$row['id'],
                'type'=>$row['type'],
                'title' => $row['title'],
                'imgs' => $row['imgs'] ? $row['imgs'][0] : '',
                'shop_nature'=>$row['shop_nature'],
                'price'=>$row['price'].'元',
                'manage_status'=>$row['manage_status'],
                'manage_type'=>$row['manage_type'],
                'floor'=>$row['floor'].'楼',
                'size'=>$row['size'],
                'pay_type'=>$row['pay_type'],
                'address'=>$row['address'],
                'measurearea'=>$row['measurearea'],
                'buildingname'=>$row['buildingname'],
                'releasetime'=>$row['releasetime'],
                'name'=>$row['name'],
                'tel'=>$row['tel'],
                'category'=>$row['category']==0?'正常':($row['category']==1?'推荐':'热门'),
                'is_sale'=>$row['is_sale']==0?'出租':'出售',
            ];
        }
        return $rtn;
    }

}