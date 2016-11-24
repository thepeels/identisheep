<?php

use App\User;
class UsersSeeder extends DatabaseSeeder

{
    public function run()
    {
        $users = [
            [
                "id" => "1",
                "name" 			=> "John Corley",
                "password" 		=> Hash::make("notmine"),
                "email"    		=> "john@jjc.me",
                "both_emails"	=> FALSE,
                "superuser"		=> TRUE
            ],
            [
                "id" 			=> "2",
                "name" 			=> "Juliet Corley",
                "password" 		=> Hash::make("pucketty"),
                "email"    		=> "juliet@julietcorley.com",
                "both_emails"	=> FALSE,
                "superuser"		=> TRUE
            ],
            [
                "id" 			=> "3",
                "name" 			=> "Rob Corley",
                "password" 		=> Hash::make("robert"),
                "email"    		=> "rob@corley.co",
                "both_emails"	=> FALSE,
                "superuser"		=> FALSE
            ],
            [
                "id" 			=> "4",
                "name" 			=> "A.N.Other",
                "password" 		=> Hash::make("m¡ne20!5"),
                "email"    		=> "other@corley.co",
                "both_emails"	=> FALSE,
                "superuser"		=> FALSE
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}