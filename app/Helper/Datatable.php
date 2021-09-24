<?php


namespace App\Helper;


/**
 * Responsible for showing data in datatable and exporting the data in administration part
 * Class EloquentQueryHelper
 * @package App\Helper
 */
class Datatable
{

    /**
     * Simplify the query for the vuetify parameter
     * @param $model
     * @param array $search_columns
     * @param null $query
     * @param bool $start_with_or_where
     * @return mixed
     */
    public static function filter($model,$search_columns,$start_with_or_where=false)
    {
        $per_page = request('per_page')?request('per_page'):25;


        $query = request('search_keyword');
        if ($query && count($search_columns)>0) {
            $model = $model->where(function ($q) use ($query, $search_columns, $start_with_or_where) {
                if ($start_with_or_where) {
                    $model = $q->orWhere($search_columns[0], 'LIKE', '%' . $query . '%');
                } else {
                    $model = $q->where($search_columns[0], 'LIKE', '%' . $query . '%');
                }
                foreach ($search_columns as $column) {
                    $model = $model->orWhere($column, 'LIKE', '%' . $query . '%');
                }
                return $model;
            });
        }
        return $model->paginate($per_page);


        // $rowsPerPage = request('size') ? request('size') : 20;

        // if (request()->has('sortBy') && request('sortBy')!="null") {
        //   $desc = 'ASC';
        //   if(request('descending') == 'true'){
        //     $desc = 'DESC';
        //   }

        //     $model = $model->orderBy(request('sortBy'), $desc);

        // }
        // else{
        //     $model = $model->orderBy('created_at', 'DESC');
        // }

        // if ($rowsPerPage == -1) {
        //     $rowsPerPage = $model->count();
        // }
        // $data = $model->paginate($rowsPerPage);
        // return $data;
    }

}
