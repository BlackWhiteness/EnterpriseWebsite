<?php
namespace app\search\model;

use Elasticsearch\ClientBuilder;

class Clientapiofes {

    const LOG_FILE = 'Utils_Search_ClientApiOfEs.log';

    private $mEs;

    // 构造函数
    public function __construct() {
        $config = array(
            '127.0.0.1:52301'
        );//exit;
        $this->mEs = ClientBuilder::create()->setHosts($config)->build();
    }

    // 创建索引
    public function createIndex($params) { // 只能创建一次
        try {
            return $this->mEs->indices()->create($params);
        } catch (\Exception $e) {
            Utils_Log::err(self::LOG_FILE, 'createIndex', array('message:' => $e->getMessage()));
            return ErrorCode::ERR_FAIL;
        }
    }

    // 删除索引
    public function deleteIndex($params) {
        try{
            return $this->mEs->indices()->delete($params);
        } catch (Exception $e) {
            Utils_Log::err(self::LOG_FILE, 'deleteIndex', array('message:' => $e->getMessage()));
            return ErrorCode::ERR_FAIL;
        }
    }

    // 添加文档
    public function addDoc($params) {
        try{
            return $this->mEs->index($params);
        } catch (Exception $e) {
            Utils_Log::err(self::LOG_FILE, 'addDoc', array('message:' => $e->getMessage()));
            return ErrorCode::ERR_FAIL;
        }
    }

    // 判断文档存在
    public function existsDoc($params) {
        try{
            return $this->mEs->exists($params);
        } catch (Exception $e) {
            Utils_Log::err(self::LOG_FILE, 'existsDoc', array('message:' => $e->getMessage()));
            return ErrorCode::ERR_FAIL;
        }
    }


    // 获取文档
    public function getDoc($params) {
        try{
            return $this->mEs->get($params);
        } catch (Exception $e) {
            Utils_Log::err(self::LOG_FILE, 'getDoc', array('message:' => $e->getMessage()));
            return ErrorCode::ERR_FAIL;
        }
    }

    // 建议文档
    public function suggest($params) {
        try{
            return $this->mEs->suggest($params);
        } catch (Exception $e) {
            Utils_Log::err(self::LOG_FILE, 'suggest', array('message:' => $e->getMessage()));
            return ErrorCode::ERR_FAIL;
        }
    }

    // 更新文档
    public function updateDoc($params) {
        try{
            return $this->mEs->update($params);
        } catch (Exception $e) {
            Utils_Log::err(self::LOG_FILE, 'updateDoc', array('message:' => $e->getMessage()));
            return ErrorCode::ERR_FAIL;
        }
    }

    // 删除文档
    public function deleteDoc($params) {
        try{
            return $this->mEs->delete($params);
        } catch (Exception $e) {
            Utils_Log::err(self::LOG_FILE, 'deleteDoc', array('message:' => $e->getMessage()));
            return ErrorCode::ERR_FAIL;
        }
    }

    // 查询文档 (分页，排序，权重，过滤)
    public function search($params) {
        try{
            return $this->mEs->search($params);
        } catch (Exception $e) {
            Utils_Log::err(self::LOG_FILE, 'search', array('message:' => $e->getMessage()));
            return ErrorCode::ERR_FAIL;
        }
    }

    public function bulk($params) {
        try{
            return $this->mEs->bulk($params);
        } catch (Exception $e) {
            Utils_Log::err(self::LOG_FILE, 'bulk', array('message:' => $e->getMessage()));
            return ErrorCode::ERR_FAIL;
        }
    }

    public function analyze($params) {
        try{
            return $this->mEs->indices()->analyze($params);
        } catch (Exception $e) {
            Utils_Log::err(self::LOG_FILE, 'analyze', array('message:' => $e->getMessage()));
            return ErrorCode::ERR_FAIL;
        }
    }
}