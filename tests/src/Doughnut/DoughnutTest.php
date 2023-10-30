<?php

use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\Date;
use SaKanjo\EasyMetrics\Enums\GrowthRateType;
use SaKanjo\EasyMetrics\Enums\Range;
use SaKanjo\EasyMetrics\Metrics\Doughnut;
use SaKanjo\EasyMetrics\Tests\Enums\Gender;
use SaKanjo\EasyMetrics\Tests\Models\User;
use SaKanjo\EasyMetrics\Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

uses(TestCase::class);

it('shows all options when using `options` method with inital value of 0', function () {
    $sequence = new Sequence(
        ['gender' => Gender::Male],
        ['gender' => Gender::Male],
        // ['gender' => Gender::Female],
        ['gender' => Gender::Male],
        // ['gender' => Gender::Female],
        ['gender' => Gender::Male],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $doughnut = Doughnut::make(User::class)
        ->options(Gender::class)
        ->count('gender');

    assertEquals($doughnut->getLabels(), [
        Gender::Male->value,
        Gender::Female->value,
    ]);
});

it('shows limited options when using `options` method', function () {
    $sequence = new Sequence(
        // ['gender' => Gender::Male],
        // ['gender' => Gender::Male],
        ['gender' => Gender::Female],
        // ['gender' => Gender::Male],
        ['gender' => Gender::Female],
        // ['gender' => Gender::Male],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $doughnut = Doughnut::make(User::class)
        ->count('gender');

    assertEquals($doughnut->getLabels(), [
        Gender::Female->value,
    ]);
});

it('shows correct data for count method', function () {
    $sequence = new Sequence(
        ['gender' => Gender::Male],
        ['gender' => Gender::Male],
        ['gender' => Gender::Female],
        ['gender' => Gender::Male],
        ['gender' => Gender::Female],
        ['gender' => Gender::Male],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $doughnut = Doughnut::make(User::class)
        ->count('gender');

    assertEquals($doughnut->getData(), [4, 2]);
});

it('shows correct data for average method', function () {
    $sequence = new Sequence(
        ['age' => 20, 'gender' => Gender::Male],
        ['age' => 15, 'gender' => Gender::Male],
        ['age' => 60, 'gender' => Gender::Female],
        ['age' => 30, 'gender' => Gender::Male],
        ['age' => 45, 'gender' => Gender::Female],
        ['age' => 50, 'gender' => Gender::Male],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $doughnut = Doughnut::make(User::class)
        ->average('age', 'gender');

    assertEquals($doughnut->getData(), [28.75, 52.5]);
});

it('shows correct data for sum method', function () {
    $sequence = new Sequence(
        ['age' => 20, 'gender' => Gender::Male],
        ['age' => 15, 'gender' => Gender::Male],
        ['age' => 60, 'gender' => Gender::Female],
        ['age' => 30, 'gender' => Gender::Male],
        ['age' => 45, 'gender' => Gender::Female],
        ['age' => 50, 'gender' => Gender::Male],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $doughnut = Doughnut::make(User::class)
        ->sum('age', 'gender');

    assertEquals($doughnut->getData(), [115, 105]);
});

it('shows correct data for max method', function () {
    $sequence = new Sequence(
        ['age' => 20, 'gender' => Gender::Male],
        ['age' => 15, 'gender' => Gender::Male],
        ['age' => 60, 'gender' => Gender::Female],
        ['age' => 30, 'gender' => Gender::Male],
        ['age' => 45, 'gender' => Gender::Female],
        ['age' => 50, 'gender' => Gender::Male],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $doughnut = Doughnut::make(User::class)
        ->max('age', 'gender');

    assertEquals($doughnut->getData(), [50, 60]);
});

it('shows correct data for min method', function () {
    $sequence = new Sequence(
        ['age' => 20, 'gender' => Gender::Male],
        ['age' => 15, 'gender' => Gender::Male],
        ['age' => 60, 'gender' => Gender::Female],
        ['age' => 30, 'gender' => Gender::Male],
        ['age' => 45, 'gender' => Gender::Female],
        ['age' => 50, 'gender' => Gender::Male],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $doughnut = Doughnut::make(User::class)
        ->min('age', 'gender');

    assertEquals($doughnut->getData(), [15, 45]);
});

// Growth rate

it('shows correct growth rate for count method by Range::ALL', function () {
    $sequence = new Sequence(
        ['gender' => Gender::Female, 'created_at' => Date::now()->yesterday()],
        ['gender' => Gender::Female, 'created_at' => Date::now()->yesterday()],
        ['gender' => Gender::Male, 'created_at' => Date::now()->subDays(1)],
        ['gender' => Gender::Female, 'created_at' => Date::now()],
        ['gender' => Gender::Male, 'created_at' => Date::now()->addDays(1)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $doughnut = Doughnut::make(User::class)
        ->options(Gender::class)
        ->range(Range::ALL)
        ->withGrowthRate()
        ->growthRateType(GrowthRateType::Value)
        ->count('gender');

    assertEquals($doughnut->getGrowthRate(), [2, 3]);

    $doughnut = Doughnut::make(User::class)
        ->options(Gender::class)
        ->range(Range::ALL)
        ->withGrowthRate()
        ->growthRateType(GrowthRateType::Percentage)
        ->count('gender');

    assertEquals($doughnut->getGrowthRate(), [100, 100]);
});

it('shows correct growth rate for count method', function () {
    $sequence = new Sequence(
        ['gender' => Gender::Female, 'created_at' => Date::now()->yesterday()],
        ['gender' => Gender::Female, 'created_at' => Date::now()->yesterday()],
        ['gender' => Gender::Male, 'created_at' => Date::now()->subDays(1)],
        ['gender' => Gender::Female, 'created_at' => Date::now()],
        ['gender' => Gender::Male, 'created_at' => Date::now()->addDays(1)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $doughnut = Doughnut::make(User::class)
        ->options(Gender::class)
        ->range(Range::TODAY)
        ->withGrowthRate()
        ->growthRateType(GrowthRateType::Value)
        ->count('gender');

    assertEquals($doughnut->getGrowthRate(), [-1, -1]);

    $doughnut = Doughnut::make(User::class)
        ->options(Gender::class)
        ->range(Range::TODAY)
        ->withGrowthRate()
        ->growthRateType(GrowthRateType::Percentage)
        ->count('gender');

    assertEquals($doughnut->getGrowthRate(), [-100, -50]);
});

it('shows correct growth rate for average method', function () {
    $sequence = new Sequence(
        ['age' => 50, 'gender' => Gender::Male, 'created_at' => Date::now()->yesterday()],
        ['age' => 20, 'gender' => Gender::Male, 'created_at' => Date::now()->subDays(1)],
        ['age' => 45, 'gender' => Gender::Female, 'created_at' => Date::now()->yesterday()],
        ['age' => 15, 'gender' => Gender::Female, 'created_at' => Date::now()],
        ['age' => 60, 'gender' => Gender::Male, 'created_at' => Date::now()->addDays(1)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $doughnut = Doughnut::make(User::class)
        ->options(Gender::class)
        ->range(Range::TODAY)
        ->withGrowthRate()
        ->growthRateType(GrowthRateType::Value)
        ->average('age', 'gender');

    assertEquals($doughnut->getGrowthRate(), [-35, -30]);

    $doughnut = Doughnut::make(User::class)
        ->options(Gender::class)
        ->range(Range::TODAY)
        ->withGrowthRate()
        ->growthRateType(GrowthRateType::Percentage)
        ->average('age', 'gender');

    assertEquals($doughnut->getGrowthRate(), [-100, -66.67]);
});

it('shows correct growth rate for sum method', function () {
    $sequence = new Sequence(
        ['age' => 50, 'gender' => Gender::Male, 'created_at' => Date::now()->yesterday()],
        ['age' => 20, 'gender' => Gender::Male, 'created_at' => Date::now()->subDays(1)],
        ['age' => 45, 'gender' => Gender::Female, 'created_at' => Date::now()->yesterday()],
        ['age' => 15, 'gender' => Gender::Female, 'created_at' => Date::now()],
        ['age' => 60, 'gender' => Gender::Male, 'created_at' => Date::now()->addDays(1)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $doughnut = Doughnut::make(User::class)
        ->options(Gender::class)
        ->range(Range::TODAY)
        ->withGrowthRate()
        ->growthRateType(GrowthRateType::Value)
        ->sum('age', 'gender');

    assertEquals($doughnut->getGrowthRate(), [-70, -30]);

    $doughnut = Doughnut::make(User::class)
        ->options(Gender::class)
        ->range(Range::TODAY)
        ->withGrowthRate()
        ->growthRateType(GrowthRateType::Percentage)
        ->sum('age', 'gender');

    assertEquals($doughnut->getGrowthRate(), [-100, -66.67]);
});

it('shows correct growth rate for max method', function () {
    $sequence = new Sequence(
        ['age' => 50, 'gender' => Gender::Male, 'created_at' => Date::now()->yesterday()],
        ['age' => 20, 'gender' => Gender::Male, 'created_at' => Date::now()->subDays(1)],
        ['age' => 45, 'gender' => Gender::Female, 'created_at' => Date::now()->yesterday()],
        ['age' => 15, 'gender' => Gender::Female, 'created_at' => Date::now()],
        ['age' => 60, 'gender' => Gender::Male, 'created_at' => Date::now()->addDays(1)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $doughnut = Doughnut::make(User::class)
        ->options(Gender::class)
        ->range(Range::TODAY)
        ->withGrowthRate()
        ->growthRateType(GrowthRateType::Value)
        ->max('age', 'gender');

    assertEquals($doughnut->getGrowthRate(), [-50, -30]);

    $doughnut = Doughnut::make(User::class)
        ->options(Gender::class)
        ->range(Range::TODAY)
        ->withGrowthRate()
        ->growthRateType(GrowthRateType::Percentage)
        ->max('age', 'gender');

    assertEquals($doughnut->getGrowthRate(), [-100, -66.67]);
});

it('shows correct growth rate for min method', function () {
    $sequence = new Sequence(
        ['age' => 50, 'gender' => Gender::Male, 'created_at' => Date::now()->yesterday()],
        ['age' => 20, 'gender' => Gender::Male, 'created_at' => Date::now()->subDays(1)],
        ['age' => 45, 'gender' => Gender::Female, 'created_at' => Date::now()->yesterday()],
        ['age' => 15, 'gender' => Gender::Female, 'created_at' => Date::now()],
        ['age' => 60, 'gender' => Gender::Male, 'created_at' => Date::now()->addDays(1)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $doughnut = Doughnut::make(User::class)
        ->options(Gender::class)
        ->range(Range::TODAY)
        ->withGrowthRate()
        ->growthRateType(GrowthRateType::Value)
        ->min('age', 'gender');

    assertEquals($doughnut->getGrowthRate(), [-20, -30]);

    $doughnut = Doughnut::make(User::class)
        ->options(Gender::class)
        ->range(Range::TODAY)
        ->withGrowthRate()
        ->growthRateType(GrowthRateType::Percentage)
        ->min('age', 'gender');

    assertEquals($doughnut->getGrowthRate(), [-100, -66.67]);
});
