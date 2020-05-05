<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'email'         => $faker->safeEmail,
        'phone'         => $faker->phone,
        'display_name'  => $faker->name,
        'status'        => true,
        'group_code'    => 'member',
        'api_token'     => str_random(60),
        'remember_token' => str_random(10),
        'password'      => $password ?: $password = bcrypt('secret'),
    ];
});

$factory->define(Models\Comment::class, function (Faker\Generator $faker) {
    $faker = Faker\Factory::create('vi_VN');
    return [
        'content' => $faker->realText,
        'post_id' => 1,
        'author' => [
          'name' => $faker->name,
          'email' => $faker->safeEmail,
          'phone' => $faker->phoneNumber,
        ],
        'parent' => null,
        'status'  => true
    ];
});
