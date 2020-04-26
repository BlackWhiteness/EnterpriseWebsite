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
// | 后台菜单管理
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\model\Workshop as Workshop_Model;
use app\common\controller\Adminbase;
use think\Request;

class Upload extends Adminbase
{

    //后台菜单首页
    public function imgs()
    {
        $file = request()->file('file');
        ini_set('memory_limit', '216M');
        // 移动到框架应用根目录/public/uploads/ 目录下
        if (!empty($file)) {
            // 移动到框架应用根目录/public/uploads/ 目录下

            $s = 'uploads' . DIRECTORY_SEPARATOR . date('Y-m-d');
//,'ext'=>'jpg,png,gif'
            $info = $file->validate(['size' => 10485760])->rule('uniqid')->move(ROOT_PATH . 'public' . DIRECTORY_SEPARATOR . $s);
            $error = $file->getError();
            //验证文件后缀后大小
            if (!empty($error)) {
                dump($error);
                exit;
            }
            if ($info) {
                // 成功上传后 获取上传信息
                // 输出 jpg
                $info->getExtension();
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                $info->getSaveName();
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                $photo = $info->getFilename();
                $returnPath = DIRECTORY_SEPARATOR . $s . DIRECTORY_SEPARATOR . $photo;
                $fullPath = ROOT_PATH . 'public' .$returnPath;
                $this->compressPic($fullPath);
            } else {
                // 上传失败获取错误信息
                $file->getError();
            }
        } else {
            $photo = '';
        }
        if ($photo !== '') {
            return ['code' => 1, 'msg' => '成功', 'photo' => $returnPath];
        } else {
            return ['code' => 404, 'msg' => '失败'];
        }

    }

    /**
     * 压缩图片
     *
     * @param $path
     * @return bool
     */
    public function compressPic($path)
    {
        $image = file_get_contents($path);
        $source = imagecreatefromstring($image);
        $width = imagesx($source);
        $height = imagesy($source);
        $newImage = imagecreatetruecolor($width, $height);
        imagecopy($newImage, $source, 0, 0, 0, 0, $width, $height);
        imagedestroy($source);
        return imagejpeg($newImage, $path, 75);//输出
    }

    /**
     *
     * 编辑器上传图片
     * @param Request $request
     * @return array
     */
    public function editUploads(Request $request)
    {
        $files = $request->file('image');
        $data = [];
        foreach ($files as $file) {
            $s = 'uploads/' . date('Y-m-d');
            $info = $file->validate(['size' => 1048576])->rule('uniqid')
                ->move(ROOT_PATH . 'public' . DIRECTORY_SEPARATOR . $s);
            if ($info) {
                $photo = $info->getFilename();
//                $data[] = 'http://'.$request->host().'/'.$s. '/' . $photo;
//                $data[] = url($s. '/' . $photo);
                $data[] = url($s . '/' . $photo, '', '', true);;
            }
        }
        if (empty($data)) {
            $res = ['errno' => 1, 'data' => []];
        } else {
            $res = ['errno' => 0, 'data' => $data];
        }
        return json($res);
    }

    //添加后台菜单
    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            /**if (!isset($data['status'])) {
             * $data['status'] = 0;
             * } else {
             * $data['status'] = 1;
             * }**/

            //$result = $this->validate($data, 'Menu.add');var_dump($result);exit;
            if (!$data) {
                return $this->error($data);
            }
            if (Workshop_Model::create($data)) {
                $this->success("添加成功！", url("index"));
            } else {
                $this->error('添加失败！');
            }
        } else {
            return $this->fetch();
        }
    }

    /**
     *编辑后台菜单
     */
    public function edit()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            if (!isset($data['status'])) {
                $data['status'] = 0;
            } else {
                $data['status'] = 1;
            }
            //$result = $this->validate($data, 'Menu.edit');
            if (!$data) {
                return $this->error($result);
            }
            if (Workshop_Model::update($data)) {
                $this->success("编辑成功！", url("index"));
            } else {
                $this->error('编辑失败！');
            }
        } else {
            $id = $this->request->param('id/d', '');
            $rs = Workshop_Model::where(["id" => $id])->find();
            $result = Workshop_Model::order(array('id' => 'DESC'))->select()->toArray();
            $this->assign("data", $rs);
            $this->assign("select_categorys", $result);
            return $this->fetch();
        }

    }

    /**
     * 菜单删除
     */
    public function del()
    {
        $id = $this->request->param('id/d');
        if (empty($id)) {
            $this->error('ID错误');
        }
        $workshop = new Workshop_Model();
        if ($workshop->del($id) !== false) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }

    /**
     * 菜单排序
     */
    public function listorder()
    {
        $id = $this->request->param('id/d', 0);
        $listorder = $this->request->param('value/d', 0);
        $rs = Workshop_Model::update(['id' => $id, 'listorder' => $listorder]);
        if ($rs) {
            $this->success("菜单排序成功！");
        } else {
            $this->error("菜单排序失败！");
        }
    }

    /**
     * 菜单状态
     */
    public function setstate()
    {
        $id = $this->request->param('id/d');
        empty($id) && $this->error('参数不能为空！');
        $status = $this->request->param('status/d');
        if (Workshop_Model::update(['status' => $status], ['id' => $id])) {
            $this->success('操作成功！');
        } else {
            $this->error('操作失败！');
        }
    }

}
