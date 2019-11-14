<?php

namespace app\admin\model;

use \think\Model;

/**
 * 外链模型类
 * Class HrefManage
 * @package app\admin\model
 */
class HrefManage extends Model
{
    protected $pk = 'id';
    protected $table = 'search_href';
    protected $autoWriteTimestamp = true;
    // 定义时间戳字段名
    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';

}
