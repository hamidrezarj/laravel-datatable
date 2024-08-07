<?php

namespace HamidRrj\LaravelDatatable\Database\Factories;

use HamidRrj\LaravelDatatable\Tests\Models\Post;
use HamidRrj\LaravelDatatable\Tests\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;


class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        return [
            'title' => $this->faker->name(),
            'description' => $this->faker->text(),
            'user_id' => User::factory(),
        ];

    }
}

