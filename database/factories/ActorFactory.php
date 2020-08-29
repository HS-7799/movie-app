<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Actor;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Actor::class, function (Faker $faker) {
    $name = $faker->name;
    return [

        'name' => $name,
        'slug' => Str::slug($name),
        'biographie' => $faker->paragraph,
        'photo' => null
    ];
});
