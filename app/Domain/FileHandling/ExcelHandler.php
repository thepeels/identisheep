<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 15/02/2017
 * Time: 17:45
 */

namespace App\Domain\FileHandling;


use App\Domain\Sheep\TagNumber;
use Maatwebsite\Excel\Facades\Excel;

class ExcelHandler
{
    /**
     * FileHandler constructor.
     * @param $process_file
     * @param string $original_filename
     */
    public function __construct($process_file,$original_filename)
    {
        $this->original_name = $original_filename;
        $this->loaded_file = Excel::load($process_file,function($decode){
        $this->results = $decode->get();
        });
    }

    public function excelFile()
    {
        foreach($this->results as $row){
            echo ($row->eid."<br>");
        };
        dd('done');
    }

    /**
     * @return array
     * @uses TagNumber
     *
     */
    public function extractTagNumbers()
    {
        $i = 0;
        $tag_list = [];
        foreach ($this->results as $ewe) {
            $tag = new TagNumber($ewe->eid);
            if ($tag->getSerialNumber() != 0) {
                $i++;
                $tag_list[$i][0] = $i;
                $tag_list[$i][1] = $tag->getShortTagNumber();
            }
        }
        return $tag_list;
    }

    /**
     * @return array
     */
    public function returnTagNumbers()
    {
        $i = 0;
        $ewe_list = [];
        foreach ($this->results as $ewe) {
            $i++;
            $ewe_list[$i] = $ewe->eid;
        }
        return $ewe_list;
    }
}