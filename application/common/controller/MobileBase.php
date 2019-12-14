<?php


namespace app\common\controller;

use app\common\controller\Base;

/**
 * 移动端控制器基类
 * Class MobileBase
 * @package app\common\controller
 */
class MobileBase extends Base
{
    /**
     * 初始化函数
     */
    protected function initialize()
    {
        //移除HTML标签
        $this->request->filter('trim,strip_tags,htmlspecialchars');
        parent::initialize();
    }

}
