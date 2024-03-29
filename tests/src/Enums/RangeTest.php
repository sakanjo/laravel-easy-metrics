<?php

use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\Date;
use SaKanjo\EasyMetrics\Enums\Range;
use SaKanjo\EasyMetrics\Metrics\Value;
use SaKanjo\EasyMetrics\Tests\Models\User;
use SaKanjo\EasyMetrics\Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

uses(TestCase::class);

it('shows correct data count by RANGE::TODAY', function () {
    $sequence = new Sequence(
        ['created_at' => Date::now()->addMonth()],
        ['created_at' => Date::now()->addDays(2)],
        ['created_at' => Date::now()->addDays(1)],
        ['created_at' => Date::now()], // This one
        ['created_at' => Date::now()->subDays(1)],
        ['created_at' => Date::now()->subDays(2)],
        ['created_at' => Date::now()->subMonth()],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $data = Value::make(User::class)
        ->range(Range::TODAY)
        ->count();

    assertEquals($data, 1);
});

it('shows correct data count by RANGE::YESTERDAY', function () {
    $sequence = new Sequence(
        ['created_at' => Date::now()->addMonth()],
        ['created_at' => Date::now()->addDays(2)],
        ['created_at' => Date::now()->addDays(1)],
        ['created_at' => Date::now()],
        ['created_at' => Date::now()->subDays(1)], // This one
        ['created_at' => Date::now()->yesterday()], // This one
        ['created_at' => Date::now()->subDays(2)],
        ['created_at' => Date::now()->subMonth()],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $data = Value::make(User::class)
        ->range(Range::YESTERDAY)
        ->count();

    assertEquals($data, 2);
});

it('shows correct data count by RANGE::MTD', function () {
    $sequence = new Sequence(
        ['created_at' => Date::now()->addMonth()],
        ['created_at' => Date::now()->addDays(2)],
        ['created_at' => Date::now()->addDays(1)],
        ['created_at' => Date::now()], // This one
        ['created_at' => Date::now()->subDays(1)], // This one
        ['created_at' => Date::now()->yesterday()], // This one
        ['created_at' => Date::now()->subDays(2)], // This one
        ['created_at' => Date::now()->subMonth()],
        ['created_at' => Date::now()->subMonth()->subMinute()],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $data = Value::make(User::class)
        ->range(Range::MTD)
        ->count();

    assertEquals($data, 4);
});

it('shows correct data count by RANGE::QTD', function () {
    $sequence = new Sequence(
        ['created_at' => Date::now()->addMonth()],
        ['created_at' => Date::now()->addDays(2)],
        ['created_at' => Date::now()->addDays(1)],
        ['created_at' => Date::now()], // This one
        ['created_at' => Date::now()->subDays(1)], // This one
        ['created_at' => Date::now()->yesterday()], // This one
        ['created_at' => Date::now()->subDays(2)], // This one
        ['created_at' => Date::now()->startOfQuarter()->addMinute()], // This one
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $data = Value::make(User::class)
        ->range(Range::QTD)
        ->count();

    assertEquals($data, 5);
});

it('shows correct data count by RANGE::YTD', function () {
    $sequence = new Sequence(
        ['created_at' => Date::now()->addMonth()],
        ['created_at' => Date::now()->addDays(2)],
        ['created_at' => Date::now()->addDays(1)],
        ['created_at' => Date::now()], // This one
        ['created_at' => Date::now()->subDays(1)], // This one
        ['created_at' => Date::now()->yesterday()], // This one
        ['created_at' => Date::now()->subDays(2)], // This one
        ['created_at' => Date::now()->startOfYear()->addMinute()], // This one
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $data = Value::make(User::class)
        ->range(Range::YTD)
        ->count();

    assertEquals($data, 5);
});

it('shows correct data count by RANGE::ALL', function () {
    $sequence = new Sequence(
        ['created_at' => Date::now()->addMonth()], // This one
        ['created_at' => Date::now()->addDays(2)], // This one
        ['created_at' => Date::now()->addDays(1)], // This one
        ['created_at' => Date::now()], // This one
        ['created_at' => Date::now()->subDays(1)], // This one
        ['created_at' => Date::now()->yesterday()], // This one
        ['created_at' => Date::now()->subDays(2)], // This one
        ['created_at' => Date::now()->startOfYear()->addMinute()], // This one
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $data = Value::make(User::class)
        ->range(Range::ALL)
        ->count();

    assertEquals($data, 8);
});

it('shows correct data count by 15 days', function () {
    $sequence = new Sequence(
        ['created_at' => Date::now()->addMonth()],
        ['created_at' => Date::now()->addDays(2)],
        ['created_at' => Date::now()->addDays(1)],
        ['created_at' => Date::now()], // This one
        ['created_at' => Date::now()->subDays(1)], // This one
        ['created_at' => Date::now()->yesterday()], // This one
        ['created_at' => Date::now()->subDays(2)], // This one
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

it('shows correct data count by 5 days', function () {
    $sequence = new Sequence(
        ['created_at' => Date::now()->addMonth()],
        ['created_at' => Date::now()->addDays(2)],
        ['created_at' => Date::now()->addDays(1)],
        ['created_at' => Date::now()], // This one
        ['created_at' => Date::now()->yesterday()], // This one
        ['created_at' => Date::now()->subDays(6)]
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $data = Value::make(User::class)
        ->ranges([5])
        ->count();

    assertEquals($data, 2);
});
