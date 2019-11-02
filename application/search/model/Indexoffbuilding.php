<?php
namespace app\search\model;
use app\search\model\Clientapiofes as Clientapiofes;
class Indexoffbuilding {

    const PAGE_SIZE = 50;
    const LOCK_TIME = 1800; // 30 min
    const BULK_INDEX_ZY_BOOK_LOG = 'bulk_index_zy_book_v2.log';

    private $mSearchObj = null;
    private $mIndexName = 'off_building';
    private $mIndexType = 'off_building';
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
            'measurearea'        => $params['measurearea'],
            'plantrent'        => $params['plantrent']
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