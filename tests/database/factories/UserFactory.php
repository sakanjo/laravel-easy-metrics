<?php

namespace SaKanjo\EasyMetrics\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use SaKanjo\EasyMetrics\Tests\Enums\Gender;
use SaKanjo\EasyMetrics\Tests\Models\User;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => Date::now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'gender' => $this->faker->randomElement(Arr::pluck(Gender::cases(), 'value')),
            'age' => $this->faker->numberBetween(18, 60),
            'remember_token' => Str::random(10),
        ];
    }
}
