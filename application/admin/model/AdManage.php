<?php

namespace app\admin\model;

use \think\Model;

/**
 * 广告模型类
 * Class AdManage
 * @package app\admin\model
 */
class AdManage extends Model
{
    protected $pk = 'id';
    protected $table = 'search_ad';
    protected $autoWriteTimestamp = true;
    // 定义时间戳字段名
    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';

}
