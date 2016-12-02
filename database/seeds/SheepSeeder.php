<?php

use App\Models\Sheep;
class SheepSeeder extends DatabaseSeeder

{
    public function run()
    {
        $ewes = [
            [
                'user_id'       =>  "1",
                'local_id'      =>  "1",
                'move_on'       =>  date('Y-m-d'),
                'move_off'      =>  "0000-00-00 00:00:00",
                'e_flock'       =>  '109680',
                'original_e_flock'  =>'109680',
                'colour_flock'      =>'109680',
                'e_tag'         =>  "1",
                'original_e_tag'=>  "1",
                'colour_tag'    =>  "1",
                'sex'           =>  'female'
            ],
            [
                'user_id'       =>  "2",
                'local_id'      =>  "1",
                'move_on'       =>  date('Y-m-d'),
                'move_off'      =>  "0000-00-00 00:00:00",
                'e_flock'       =>  '106374',
                'original_e_flock'  =>'106374',
                'colour_flock'      =>'106374',
                'e_tag'         =>  "1",
                'original_e_tag'=>  "1",
                'colour_tag'    =>  "1",
                'sex'           =>  'female'
            ],
            [
                'user_id'       =>  "2",
                'local_id'      =>  "2",
                'move_on'       =>  date('Y-m-d'),
                'move_off'      =>  "0000-00-00 00:00:00",
                'e_flock'       =>  '106374',
                'original_e_flock'  =>'106374',
                'colour_flock'      =>'106374',
                'e_tag'         =>  "2",
                'original_e_tag'=>  "2",
                'colour_tag'    =>  "2",
                'sex'           =>  'female'
            ],
            [
                'user_id'       =>  "1",
                'local_id'      =>  "2",
                'move_on'       =>  date('Y-m-d'),
                'move_off'      =>  "0000-00-00 00:00:00",
                'e_flock'       =>  '106374',
                'original_e_flock'  =>'106374',
                'colour_flock'      =>'106374',
                'e_tag'         =>  "3",
                'original_e_tag'=>  "3",
                'colour_tag'    =>  "3",
                'sex'           =>  'female'
            ],
            [
                'user_id'       =>  "1",
                'local_id'      =>  "3",
                'move_on'       =>  date('Y-m-d'),
                'move_off'      =>  "0000-00-00 00:00:00",
                'e_flock'       =>  '109680',
                'original_e_flock'  =>'109680',
                'colour_flock'      =>'109680',
                'e_tag'         =>  "2",
                'original_e_tag'=>  "2",
                'colour_tag'    =>  "2",
                'sex'           =>  'female'
            ]
        ];

        foreach ($ewes as $sheep) {
            Sheep::create($sheep);
        }
    }
}