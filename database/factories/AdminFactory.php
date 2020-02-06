<?php

use Faker\Generator as Faker;

$factory->define(Admin::class, function (Faker $faker) {
    return [
      'national_id' =>str_random(10),

    ];
});
