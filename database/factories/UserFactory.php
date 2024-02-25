<?php

namespace Database\Factories;

use App\Enums\UserStatus;
use App\Enums\UserTypes;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => Carbon::now(),
            'password' => 'password',
            'type' => $this->faker->randomElement(UserTypes::cases()),
            'status' => $this->faker->randomElement(UserStatus::cases()),
            'remember_token' => Str::random(10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    public function unverified(): UserFactory
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null
        ]);
    }

    public function active(): UserFactory
    {
        return $this->state(fn (array $attributes) => [
            'status' => UserStatus::ACTIVE
        ]);
    }

    public function inactive(): UserFactory
    {
        return $this->state(fn (array $attributes) => [
            'status' => UserStatus::INACTIVE
        ]);
    }

    public function pending(): UserFactory
    {
        return $this->state(fn (array $attributes) => [
            'status' => UserStatus::PENDING
        ]);
    }

    public function internal(): UserFactory
    {
        return $this->state(fn (array $attributes) => [
            'type' => UserTypes::INTERNAL
        ]);
    }

    public function external(): UserFactory
    {
        return $this->state(fn (array $attributes) => [
            'type' => UserTypes::EXTERNAL
        ]);
    }
}
