<?php

use App\Models\Sheep;
class SheepSeeder extends DatabaseSeeder

{
    public function run()
    {
        $ewes = [
            [
                'user_id'       =>  "1",
                'move_on'       =>  date('Y-m-d'),
                'move_off'      =>  "00-00-0000",
                'e_flock'       =>  'UK0109680',
                'original_e_flock'  =>'UK0109680',
                'colour_flock'      =>'UK0109680',
                'e_tag'         =>  "1",
                'original_e_tag'=>  "1",
                'colour_tag'    =>  "1"
            ],
            [
                'user_id'       =>  "2",
                'move_on'       =>  date('Y-m-d'),
                'move_off'      =>  "00-00-0000",
                'e_flock'       =>  'UK0106374',
                'original_e_flock'  =>'UK0106374',
                'colour_flock'      =>'UK0106374',
                'e_tag'         =>  "1",
                'original_e_tag'=>  "1",
                'colour_tag'    =>  "1"
            ],
            [
                'user_id'       =>  "2",
                'move_on'       =>  date('Y-m-d'),
                'move_off'      =>  "00-00-0000",
                'e_flock'       =>  'UK0106374',
                'original_e_flock'  =>'UK0106374',
                'colour_flock'      =>'UK0106374',
                'e_tag'         =>  "2",
                'original_e_tag'=>  "2",
                'colour_tag'    =>  "2"
            ],
            [
                'user_id'       =>  "1",
                'move_on'       =>  date('Y-m-d'),
                'move_off'      =>  "00-00-0000",
                'e_flock'       =>  'UK0106374',
                'original_e_flock'  =>'UK0106374',
                'colour_flock'      =>'UK0106374',
                'e_tag'         =>  "3",
                'original_e_tag'=>  "3",
                'colour_tag'    =>  "3"
            ],
            [
                'user_id'       =>  "1",
                'move_on'       =>  date('Y-m-d'),
                'move_off'      =>  "00-00-0000",
                'e_flock'       =>  'UK0109680',
                'original_e_flock'  =>'UK0109680',
                'colour_flock'      =>'UK0109680',
                'e_tag'         =>  "2",
                'original_e_tag'=>  "2",
                'colour_tag'    =>  "2"
            ]
        ];

        foreach ($ewes as $sheep) {
            Sheep::create($sheep);
        }
    }
}