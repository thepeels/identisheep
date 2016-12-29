<?php

use App\Models\Sheep;
class SheepSeeder extends DatabaseSeeder

{
    public function run()
    {
        $ewes = [
            [
                'owner'       =>  "1",
                'local_id'      =>  "1",
                'move_on'       =>  date('Y-m-d'),
                'alive'         =>  "1",
                'move_off'      =>  "0000-00-00 00:00:00",
                'flock_number'       =>  '109680',
                'original_flock_number'  =>'109680',
                'supplementary_tag_flock_number'      =>'109680',
                'serial_number'         =>  "1",
                'original_serial_number'=>  "1",
                'tag_colour'    =>  "Green",
                'sex'           =>  'female'
            ],
            [
                'owner'       =>  "2",
                'local_id'      =>  "1",
                'move_on'       =>  date('Y-m-d'),
                'alive'         =>  "1",
                'move_off'      =>  "0000-00-00 00:00:00",
                'flock_number'       =>  '106374',
                'original_flock_number'  =>'106374',
                'supplementary_tag_flock_number'      =>'106374',
                'serial_number'         =>  "1",
                'original_serial_number'=>  "1",
                'tag_colour'    =>  "Green",
                'sex'           =>  'female'
            ],
            [
                'owner'       =>  "2",
                'local_id'      =>  "2",
                'move_on'       =>  date('Y-m-d'),
                'alive'         =>  "1",
                'move_off'      =>  "0000-00-00 00:00:00",
                'flock_number'       =>  '106374',
                'original_flock_number'  =>'106374',
                'supplementary_tag_flock_number'      =>'106374',
                'serial_number'         =>  "2",
                'original_serial_number'=>  "2",
                'tag_colour'    =>  "Green",
                'sex'           =>  'female'
            ],
            [
                'owner'       =>  "1",
                'local_id'      =>  "2",
                'move_on'       =>  date('Y-m-d'),
                'alive'         =>  "1",
                'move_off'      =>  "0000-00-00 00:00:00",
                'flock_number'       =>  '106374',
                'original_flock_number'  =>'106374',
                'supplementary_tag_flock_number'      =>'106374',
                'serial_number'         =>  "3",
                'original_serial_number'=>  "3",
                'tag_colour'    =>  "Green",
                'sex'           =>  'female'
            ],
            [
                'owner'       =>  "1",
                'local_id'      =>  "3",
                'move_on'       =>  date('Y-m-d'),
                'alive'         =>  "1",
                'move_off'      =>  "0000-00-00 00:00:00",
                'flock_number'       =>  '109680',
                'original_flock_number'  =>'109680',
                'supplementary_tag_flock_number'      =>'109680',
                'serial_number'         =>  "2",
                'original_serial_number'=>  "2",
                'tag_colour'    =>  "Green",
                'sex'           =>  'female'
            ]
        ];

        foreach ($ewes as $sheep) {
            Sheep::create($sheep);
        }
    }
}