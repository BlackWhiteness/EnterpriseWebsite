<?php
namespace app\search\model;
use app\search\model\Clientapiofes as Clientapiofes;
class Indexworkshop {

    const PAGE_SIZE = 50;
    const LOCK_TIME = 1800; // 30 min
    const BULK_INDEX_ZY_BOOK_LOG = 'bulk_index_zy_book_v2.log';

    private $mSearchObj = null;
    private $mIndexName = 'work_shop';
    private $mIndexType = 'work_shop';
    private $mBulkIndexZYBookLock = 'bulk_index_zy_book_v2_lock';
    private $mBooks = array();
    private $mRequestParams = array();

    public function __construct() {
        $this->mSearchObj = new ClientApiOfEs();
    }

    // 判断文档存在
    public function checkDoc($id) {
        $params = [
            'index' => $this->mIndexName,
            'type'  => $this->mIndexType,
            'id'    => $id
        ];

        return $this->mSearchObj->existsDoc($params);
    }

    public function index($params) {
        $this->mRequestParams = [
            'index' => $this->mIndexName,
            'type'  => $this->mIndexType,
            'body'  => []
        ];
        $item = array(
            'id'        => $params['id'],
            'title'        => $params['title'],
            'city'        => $params['city'],
            'area'        => $params['area'],
            'floor'        => $params['floor'],
            'structure'        => $params['structure'],
            'measurearea'        => $params['measurearea'],
            'type'        => $params['type'],
            'category'        => $params['category'],
        );
        
        $checkDoc = $this->checkDoc($params['id']);
        unset($item['id']);
        if ($checkDoc){
            array_push(
                $this->mRequestParams['body'], array('update' => array('_id' => $params['id'])),array('doc' => $item)
            );
            
        } else {
            array_push(
                $this->mRequestParams['body'], array('create' => array('_id' => $params['id'])), $item
            );
        }
        if (!empty($this->mRequestParams['body'])) {
            $this->mSearchObj->bulk($this->mRequestParams);
        }
    }
}