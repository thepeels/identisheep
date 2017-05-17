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
     * ExcelHandler constructor.
     * @param $process_file
     * @param $original_filename
     */
    public function __construct($process_file,$original_filename)
    {
        $this->original_name = $original_filename;
        $this->loaded_file = Excel::load($process_file,function($decode){
        $this->results = $decode->get();
        });
    }

    /**
     *
     */
    public function excelFile()
    {
        foreach($this->results as $row){
            echo ($row->eid."<br>");
        };

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
        foreach ($this->results as $row) {
            $tag = new TagNumber($row->eid);
            if ($tag->getSerialNumber() != 0) {
                $i++;
                $tag_list[$i][0] = $i;
                $tag_list[$i][1] = $tag->getCountryCode();
                $tag_list[$i][2] = $tag->getShortTagNumber();
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

        foreach ($this->results as $row) {
            $i++;
            $ewe_list[$i] = $row->eid;
        }

        return $ewe_list;
    }
}