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

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'user_name' => $faker->userName,
        'email' => $faker->email,
        'phone' => $faker->phoneNumber,
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'location' => $faker->city,
        'password' => $faker->password
    ];
});
