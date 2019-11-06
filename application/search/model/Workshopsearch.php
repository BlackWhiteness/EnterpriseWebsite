<?php

namespace app\search\model;

use app\search\model\Clientapiofes as Clientapiofes;

class Workshopsearch
{
    const BOOST_LEVEL_1 = 4;
    const BOOST_LEVEL_2 = 2;
    const BOOST_LEVEL_3 = 1;
    const PRE_TAGS = "<font color='red'>";
    const POST_TAGS = "</font>";
    const HIGHLIGHT_TYPE = 'fvh';
    const PINYIN = 'pinyin';
    const SEPARATOR = '.';
    private $mSearchObj = null;
    private $mIndexName = 'work_shop';
    private $mIndexType = 'work_shop';

    public function __construct()
    {
        $this->mSearchObj = new Clientapiofes();
    }

    // 删除文档
    public function deleteDoc($id)
    {
        $params = [
            'index' => $this->mIndexName,
            'type' => $this->mIndexType,
            'id' => $id
        ];
        return $this->mSearchObj->deleteDoc($params);
    }

    // 查询文档 (分页，排序，权重，过滤)
    public function searchDoc($keywords, $cursor = 1, $pageSize, $type = 1)
    {
        $from = ($cursor - 1) * $pageSize;

        $params = [
            'from' => $from,
            'size' => $pageSize,
            'index' => $this->mIndexName,
            'type' => $this->mIndexType,
            'body' => [
                "sort" => [
                    "_score" => [
                        "order" => "desc"
                    ]
                ]]
        ];

        $i = 0;
        if (isset($keywords['city'])) {
            $params['body']['query']['bool']['must'][$i] = [
                "term" => [
                    'city' => $keywords['city'],
                ]
            ];
            $i++;
        }
        if (isset($keywords['area'])) {
            $params['body']['query']['bool']['must'][$i] = [
                "term" => [
                    'area' => $keywords['area'],
                ]
            ];
            $i++;
        }
        if (isset($keywords['floor'])) {
            $params['body']['query']['bool']['must'][$i] = [
                "term" => [
                    'floor' => $keywords['floor'],
                ]
            ];
            $i++;
        }
        if (isset($keywords['structure'])) {
            $params['body']['query']['bool']['must'][$i] = [
                "term" => [
                    'structure' => $keywords['structure'],
                ]
            ];
            $i++;
        }
        if (isset($keywords['type'])) {
            $params['body']['query']['bool']['must'][$i] = [
                "term" => [
                    'type' => $keywords['type'],
                ]
            ];
            $i++;
        }
        if (isset($keywords['category'])) {
            $params['body']['query']['bool']['must'][$i] = [
                "term" => [
                    'category' => $keywords['category'],
                ]
            ];
            $i++;
        }
        if (isset($keywords['measurearea'])) {
            $measurearea = explode('-', $keywords['measurearea']);
            $params['body']['query']['bool']['must'][$i] = [
                "range" => [
                    'measurearea' => array('gte' => $measurearea[0], 'lte' => $measurearea[1] ?: 1000000)
                ]
            ];
            $i++;
        }
        if (isset($keywords['title'])) {

            $params['body']['query']['bool']['must'][$i] =
                [
                    "match" => [
                        "title" => [
                            "query" => $keywords['title'],
                            "minimum_should_match" => "10%"
                        ]
                    ]
                ];
            $i++;

        }

        return $this->mSearchObj->search($params);
    }
}
/*
POST /work_shop/work_shop/_mapping 
{
  "work_shop": {
    "properties": {
      "title": {
        "type": "text"
      },
      "city": {
        "type": "text"
      },
      "area": {
        "type": "text"
      },
      "floor": {
        "type": "text"
      },
      "structure": {
        "type": "keyword"
      },
      "measurearea": {
        "type": "keyword"
      },
      "type": {
        "type": "keyword"
      },
      "category": {
        "type": "keyword"
      }
    }
  }
}
POST /off_building/off_building/_mapping 
{
  "off_building": {
    "properties": {
      "title": {
        "type": "text"
      },
      "city": {
        "type": "text"
      },
      "area": {
        "type": "text"
      },
      "plantrent": {
        "type": "text"
      },
      "measurearea": {
        "type": "keyword"
      }
    }
  }
}
array(5) {
  ["from"]=>
  int(0)
  ["size"]=>
  int(10)
  ["index"]=>
  string(9) "work_shop"
  ["type"]=>
  string(9) "work_shop"
  ["body"]=>
  array(2) {
    ["sort"]=>
    array(1) {
      ["_score"]=>
      array(1) {
        ["order"]=>
        string(4) "desc"
      }
    }
    ["query"]=>
    array(1) {
      ["bool"]=>
      array(2) {
        ["must"]=>
        array(1) {
          [0]=>
          array(1) {
            ["term"]=>
            array(1) {
              ["city"]=>
              string(1) "1"
            }
          }
        }
        ["should"]=>
        array(1) {
          [0]=>
          array(1) {
            ["multi_match"]=>
            array(3) {
              ["type"]=>
              string(11) "most_fields"
              ["query"]=>
              string(3) "张"
              ["fields"]=>
              array(1) {
                [0]=>
                string(5) "title"
              }
            }
          }
        }
      }
    }
  }
}

*/