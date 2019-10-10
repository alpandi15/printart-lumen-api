<?php

namespace App\Http\Services\Utils;

use Laravel\Lumen\Routing\Controller as BaseController;

class Query extends BaseController
{
  public static function filterAllowedField($allowed, $fields) {
    $filtered = array_filter(
      $fields,
      function ($key) use ($allowed) {
        return in_array($key, $allowed);
      },
      ARRAY_FILTER_USE_KEY
    );

    return $filtered;
  }

  public static function filterDisallowField($query, $filtered) {
    return array_diff($query, $filtered);
  }

  public static function Paginate($model, $query = null, $fields = null) {
    $allowed = [
      'page',
      'limit',
      'keyword',
      'sort',
      'field',
      'relationship',
      'type',
      'from',
      'to',
      'offset',
      'paginate',
    ];
    
    $filtered = Query::filterAllowedField($allowed, $query);
    $otherFilter = Query::filterDisallowField($query, $filtered);

    $data = $model;
    $limit = isset($filtered['limit']) ? intval($filtered['limit']) : 10;
    $page = isset($filtered['page']) ? intval($filtered['page']) : 1;
    $keyword = isset($filtered['keyword']) ? $filtered['keyword'] : null;
    $sort = isset($filtered['sort']) ? $filtered['sort'] : null;
    $field = isset($query['field']) ? explode(",", $query['field']) : null;
    $relationship = isset($filtered['relationship']) ? intval($filtered['relationship']) : 0;
    $type = isset($filtered['type']) ? $filtered['type'] : null;
    $from = isset($filtered['from']) ? $filtered['from'] : null;
    $to = isset($filtered['to']) ? $filtered['to'] : null;
    $offset = isset($filtered['offset']) ? intval($filtered['offset']) : 0;
    $totalData = Query::countData($model, $query, $fields);
    
    if ($page < 1) { $page = 1; }
    $offset = ($page - 1) * $limit;

    if (!$field) $field = $fields;

    $data = $data->where(function($q) use ($fields, $keyword, $otherFilter) {
      if ($keyword) {
        foreach($fields as $column) {
          $q = $q->orWhere($column,'LIKE','%'.strtoupper($keyword).'%');
        }
      }
      $q->where($otherFilter);
    });
    $data = $type !== 'all' ? $data->offset($offset) : $data;
    $data = $type !== 'all' ? $data->limit($limit) : $data;
    $data = $sort ? $data->orderByRaw($sort) : $data;
    $data = $data->select($field ? $field : '*');
    $resData = $data->get();

    $paginate = [
      "keyword" => $keyword,
      "totalData" => $totalData,
      "perPage" => $type !== 'all' ? $limit : $totalData,
      "lastPage" => $type !== 'all' ? ceil($totalData/$limit) : 1,
      "currentPage" => $type !== 'all' ? $page : 1,
      "data" => $resData
    ];
    
    return $paginate;
  }

  public static function countData($model, $query = null, $fields = null) {
    $allowed = [
      'page',
      'limit',
      'keyword',
      'sort',
      'field',
      'relationship',
      'type',
      'from',
      'to',
      'offset',
      'paginate',
    ];
    
    $filtered = Query::filterAllowedField($allowed, $query);
    $otherFilter = Query::filterDisallowField($query, $filtered);

    $data = $model;
    $limit = isset($filtered['limit']) ? intval($filtered['limit']) : 10;
    $page = isset($filtered['page']) ? intval($filtered['page']) : 1;
    $keyword = isset($filtered['keyword']) ? $filtered['keyword'] : null;
    $sort = isset($filtered['sort']) ? $filtered['sort'] : null;
    $field = isset($query['field']) ? explode(",", $query['field']) : null;
    $relationship = isset($filtered['relationship']) ? intval($filtered['relationship']) : 0;
    $type = isset($filtered['type']) ? $filtered['type'] : null;
    $from = isset($filtered['from']) ? $filtered['from'] : null;
    $to = isset($filtered['to']) ? $filtered['to'] : null;
    $offset = isset($filtered['offset']) ? intval($filtered['offset']) : 0;
    
    if ($page < 1) { $page = 1; }
    $offset = ($page - 1) * $limit;

    if (!$field) $field = $fields;

    $data = $data->where(function($q) use ($fields, $keyword, $otherFilter) {
      if ($keyword) {
        foreach($fields as $column) {
          $q = $q->orWhere($column,'LIKE','%'.strtoupper($keyword).'%');
        }
      }
      $q->where($otherFilter);
    });
    $resCount = $data->count();
    
    return $resCount;
  }
}
