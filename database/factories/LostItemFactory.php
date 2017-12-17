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

$factory->define(App\Models\LostItem::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->text(10),
        'found' => $faker->boolean(20),
        'category' => $faker->numberBetween(1, 4),
        'finder' => function (array $lostItem) use($faker) {
            return $lostItem['found'] ? $faker->numberBetween(1, 10) : $faker->randomElement([$faker->numberBetween(1, 10), null]);
        },
        'owner' => function (array $lostItem) use ($faker) {
            return $lostItem['found'] ? $faker->numberBetween(1, 10) : ($lostItem['finder'] ? null : $faker->numberBetween(1, 10));
        }
    ];
});
