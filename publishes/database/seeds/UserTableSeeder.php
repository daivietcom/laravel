<?php

use Illuminate\Database\Seeder;
use Models\User;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->delete();
        User::create([
            'email'         => 'demo.vns@gmail.com',
            'phone'         => '0986901797',
            'display_name'  => 'Mr. VnS',
            'status'        => true,
            'group_code'    => 'administrator',
            'api_token'     => str_random(60),
            'password'      => bcrypt('123456'),
        ]);
    }
}
