<?php

use Illuminate\Database\Seeder;
use Models\Group;

class GroupTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('groups')->delete();
        Group::create([
            'code'  =>'administrator',
            'name'  => 'Administrator',
            'status' => true
        ]);
        Group::create([
            'code'  =>'member',
            'name'  => 'Member',
            'status' => true
        ]);
    }

}
