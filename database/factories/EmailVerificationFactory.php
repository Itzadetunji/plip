<?php

namespace Database\Factories;

use App\Models\EmailVerification;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmailVerificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "email" => $this->faker->safeEmail(),
            "token" => (new EmailVerification())->generateToken()
        ];
    }

    /**
     * Indicate that the model's email address should be verified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function verified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => now()
            ];
        });
    }
}
