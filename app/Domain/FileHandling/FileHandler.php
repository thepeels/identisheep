<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 15/02/2017
 * Time: 08:34
 */

namespace App\Domain\FileHandling;


use App\Domain\Sheep\TagNumber;

class FileHandler
{
    /**
     * FileHandler constructor.
     * @param string $process_file
     * @param string $original_filename
     */
    public function __construct($process_file,$original_filename)
    {
        $this->ewelist = str_replace(array("\r\n","\n\r","\n", "\r", "¶"), "", $process_file);
        $this->original_name = $original_filename;
    }

    /**
     * @return array
     */
    public function mappedFile()
    {
        return $this->ewelist;
    }

    /**
     * @return string
     */
    public function originalName()
    {
        return $this->original_name;
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
        foreach ($this->ewelist as $ewe) {
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