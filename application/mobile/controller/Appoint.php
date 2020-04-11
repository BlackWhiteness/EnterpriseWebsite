<?php


namespace app\mobile\controller;


use app\common\controller\MobileBase;
use think\Db;
use think\Request;

class Appoint extends MobileBase
{
    /**
     * 预约
     *
     * @param Request $request
     * @return \think\response\Json
     */
    public function saveAppoint(Request $request)
    {
        $phone = $request->param('phone');
        $detail = trim(strip_tags($request->param('detail')));
        if (empty($phone) || empty($detail)) {
            return json(['status' => -1, 'message' => '手机号或需求不能为空！']);
        }
        $check = '/^(1(([35789][0-9])|(47)))\d{8}$/';
        if (!preg_match($check, $phone)) {
            return json(['status' => -1, 'message' => '手机号格式不正确！']);
        }
        Db::table('search_appoint')->insert([
            'detail' => $detail,
            'phone' => $phone,
            'create_time' => time()
        ]);
        return json(['status' => 0, 'message' => '预约成功！']);
    }

    public function demand()
    {
        return $this->fetch('demand');
    }
}
