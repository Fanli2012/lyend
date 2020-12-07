<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class Export extends StringValueBinder implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithCustomValueBinder, WithStrictNullComparison, WithEvents
{
    private $row;
    private $data;
    private $mergeCell;
    private $columnName;
    private $formatNumber;

    /**
     * $mergeCell $columnName ：合并单元格所需参数;
     * $mergeCell 需要合并的位置数组以MAP形式存储 [开始行=>结束行]
     * $columnName 需要合并列 与合并行数结合使用ARRAY存储 ['A','B']
     */
    public function __construct($row, $data, $mergeCell = null, $columnName = null, $formatNumber = [])
    {
        $this->row = $row;
        $this->data = $data;
        $this->mergeCell = $mergeCell;
        $this->columnName = $columnName;
        $this->formatNumber = $formatNumber;
    }

    public function collection()
    {
        $row = $this->row;
        $data = $this->data;

        //设置表头
        foreach ($row[0] as $key => $value) {
            $key_arr[] = $key;
        }
        //输入数据
        foreach ($data as $key => &$value) {
            $js = [];
            for ($i = 0; $i < count($key_arr); $i++) {
                $js = array_merge($js, [$key_arr[$i] => $value[$key_arr[$i]]]);
            }
            array_push($row, $js);
            unset($val);
        }
        return collect($row);
    }

    public function registerEvents(): array
    {
        // TODO: Implement registerEvents() method.
        if ($this->mergeCell && $this->columnName) {
            return [
                AfterSheet::class => function (AfterSheet $event) {
                    foreach ($this->columnName as $column) {
                        foreach ($this->mergeCell as $key => $value) {
                            $event->sheet->getDelegate()->mergeCells($column . $key . ':' . $column . $value);
                        }
                    }
                }
            ];
        }
        return [];
    }


    public function columnFormats(): array
    {
        $formatNumber = [];
        foreach ($this->formatNumber as $column) {
            $formatNumber[$column] = NumberFormat::FORMAT_TEXT;
        }
        return $formatNumber;
    }

}

/*
//表头表体都为二维数组
$row = [['row1'=>'列1','row2'=>'列2']];
//与表头key对应，缺少数据报错
$list = [['row1'=>'行1列1','row2'=>'行1列2'],['row1'=>'行2列1', 'row2'=>'行2列2']];
//将第一行到第三行，第五行到第七行的A,B,C列各自合并
$mergeCell = [1=>3, 5=>7];
$columnName = ["A","B","C"];
//数字过长的列转换格式防止科学计数
$formatNumber = ['A','B','C'];
//上方A,B,C列都为示意，根据自己需求调整，对应EXCEL的列
return Excel::download(new Export($row, $list, $mergeCell, $columnName, $formatNumber), 'fileName');
*/