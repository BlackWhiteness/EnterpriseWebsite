<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2007 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 御宅男 <530765310@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\model;

use think\Model;

/**
 * 城市类
 * Class CityModel
 */
class City extends Model
{
    protected $pk = 'id';
    protected $table = 'search_city';
//    const TYPE_ADMIN = 1; // 管理员用户组类型标识
//    const MEMBER = 'admin';
//    const AUTH_EXTEND = 'auth_extend'; // 动态权限扩展信息表
//    const AUTH_GROUP = 'auth_group'; // 用户组表名
//    const AUTH_EXTEND_CATEGORY_TYPE = 1; // 分类权限标识
//    const AUTH_EXTEND_MODEL_TYPE = 2; //分类权限标识
//
//    protected $resultSetType = 'collection';
//
//    /**
//     * 返回用户组列表
//     * 默认返回正常状态的管理员用户组列表
//     * @param array $where   查询条件,供where()方法使用
//     */
//    public function gets($where = array())
//    {
//        $map = array();
//        $map = array_merge($map, $where);
//        return $this->where($map)->select();
//    }
//
//    /**
//     * 根据角色Id获取角色名
//     * @param int $Groupid 角色id
//     * @return string 返回角色名
//     */
//    public function getRoleIdName($Groupid)
//    {
//        return $this->where(array('id' => $Groupid))->value('title');
//    }
//
//    /**
//     * 通过递归的方式获取该角色下的全部子角色
//     * @param type $id
//     * @return string
//     */
//    public function getArrchildid($id)
//    {
//        if (empty($this->roleList)) {
//            $this->roleList = $this->order(array("id" => "desc"))->column('*', 'id');
//        }
//        $arrchildid = $id;
//        if (is_array($this->roleList)) {
//            foreach ($this->roleList as $k => $cat) {
//                if ($cat['parentid'] && $k != $id && $cat['parentid'] == $id) {
//                    $arrchildid .= ',' . $this->getArrchildid($k);
//                }
//            }
//        }
//        return $arrchildid;
//    }
//
//    /**
//     * 删除角色
//     * @param int $Groupid 角色ID
//     * @return boolean
//     */
//  public function del($id)
//    {
//        $id = (int) $id;
//        if (empty($id)) {
//            $this->error = '请指定需要删除的ID！';
//            return false;
//        }
//        if (false !== $this->where(array('id' => $id))->delete()) {
//            return true;
//        } else {
//            $this->error = '删除失败！';
//            return false;
//        }
//    }

}
