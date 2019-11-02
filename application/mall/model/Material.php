<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 御宅男 <530765310@qq.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 后台配置模型
// +----------------------------------------------------------------------
namespace app\mall\model;

use \think\Model;

class Material extends Model
{
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /**
     * 获取配置信息
     * @return mixed
     */
    public function config_cache()
    {
        $data = $this->getConfig();
        cache("Material", $data);
        return $data;
    }

    public static function getConfig($where = "id='1'", $fields = 'store,url,type')
    {
        $configs = self::where($where)->column($fields);
        $newConfigs = [];
        /**foreach ($configs as $key => $value) {
            if ($value['options'] != '') {
                $value['options'] = parse_attr($value['options']);
            }
            switch ($value['type']) {
                case 'array':
                    $newConfigs[$key] = parse_attr($value['value']);
                    break;
                case 'image':
                    $newConfigs[$key] = empty($value['value']) ? '' : get_file_path($value['value']);
                    break;
                case 'images':
                    $newConfigs[$key] = empty($value['value']) ? [] : get_file_path($value['value']);
                    break;
            }
        }*/
        return $newConfigs;
    }

}
