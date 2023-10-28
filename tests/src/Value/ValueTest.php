<?php

use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\Date;
use SaKanjo\EasyMetrics\Metrics\Value;
use SaKanjo\EasyMetrics\Tests\Models\User;
use SaKanjo\EasyMetrics\Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

uses(TestCase::class);

it('shows correct data for count method', function () {
    $sequence = new Sequence(
        ['created_at' => Date::now()->addMonth()],
        ['created_at' => Date::now()->addDays(2)],
        ['created_at' => Date::now()->addDays(1)],
        ['created_at' => Date::now()],
        ['created_at' => Date::now()->subDays(1)],
        ['created_at' => Date::now()->yesterday()],
        ['created_at' => Date::now()->subDays(2)],
        ['created_at' => Date::now()->startOfYear()->addMinute()],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $data = Value::make(User::class)
        ->range(15)
        ->count();

    assertEquals($data, 4);
});

it('shows correct data for average method', function () {
    $sequence = new Sequence(
        ['created_at' => Date::now()->addMonth()],
        ['created_at' => Date::now()->addDays(2)],
        ['created_at' => Date::now()->addDays(1)],
        ['created_at' => Date::now(), 'age' => 20],
        ['created_at' => Date::now()->subDays(1), 'age' => 30],
        ['created_at' => Date::now()->yesterday(), 'age' => 15],
        ['created_at' => Date::now()->subDays(2), 'age' => 40],
        ['created_at' => Date::now()->startOfYear()->addMinute()],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $data = Value::make(User::class)
        ->range(15)
        ->average('age');

    assertEquals($data, 26.25);
});

it('shows correct data for sum method', function () {
    $sequence = new Sequence(
        ['created_at' => Date::now()->addMonth()],
        ['created_at' => Date::now()->addDays(2)],
        ['created_at' => Date::now()->addDays(1)],
        ['created_at' => Date::now(), 'age' => 20],
        ['created_at' => Date::now()->subDays(1), 'age' => 30],
        ['created_at' => Date::now()->yesterday(), 'age' => 15],
        ['created_at' => Date::now()->subDays(2), 'age' => 40],
        ['created_at' => Date::now()->startOfYear()->addMinute()],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $data = Value::make(User::class)
        ->range(15)
        ->sum('age');

    assertEquals($data, 105);
});

it('shows correct data for max method', function () {
    $sequence = new Sequence(
        ['created_at' => Date::now()->addMonth()],
        ['created_at' => Date::now()->addDays(2)],
        ['created_at' => Date::now()->addDays(1)],
        ['created_at' => Date::now(), 'age' => 20],
        ['created_at' => Date::now()->subDays(1), 'age' => 30],
        ['created_at' => Date::now()->yesterday(), 'age' => 15],
        ['created_at' => Date::now()->subDays(2), 'age' => 40],
        ['created_at' => Date::now()->startOfYear()->addMinute()],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $data = Value::make(User::class)
        ->range(15)
        ->max('age');

    assertEquals($data, 40);
});

it('shows correct data for min method', function () {
    $sequence = new Sequence(
        ['created_at' => Date::now()->addMonth()],
        ['created_at' => Date::now()->addDays(2)],
        ['created_at' => Date::now()->addDays(1)],
        ['created_at' => Date::now(), 'age' => 20],
        ['created_at' => Date::now()->subDays(1), 'age' => 30],
        ['created_at' => Date::now()->yesterday(), 'age' => 15],
        ['created_at' => Date::now()->subDays(2), 'age' => 40],
        ['created_at' => Date::now()->startOfYear()->addMinute()],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $data = Value::make(User::class)
        ->range(15)
        ->min('age');

    assertEquals($data, 15);
});
