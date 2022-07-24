<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $birthday = $this->faker->dateTimeBetween('1990-01-01', '2012-12-31')->format('Y-m-d');

        return [
            'name' => $this->faker->name(),
            'birthday' => $birthday,
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make($birthday),
            'level' => rand(1, 4),
            'added_by' => 1,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
            ];
        });
    }
}
