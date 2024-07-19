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
            'age' => $this->faker->numberBetween(1, 100),
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

