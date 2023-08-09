<?php

namespace App\Helpers;

use App\Models\Patient;
use Illuminate\Support\Facades\Schema;

class QueryHelper
{
    public static function searchableColumns($query, $exceptColumns = null)
    {
        $model = $query->getModel();
        $tableName = $model->getTable();
        $allColumns = Schema::getColumnListing($tableName);
        return $exceptColumns == null ? $allColumns : array_diff_key($allColumns, array_flip($exceptColumns));
    }

    public static function applyFilter($query, $request, $searchableColumns)
    {
        $filterColumns = $request->only($searchableColumns);
        $filterOperator = $request->filter_operator ?? 'and';
        $searchText = $request->search_text;

        if (!empty($searchText)) {
            $query->where(function ($query) use ($searchText, $searchableColumns, $filterOperator) {
                foreach ($searchableColumns as $column) {
                    $query->orWhere($column, 'like', "%$searchText%");
                }
            });
        } else {
            if (!empty($filterColumns)) {
                $query->where(function ($query) use ($filterColumns, $filterOperator) {
                    foreach ($filterColumns as $column => $value) {
                        if (!empty($value)) {
                            $query->where($column, 'like', "%$value%");

                            if ($filterOperator === 'or') {
                                $query->orWhere($column, 'like', "%$value%");
                            }
                        }
                    }
                });
            }
        }

        return $query;
    }

    public static function applyOrder($query, $request, $orderableColumns)
    {
        $order = $request->order === 'asc' ? 'asc' : 'desc';
        $orderColumn = $request->order_column ?? 'id';

        if (in_array($orderColumn, $orderableColumns)) {
            $query->orderBy($orderColumn, $order);
        } else {
            $query->orderBy('id', $order);
        }

        return $query;
    }

    public static function applyLimit($query, $request)
    {
        if($request->limit == 0){
            return $query;
        }
        $limit = $request->limit ?? 10;
        $query->limit($limit);

        return $query;
    }

    public static function applyBetween($query, $request, $searchableColumns)
    {
        $start = $request->start;
        $end = $request->end ?: $request->start;
        $dateColumn = $request->date_column;
        if (in_array($dateColumn, $searchableColumns) && $start) {
            $query->whereBetween($dateColumn, [$start, $end]);
        }
        return $query;
    }

    public static function applyRelationalData($query, $request, $searchableColumns)
    {
        $value = $request->relational_id;
        $relationalColumn = $request->relational_column;
        if (in_array($relationalColumn, $searchableColumns) && $value) {
            $query->where($relationalColumn, $value);
        }
        return $query;
    }

    

    public static function applyWithRelation( $query, $relation)
    {
        if (!empty($relation)) {
            $query->with($relation);
        }
        return $query;
    }

    public static function applyPagination($query, $request)
    {
        $pagination = $request->pagination ?? false;
        $perPage = $request->perPage ?? 10;

        if ($pagination) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }

    public static function applyFilterOrderLimitPagination($query, $request, $relation = null, $exceptColumns = null)
    {
        $searchableColumns = self::searchableColumns($query,$exceptColumns);
        $query = self::applyFilter($query, $request,$searchableColumns);
        $query = self::applyOrder($query, $request,$searchableColumns);
        $query = self::applyLimit($query, $request);
        $query = self::applyBetween($query, $request, $searchableColumns);
        $query = self::applyRelationalData($query, $request, $searchableColumns);
        $query = self::applyWithRelation( $query, $relation);
        $query = self::applyPagination($query, $request);

        return $query;
    }
}
