<?php

namespace app\search\model;

use app\search\model\Clientapiofes as Clientapiofes;

class Indexworkshop
{

    const PAGE_SIZE = 50;
    const LOCK_TIME = 1800; // 30 min
    const BULK_INDEX_ZY_BOOK_LOG = 'bulk_index_zy_book_v2.log';

    private $mSearchObj = null;
    private $mIndexName = 'work_shop';
    private $mIndexType = 'work_shop';
    private $mBulkIndexZYBookLock = 'bulk_index_zy_book_v2_lock';
    private $mBooks = array();
    private $mRequestParams = array();

    public function __construct()
    {
        $this->mSearchObj = new ClientApiOfEs();
    }

    // 判断文档存在
    public function checkDoc($id)
    {
        $params = [
            'index' => $this->mIndexName,
            'type' => $this->mIndexType,
            'id' => $id
        ];

        return $this->mSearchObj->existsDoc($params);
    }

    /**
     * 修改或添加文档
     * @param $params
     * @return bool
     */
    public function index($params)
    {
        $esParam = [
            'index' => $this->mIndexName,
            'type' => $this->mIndexType,
            'body' => []
        ];
        $item = array(
            'id' => $params['id'],
            'title' => $params['title'],
            'city' => $params['city'],
            'area' => $params['area'],
            'floor' => $params['floor'],
            'structure' => $params['structure'],
            'measurearea' => $params['measurearea'],
            'type' => $params['type'],
            'category' => $params['category'],
        );

        $checkDoc = $this->checkDoc($params['id']);
        unset($item['id']);
        if ($checkDoc) {
            $esParam['id'] = $params['id'];
            $esParam['body']['doc'] = $item;
            $this->mSearchObj->updateDoc($esParam);
        } else {
            $esParam['id'] = $params['id'];
            $esParam['body']['doc'] = $item;
//            array_push(
//                $this->mRequestParams['body'], array('create' => array('_id' => $params['id'])), $item
//            );
            $this->mSearchObj->addDoc($esParam);
        }
        return true;
//        dump($this->mRequestParams);
//        dump($this->mRequestParams['body']);
//        if (!empty($this->mRequestParams['body'])) {
//            $this->mSearchObj->bulk($this->mRequestParams);
//        }
    }

    /**
     * 删除文档
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $params = [
            'index' => $this->mIndexName,
            'type' => $this->mIndexType,
            'id' => $id
        ];
        $this->mSearchObj->deleteDoc($params);
        return true;
    }
}