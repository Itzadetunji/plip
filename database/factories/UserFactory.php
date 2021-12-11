<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
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
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'middle_name' => "A",
            'nick_name' => $this->faker->word(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt("Password22"),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function emailVerified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => now(),
            ];
        });
    }



    /**
     * Indicate that the model's should have an avatar.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withAvatar()
    {
        return $this->state(function (array $attributes) {
            return [
                'avatar' => $this->faker->image(
                    "media/profile"
                ),
            ];
        });
    }
}
