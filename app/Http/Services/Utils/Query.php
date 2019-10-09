<?php

namespace App\Http\Services\Utils;

use Laravel\Lumen\Routing\Controller as BaseController;

class Query extends BaseController
{
  protected static function filterAllowedField($allowed, $fields) {
    $filtered = array_filter(
      $fields,
      function ($key) use ($allowed) {
        return in_array($key, $allowed);
      },
      ARRAY_FILTER_USE_KEY
    );

    return $filtered;
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
    // return $filtered;

    $otherFilter = array_diff($query, $filtered);

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
    $data = $data->offset($offset);
    $data = $data->limit($limit);
    $data = $sort ? $data->orderByRaw($sort) : $data;
    $data = $data->select($field ? $field : '*');
    $resData = $data->get();

    $paginate = [
      "keyword" => $keyword,
      "totalData" => $resData->count(),
      "perPage" => $limit,
      "lastPage" => 1,
      "currentPage" => $page,
      "data" => $resData
    ];
    
    return $paginate;
  }
}
