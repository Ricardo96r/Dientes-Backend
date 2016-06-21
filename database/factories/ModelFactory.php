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

$factory->define(App\Paciente::class, function (Faker\Generator $faker) {
    return [
        'nombre' => $faker->firstName,
        'segundo_nombre' => $faker->firstName,
        'apellido' => $faker->lastName,
        'segundo_apellido' => $faker->lastName,
        'fecha_nacimiento' => $faker->dateTimeBetween('-70 years', '-2 years'),
        'cedula' => $faker->numberBetween('1', '32000000'),
        'ocupacion' => $faker->jobTitle,
        'telefono' => $faker->phoneNumber,
        'telefono_emergencias' => $faker->phoneNumber
    ];
});

$factory->define(App\Paciente::class, function (Faker\Generator $faker) {
    return [
        'nombre' => $faker->firstName,
        'segundo_nombre' => $faker->firstName,
        'apellido' => $faker->lastName,
        'segundo_apellido' => $faker->lastName,
        'fecha_nacimiento' => $faker->dateTimeBetween('-70 years', '-2 years'),
        'cedula' => $faker->numberBetween('1', '32000000'),
        'ocupacion' => $faker->jobTitle,
        'telefono' => $faker->phoneNumber,
        'telefono_emergencias' => $faker->phoneNumber
    ];
});

