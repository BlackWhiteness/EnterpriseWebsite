<?php
namespace app\mall\model;

use think\Model;

class Pingjia extends Model
{
    public function createPingjia($data)
    {
        if (empty($data)) {
            $this->error = '没有数据！';
            return false;
        }
        $id = $this->allowField(true)->save($data);
        if ($id) {
            return $id;
        }
        $this->error = '入库失败！';
        return false;
    }
}
