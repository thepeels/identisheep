<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 31/01/2017
 * Time: 16:18
 */

namespace App\Domain\FileHandling;


use App\Domain\Sheep\TagNumber;

class FileHandler
{
    /**
     * FileHandler constructor.
     * @param $process_file
     */
    public function __construct($process_file)
    {
        $this->ewelist = array_map('str_getcsv', ($process_file));
    }

    /**
     * @return array
     */
    public function mappedFile()
    {
        return $this->ewelist;
    }

    /**
     * @return array
     */
    public function extractTagNumbers()
    {
        $i = 0;
        $tag_list = [];
        foreach ($this->ewelist[2] as $ewe) {
            $tag = new TagNumber($ewe);
            if ($tag->getSerialNumber() != 0) {
                $i++;
                $tag_list[$i][0] = $i;
                $tag_list[$i][1] = $tag->getShortTagNumber();
            }
        }

        return $tag_list;
    }
}