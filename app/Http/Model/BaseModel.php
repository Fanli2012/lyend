<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BaseModel extends Model
{
    // 打印SQL DB::table('article')->orderBy(DB::raw('rand()'))->toSql();
    //获取某一表的所有字段
    public static function getColumnListing($table)
    {
        return Schema::getColumnListing($table);
    }

    //过滤不是某一表的字段、过滤空值
    public static function filterTableColumn($data, $table)
    {
        $table_column = Schema::getColumnListing($table);

        if (!$table_column) {
            return $data;
        }

        foreach ($data as $k => $v) {
            if (!in_array($k, $table_column)) {
                unset($data[$k]);
            }
            /* else {
                //过滤空值
                if ($data[$k] == '') {
                    unset($data[$k]);
                }
            } */
        }

        return $data;
    }

    //获取排序参数
    public static function getOrderByData($model, $orderby)
    {
        if ($orderby == 'rand()') {
            $model = $model->orderBy(\DB::raw('rand()'));
        } else {
            if (count($orderby) == count($orderby, 1)) { //一维数组
                $model = $model->orderBy($orderby[0], $orderby[1]);
            } else { //二维数组
                foreach ($orderby as $row) {
                    $model = $model->orderBy($row[0], $row[1]);
                }
            }
        }

        return $model;
    }

    //过滤数据表中的某些字段
    public static function filterTableFields($table, $data)
    {
        $table_column = Schema::getColumnListing($table);

        if (!$data) {
            return $table_column;
        }
        if (!$table_column) {
            return array();
        }

        foreach ($table_column as $k => $v) {
            if (in_array($v, $data)) {
                unset($table_column[$k]);
            }
        }

        return $table_column;
    }

}