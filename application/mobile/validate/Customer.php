<?php
/**
 * Created by PhpStorm.
 * User: d
 * Date: 2019/10/19
 * Time: 22:47
 */
namespace app\index\validate;

use think\Validate;

class Customer extends Validate
{
    protected $rule = [
//        'city' => 'require|number',
//        'area' => 'require|number',
        'leibie' => 'number|between:1,3',
        'leixing' => 'number|between:1,2',
        'name' => 'require|max:15',
        'tel' => 'require|checkMobile',
        'detail' => 'require'
    ];

    protected $field = [
        'city' => '城市',
        'area' => '地区',
        'leibie' => '委托类型',
        'leixing' => '租售类型',
        'name' => '姓名',
        'tel' => '手机',
        'detail' => '内容',

    ];

    protected $msg = [
        'city.require' => '请选择您所在城市',
        'area.require' => '请选择所在地区',
        'leibie.between' => '请选择正确的委托类型',
        'leixing.between' => '请选择正确的租售类型',
        'name.require' => '请输入您的姓名',
        'tel.checkMobile' => '请输入正确的手机号',
        'detail.require' => '请输入您的具体要求',
    ];

    /**
     * 检查手机格式
     * @param $value|验证数据
     * @param $rule|验证规则
     * @param $data|全部数据
     * @return bool|string
     */
    protected function checkMobile($value, $rule ,$data)
    {
        return check_mobile($value);
    }

}