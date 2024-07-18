<?php

namespace HamidRrj\LaravelDatatable\Database\Factories;

use HamidRrj\LaravelDatatable\Tests\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;


class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'username' => $this->faker->userName(),
            'email' => $this->faker->email,
//            'position' => $this->faker->randomElement(['boss']),
//            'city_id' => City::factory(),
//            'province_id' => function (array $attributes) {
//                return City::find($attributes['city_id'])->province_id;
//            },
//            'city_name' => function (array $attributes) {
//                return City::find($attributes['city_id'])->name;
//            },

        ];

    }
}

