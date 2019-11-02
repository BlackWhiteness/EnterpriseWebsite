<?php
namespace app\upload\controller;

use app\admin\model\AdminUser as Admin_User;
use app\admin\model\AuthGroup as AuthGroup_Model;
use app\common\controller\Adminbase;
use think\Db;

/**
 * 管理员管理
 */
class Video extends Adminbase
{
    protected function initialize()
    {
        parent::initialize();
        $this->Admin_User = new Admin_User;
    }

    /**
     * 管理员管理列表
     */
    public function index()
    {
    		// 获取表单上传文件 例如上传了001.jpg
	    $file = request()->file('file');
	   
	    // 移动到框架应用根目录/public/uploads/ 目录下
	                    if(!empty($file)){
                    // 移动到框架应用根目录/public/uploads/ 目录下
$s =  'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . date('Y-m-d');
//,'ext'=>'jpg,png,gif'
                    $info = $file->validate(['size'=>1048576])->rule('uniqid')->move(ROOT_PATH . $s);
                    $error = $file->getError();
                    //验证文件后缀后大小
                    if(!empty($error)){
                        dump($error);exit;
                    }
                    if($info){
                        // 成功上传后 获取上传信息
                        // 输出 jpg
                        $info->getExtension();
                        // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                        $info->getSaveName();
                        // 输出 42a79759f284b767dfcb2a0197904287.jpg
                        $photo = $info->getFilename();
 
                    }else{
                        // 上传失败获取错误信息
                        $file->getError();
                    }
                }else{
                    $photo = '';
                }
        if($photo !== ''){
            return ['code'=>1,'msg'=>'成功','photo'=> '/' . $s . '/'.$photo];
        }else{
            return ['code'=>404,'msg'=>'失败'];
        }
    }

/**
public function upload_photo(){
        $file = $this->request->file('file');
        $uid = session('ydyl_weixin_user.id');
        // if(empty($uid)){
        //     return ['code'=>404,'msg'=>'用户未登录'];
        // }
                if(!empty($file)){
                    // 移动到框架应用根目录/public/uploads/ 目录下
                    $info = $file->validate(['size'=>1048576,'ext'=>'jpg,png,gif'])->rule('uniqid')->move(ROOT_PATH . 'public' . DS . 'uploads');
                    $error = $file->getError();
                    //验证文件后缀后大小
                    if(!empty($error)){
                        dump($error);exit;
                    }
                    if($info){
                        // 成功上传后 获取上传信息
                        // 输出 jpg
                        $info->getExtension();
                        // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                        $info->getSaveName();
                        // 输出 42a79759f284b767dfcb2a0197904287.jpg
                        $photo = $info->getFilename();
 
                    }else{
                        // 上传失败获取错误信息
                        $file->getError();
                    }
                }else{
                    $photo = '';
                }
        if($photo !== ''){
            return ['code'=>1,'msg'=>'成功','photo'=>$photo];
        }else{
            return ['code'=>404,'msg'=>'失败'];
        }
    }
*/

    /**
     * 添加管理员
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post('');
            $result = $this->validate($data, 'AdminUser.insert');
            if (true !== $result) {
                return $this->error($result);
            }
            if ($this->Admin_User->createManager($data)) {
                $this->success("添加管理员成功！", url('admin/manager/index'));
            } else {
                $error = $this->Admin_User->getError();
                $this->error($error ? $error : '添加失败！');
            }

        } else {
            $this->assign("roles", model('admin/AuthGroup')->getGroups());
            return $this->fetch();
        }
    }

    /**
     * 管理员编辑
     */
    public function edit()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post('');
            $result = $this->validate($data, 'AdminUser.update');
            if (true !== $result) {
                return $this->error($result);
            }
            if ($this->Admin_User->editManager($data)) {
                $this->success("修改成功！");
            } else {
                $this->error($this->User->getError() ?: '修改失败！');
            }
        } else {
            $id = $this->request->param('id/d');
            $data = $this->Admin_User->where(array("id" => $id))->find();
            if (empty($data)) {
                $this->error('该信息不存在！');
            }
            $this->assign("data", $data);
            $this->assign("roles", model('admin/AuthGroup')->getGroups());
            return $this->fetch();
        }
    }

    /**
     * 管理员删除
     */
    public function del()
    {
        $id = $this->request->param('id/d');
        if ($this->Admin_User->deleteManager($id)) {
            $this->success("删除成功！");
        } else {
            $this->error($this->Admin_User->getError() ?: '删除失败！');
        }
    }

}
