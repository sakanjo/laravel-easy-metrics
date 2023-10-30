<?php

use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\Date;
use SaKanjo\EasyMetrics\Enums\growthRateType;
use SaKanjo\EasyMetrics\Metrics\Trend;
use SaKanjo\EasyMetrics\Tests\Enums\Gender;
use SaKanjo\EasyMetrics\Tests\Models\User;
use SaKanjo\EasyMetrics\Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

uses(TestCase::class);

// countBy

it('shows correct data for countByMinutes method', function () {
    $sequence = new Sequence(
        ['gender' => Gender::Male, 'created_at' => Date::now()],
        ['gender' => Gender::Male, 'created_at' => Date::now()->subMinutes(1)],
        ['gender' => Gender::Female, 'created_at' => Date::now()->subMinutes(2)],
        ['gender' => Gender::Male, 'created_at' => Date::now()],
        ['gender' => Gender::Female, 'created_at' => Date::now()->subMinutes(6)],
        ['gender' => Gender::Male, 'created_at' => Date::now()->subMinutes(7)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->countByMinutes();

    assertEquals($trend->getData(), [
        0, 0, 1, 1, 0, 0, 0, 1, 1, 2,
    ]);
});

it('shows correct data for countByHours method', function () {
    $sequence = new Sequence(
        ['gender' => Gender::Male, 'created_at' => Date::now()],
        ['gender' => Gender::Male, 'created_at' => Date::now()->subHours(1)],
        ['gender' => Gender::Female, 'created_at' => Date::now()->subHours(2)],
        ['gender' => Gender::Male, 'created_at' => Date::now()],
        ['gender' => Gender::Female, 'created_at' => Date::now()->subHours(6)],
        ['gender' => Gender::Male, 'created_at' => Date::now()->subHours(7)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->countByHours();

    assertEquals($trend->getData(), [
        0, 0, 1, 1, 0, 0, 0, 1, 1, 2,
    ]);
});

it('shows correct data for countByDays method', function () {
    $sequence = new Sequence(
        ['gender' => Gender::Male, 'created_at' => Date::now()],
        ['gender' => Gender::Male, 'created_at' => Date::now()->subDays(1)],
        ['gender' => Gender::Female, 'created_at' => Date::now()->subDays(2)],
        ['gender' => Gender::Male, 'created_at' => Date::now()],
        ['gender' => Gender::Female, 'created_at' => Date::now()->subDays(6)],
        ['gender' => Gender::Male, 'created_at' => Date::now()->subDays(7)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->countByDays();

    assertEquals($trend->getData(), [
        0, 0, 1, 1, 0, 0, 0, 1, 1, 2,
    ]);
});

it('shows correct data for countByWeeks method', function () {
    $sequence = new Sequence(
        ['gender' => Gender::Male, 'created_at' => Date::now()],
        ['gender' => Gender::Male, 'created_at' => Date::now()->subWeeks(1)],
        ['gender' => Gender::Female, 'created_at' => Date::now()->subWeeks(2)],
        ['gender' => Gender::Male, 'created_at' => Date::now()],
        ['gender' => Gender::Female, 'created_at' => Date::now()->subWeeks(6)],
        ['gender' => Gender::Male, 'created_at' => Date::now()->subWeeks(7)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $trend = Trend::make(User::class)
        ->countByWeeks();

    assertEquals($trend->getData(), [
        0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, 1, 1, 2,
    ]);
});

it('shows correct data for countByMonths method', function () {
    $sequence = new Sequence(
        ['gender' => Gender::Male, 'created_at' => Date::now()],
        ['gender' => Gender::Male, 'created_at' => Date::now()->subMonths(1)],
        ['gender' => Gender::Female, 'created_at' => Date::now()->subMonths(2)],
        ['gender' => Gender::Male, 'created_at' => Date::now()],
        ['gender' => Gender::Female, 'created_at' => Date::now()->subMonths(6)],
        ['gender' => Gender::Male, 'created_at' => Date::now()->subMonths(7)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->countByMonths();

    assertEquals($trend->getData(), [
        0, 0, 1, 1, 0, 0, 0, 1, 1, 2,
    ]);
});

it('shows correct data for countByYears method', function () {
    $sequence = new Sequence(
        ['gender' => Gender::Male, 'created_at' => Date::now()],
        ['gender' => Gender::Male, 'created_at' => Date::now()->subYears(1)],
        ['gender' => Gender::Female, 'created_at' => Date::now()->subYears(2)],
        ['gender' => Gender::Male, 'created_at' => Date::now()],
        ['gender' => Gender::Female, 'created_at' => Date::now()->subYears(6)],
        ['gender' => Gender::Male, 'created_at' => Date::now()->subYears(7)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->countByYears();

    assertEquals($trend->getData(), [
        0, 0, 1, 1, 0, 0, 0, 1, 1, 2,
    ]);
});

// averageBy

it('shows correct data for averageByMinutes method', function () {
    $sequence = new Sequence(
        ['age' => 20, 'created_at' => Date::now()],
        ['age' => 30, 'created_at' => Date::now()->subMinutes(1)],
        ['age' => 10, 'created_at' => Date::now()->subMinutes(2)],
        ['age' => 50, 'created_at' => Date::now()],
        ['age' => 25, 'created_at' => Date::now()->subMinutes(6)],
        ['age' => 40, 'created_at' => Date::now()->subMinutes(7)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->averageByMinutes('age');

    assertEquals($trend->getData(), [
        0, 0, 40, 25, 0, 0, 0, 10, 30, 35,
    ]);
});

it('shows correct data for averageByHours method', function () {
    $sequence = new Sequence(
        ['age' => 20, 'created_at' => Date::now()],
        ['age' => 30, 'created_at' => Date::now()->subHours(1)],
        ['age' => 10, 'created_at' => Date::now()->subHours(2)],
        ['age' => 50, 'created_at' => Date::now()],
        ['age' => 25, 'created_at' => Date::now()->subHours(6)],
        ['age' => 40, 'created_at' => Date::now()->subHours(7)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->averageByHours('age');

    assertEquals($trend->getData(), [
        0, 0, 40, 25, 0, 0, 0, 10, 30, 35,
    ]);
});

it('shows correct data for averageByDays method', function () {
    $sequence = new Sequence(
        ['age' => 20, 'created_at' => Date::now()],
        ['age' => 30, 'created_at' => Date::now()->subDays(1)],
        ['age' => 10, 'created_at' => Date::now()->subDays(2)],
        ['age' => 50, 'created_at' => Date::now()],
        ['age' => 25, 'created_at' => Date::now()->subDays(6)],
        ['age' => 40, 'created_at' => Date::now()->subDays(7)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->averageByDays('age');

    assertEquals($trend->getData(), [
        0, 0, 40, 25, 0, 0, 0, 10, 30, 35,
    ]);
});

it('shows correct data for averageByWeeks method', function () {
    $sequence = new Sequence(
        ['age' => 20, 'created_at' => Date::now()],
        ['age' => 30, 'created_at' => Date::now()->subWeeks(1)],
        ['age' => 10, 'created_at' => Date::now()->subWeeks(2)],
        ['age' => 50, 'created_at' => Date::now()],
        ['age' => 25, 'created_at' => Date::now()->subWeeks(6)],
        ['age' => 40, 'created_at' => Date::now()->subWeeks(7)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->averageByWeeks('age');

    assertEquals($trend->getData(), [
        0, 0, 40, 25, 0, 0, 0, 10, 30, 35,
    ]);
});

it('shows correct data for averageByMonths method', function () {
    $sequence = new Sequence(
        ['age' => 20, 'created_at' => Date::now()],
        ['age' => 30, 'created_at' => Date::now()->subMonths(1)],
        ['age' => 10, 'created_at' => Date::now()->subMonths(2)],
        ['age' => 50, 'created_at' => Date::now()],
        ['age' => 25, 'created_at' => Date::now()->subMonths(6)],
        ['age' => 40, 'created_at' => Date::now()->subMonths(7)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->averageByMonths('age');

    assertEquals($trend->getData(), [
        0, 0, 40, 25, 0, 0, 0, 10, 30, 35,
    ]);
});

it('shows correct data for averageByYears method', function () {
    $sequence = new Sequence(
        ['age' => 20, 'created_at' => Date::now()],
        ['age' => 30, 'created_at' => Date::now()->subYears(1)],
        ['age' => 10, 'created_at' => Date::now()->subYears(2)],
        ['age' => 50, 'created_at' => Date::now()],
        ['age' => 25, 'created_at' => Date::now()->subYears(6)],
        ['age' => 40, 'created_at' => Date::now()->subYears(7)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->averageByYears('age');

    assertEquals($trend->getData(), [
        0, 0, 40, 25, 0, 0, 0, 10, 30, 35,
    ]);
});

// sumBy

it('shows correct data for sumByMinutes method', function () {
    $sequence = new Sequence(
        ['age' => 20, 'created_at' => Date::now()],
        ['age' => 30, 'created_at' => Date::now()->subMinutes(1)],
        ['age' => 10, 'created_at' => Date::now()->subMinutes(2)],
        ['age' => 50, 'created_at' => Date::now()],
        ['age' => 25, 'created_at' => Date::now()->subMinutes(6)],
        ['age' => 40, 'created_at' => Date::now()->subMinutes(7)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->sumByMinutes('age');

    assertEquals($trend->getData(), [
        0, 0, 40, 25, 0, 0, 0, 10, 30, 70,
    ]);
});

it('shows correct data for sumByHours method', function () {
    $sequence = new Sequence(
        ['age' => 20, 'created_at' => Date::now()],
        ['age' => 30, 'created_at' => Date::now()->subHours(1)],
        ['age' => 10, 'created_at' => Date::now()->subHours(2)],
        ['age' => 50, 'created_at' => Date::now()],
        ['age' => 25, 'created_at' => Date::now()->subHours(6)],
        ['age' => 40, 'created_at' => Date::now()->subHours(7)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->sumByHours('age');

    assertEquals($trend->getData(), [
        0, 0, 40, 25, 0, 0, 0, 10, 30, 70,
    ]);
});

it('shows correct data for sumByDays method', function () {
    $sequence = new Sequence(
        ['age' => 20, 'created_at' => Date::now()],
        ['age' => 30, 'created_at' => Date::now()->subDays(1)],
        ['age' => 10, 'created_at' => Date::now()->subDays(2)],
        ['age' => 50, 'created_at' => Date::now()],
        ['age' => 25, 'created_at' => Date::now()->subDays(6)],
        ['age' => 40, 'created_at' => Date::now()->subDays(7)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->sumByDays('age');

    assertEquals($trend->getData(), [
        0, 0, 40, 25, 0, 0, 0, 10, 30, 70,
    ]);
});

it('shows correct data for sumByWeeks method', function () {
    $sequence = new Sequence(
        ['age' => 20, 'created_at' => Date::now()],
        ['age' => 30, 'created_at' => Date::now()->subWeeks(1)],
        ['age' => 10, 'created_at' => Date::now()->subWeeks(2)],
        ['age' => 50, 'created_at' => Date::now()],
        ['age' => 25, 'created_at' => Date::now()->subWeeks(6)],
        ['age' => 40, 'created_at' => Date::now()->subWeeks(7)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->sumByWeeks('age');

    assertEquals($trend->getData(), [
        0, 0, 40, 25, 0, 0, 0, 10, 30, 70,
    ]);
});

it('shows correct data for sumByMonths method', function () {
    $sequence = new Sequence(
        ['age' => 20, 'created_at' => Date::now()],
        ['age' => 30, 'created_at' => Date::now()->subMonths(1)],
        ['age' => 10, 'created_at' => Date::now()->subMonths(2)],
        ['age' => 50, 'created_at' => Date::now()],
        ['age' => 25, 'created_at' => Date::now()->subMonths(6)],
        ['age' => 40, 'created_at' => Date::now()->subMonths(7)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->sumByMonths('age');

    assertEquals($trend->getData(), [
        0, 0, 40, 25, 0, 0, 0, 10, 30, 70,
    ]);
});

it('shows correct data for sumByYears method', function () {
    $sequence = new Sequence(
        ['age' => 20, 'created_at' => Date::now()],
        ['age' => 30, 'created_at' => Date::now()->subYears(1)],
        ['age' => 10, 'created_at' => Date::now()->subYears(2)],
        ['age' => 50, 'created_at' => Date::now()],
        ['age' => 25, 'created_at' => Date::now()->subYears(6)],
        ['age' => 40, 'created_at' => Date::now()->subYears(7)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->sumByYears('age');

    assertEquals($trend->getData(), [
        0, 0, 40, 25, 0, 0, 0, 10, 30, 70,
    ]);
});

// maxBy

it('shows correct data for maxByMinutes method', function () {
    $sequence = new Sequence(
        ['age' => 20, 'created_at' => Date::now()],
        ['age' => 30, 'created_at' => Date::now()->subMinutes(1)],
        ['age' => 10, 'created_at' => Date::now()->subMinutes(2)],
        ['age' => 50, 'created_at' => Date::now()],
        ['age' => 25, 'created_at' => Date::now()->subMinutes(6)],
        ['age' => 40, 'created_at' => Date::now()->subMinutes(7)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->maxByMinutes('age');

    assertEquals($trend->getData(), [
        0, 0, 40, 25, 0, 0, 0, 10, 30, 50,
    ]);
});

it('shows correct data for maxByHours method', function () {
    $sequence = new Sequence(
        ['age' => 20, 'created_at' => Date::now()],
        ['age' => 30, 'created_at' => Date::now()->subHours(1)],
        ['age' => 10, 'created_at' => Date::now()->subHours(2)],
        ['age' => 50, 'created_at' => Date::now()],
        ['age' => 25, 'created_at' => Date::now()->subHours(6)],
        ['age' => 40, 'created_at' => Date::now()->subHours(7)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->maxByHours('age');

    assertEquals($trend->getData(), [
        0, 0, 40, 25, 0, 0, 0, 10, 30, 50,
    ]);
});

it('shows correct data for maxByDays method', function () {
    $sequence = new Sequence(
        ['age' => 20, 'created_at' => Date::now()],
        ['age' => 30, 'created_at' => Date::now()->subDays(1)],
        ['age' => 10, 'created_at' => Date::now()->subDays(2)],
        ['age' => 50, 'created_at' => Date::now()],
        ['age' => 25, 'created_at' => Date::now()->subDays(6)],
        ['age' => 40, 'created_at' => Date::now()->subDays(7)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->maxByDays('age');

    assertEquals($trend->getData(), [
        0, 0, 40, 25, 0, 0, 0, 10, 30, 50,
    ]);
});

it('shows correct data for maxByWeeks method', function () {
    $sequence = new Sequence(
        ['age' => 20, 'created_at' => Date::now()],
        ['age' => 30, 'created_at' => Date::now()->subWeeks(1)],
        ['age' => 10, 'created_at' => Date::now()->subWeeks(2)],
        ['age' => 50, 'created_at' => Date::now()],
        ['age' => 25, 'created_at' => Date::now()->subWeeks(6)],
        ['age' => 40, 'created_at' => Date::now()->subWeeks(7)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->maxByWeeks('age');

    assertEquals($trend->getData(), [
        0, 0, 40, 25, 0, 0, 0, 10, 30, 50,
    ]);
});

it('shows correct data for maxByMonths method', function () {
    $sequence = new Sequence(
        ['age' => 20, 'created_at' => Date::now()],
        ['age' => 30, 'created_at' => Date::now()->subMonths(1)],
        ['age' => 10, 'created_at' => Date::now()->subMonths(2)],
        ['age' => 50, 'created_at' => Date::now()],
        ['age' => 25, 'created_at' => Date::now()->subMonths(6)],
        ['age' => 40, 'created_at' => Date::now()->subMonths(7)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->maxByMonths('age');

    assertEquals($trend->getData(), [
        0, 0, 40, 25, 0, 0, 0, 10, 30, 50,
    ]);
});

it('shows correct data for maxByYears method', function () {
    $sequence = new Sequence(
        ['age' => 20, 'created_at' => Date::now()],
        ['age' => 30, 'created_at' => Date::now()->subYears(1)],
        ['age' => 10, 'created_at' => Date::now()->subYears(2)],
        ['age' => 50, 'created_at' => Date::now()],
        ['age' => 25, 'created_at' => Date::now()->subYears(6)],
        ['age' => 40, 'created_at' => Date::now()->subYears(7)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->maxByYears('age');

    assertEquals($trend->getData(), [
        0, 0, 40, 25, 0, 0, 0, 10, 30, 50,
    ]);
});

// minBy

it('shows correct data for minByMinutes method', function () {
    $sequence = new Sequence(
        ['age' => 20, 'created_at' => Date::now()],
        ['age' => 30, 'created_at' => Date::now()->subMinutes(1)],
        ['age' => 10, 'created_at' => Date::now()->subMinutes(2)],
        ['age' => 50, 'created_at' => Date::now()],
        ['age' => 25, 'created_at' => Date::now()->subMinutes(6)],
        ['age' => 40, 'created_at' => Date::now()->subMinutes(7)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->minByMinutes('age');

    assertEquals($trend->getData(), [
        0, 0, 40, 25, 0, 0, 0, 10, 30, 20,
    ]);
});

it('shows correct data for minByHours method', function () {
    $sequence = new Sequence(
        ['age' => 20, 'created_at' => Date::now()],
        ['age' => 30, 'created_at' => Date::now()->subHours(1)],
        ['age' => 10, 'created_at' => Date::now()->subHours(2)],
        ['age' => 50, 'created_at' => Date::now()],
        ['age' => 25, 'created_at' => Date::now()->subHours(6)],
        ['age' => 40, 'created_at' => Date::now()->subHours(7)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->minByHours('age');

    assertEquals($trend->getData(), [
        0, 0, 40, 25, 0, 0, 0, 10, 30, 20,
    ]);
});

it('shows correct data for minByDays method', function () {
    $sequence = new Sequence(
        ['age' => 20, 'created_at' => Date::now()],
        ['age' => 30, 'created_at' => Date::now()->subDays(1)],
        ['age' => 10, 'created_at' => Date::now()->subDays(2)],
        ['age' => 50, 'created_at' => Date::now()],
        ['age' => 25, 'created_at' => Date::now()->subDays(6)],
        ['age' => 40, 'created_at' => Date::now()->subDays(7)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->minByDays('age');

    assertEquals($trend->getData(), [
        0, 0, 40, 25, 0, 0, 0, 10, 30, 20,
    ]);
});

it('shows correct data for minByWeeks method', function () {
    $sequence = new Sequence(
        ['age' => 20, 'created_at' => Date::now()],
        ['age' => 30, 'created_at' => Date::now()->subWeeks(1)],
        ['age' => 10, 'created_at' => Date::now()->subWeeks(2)],
        ['age' => 50, 'created_at' => Date::now()],
        ['age' => 25, 'created_at' => Date::now()->subWeeks(6)],
        ['age' => 40, 'created_at' => Date::now()->subWeeks(7)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->minByWeeks('age');

    assertEquals($trend->getData(), [
        0, 0, 40, 25, 0, 0, 0, 10, 30, 20,
    ]);
});

it('shows correct data for minByMonths method', function () {
    $sequence = new Sequence(
        ['age' => 20, 'created_at' => Date::now()],
        ['age' => 30, 'created_at' => Date::now()->subMonths(1)],
        ['age' => 10, 'created_at' => Date::now()->subMonths(2)],
        ['age' => 50, 'created_at' => Date::now()],
        ['age' => 25, 'created_at' => Date::now()->subMonths(6)],
        ['age' => 40, 'created_at' => Date::now()->subMonths(7)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->minByMonths('age');

    assertEquals($trend->getData(), [
        0, 0, 40, 25, 0, 0, 0, 10, 30, 20,
    ]);
});

it('shows correct data for minByYears method', function () {
    $sequence = new Sequence(
        ['age' => 20, 'created_at' => Date::now()],
        ['age' => 30, 'created_at' => Date::now()->subYears(1)],
        ['age' => 10, 'created_at' => Date::now()->subYears(2)],
        ['age' => 50, 'created_at' => Date::now()],
        ['age' => 25, 'created_at' => Date::now()->subYears(6)],
        ['age' => 40, 'created_at' => Date::now()->subYears(7)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->minByYears('age');

    assertEquals($trend->getData(), [
        0, 0, 40, 25, 0, 0, 0, 10, 30, 20,
    ]);
});

// Growth rate

it('shows correct growth rate for countByYears method', function () {
    $sequence = new Sequence(
        ['gender' => Gender::Male, 'created_at' => Date::now()],
        ['gender' => Gender::Male, 'created_at' => Date::now()->subYears(1)],
        ['gender' => Gender::Female, 'created_at' => Date::now()->subYears(2)],
        ['gender' => Gender::Male, 'created_at' => Date::now()],
        ['gender' => Gender::Female, 'created_at' => Date::now()->subYears(6)],
        ['gender' => Gender::Male, 'created_at' => Date::now()->subYears(7)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->withGrowthRate()
        ->growthRateType(growthRateType::Value)
        ->countByYears();

    assertEquals($trend->getGrowthRate(), -1);

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->withGrowthRate()
        ->growthRateType(growthRateType::Percentage)
        ->countByYears();

    assertEquals($trend->getGrowthRate(), -50);
});

it('shows correct growth rate for averageByYears method', function () {
    $sequence = new Sequence(
        ['age' => 20, 'created_at' => Date::now()],
        ['age' => 30, 'created_at' => Date::now()->subYears(1)],
        ['age' => 10, 'created_at' => Date::now()->subYears(2)],
        ['age' => 50, 'created_at' => Date::now()],
        ['age' => 25, 'created_at' => Date::now()->subYears(6)],
        ['age' => 40, 'created_at' => Date::now()->subYears(7)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->withGrowthRate()
        ->growthRateType(growthRateType::Value)
        ->averageByYears('age');

    assertEquals($trend->getGrowthRate(), -5);

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->withGrowthRate()
        ->growthRateType(growthRateType::Percentage)
        ->averageByYears('age');

    assertEquals($trend->getGrowthRate(), -14.29);
});

it('shows correct growth rate for sumByYears method', function () {
    $sequence = new Sequence(
        ['age' => 20, 'created_at' => Date::now()],
        ['age' => 30, 'created_at' => Date::now()->subYears(1)],
        ['age' => 10, 'created_at' => Date::now()->subYears(2)],
        ['age' => 50, 'created_at' => Date::now()],
        ['age' => 25, 'created_at' => Date::now()->subYears(6)],
        ['age' => 40, 'created_at' => Date::now()->subYears(7)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->withGrowthRate()
        ->growthRateType(growthRateType::Value)
        ->sumByYears('age');

    assertEquals($trend->getGrowthRate(), -40);

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->withGrowthRate()
        ->growthRateType(growthRateType::Percentage)
        ->sumByYears('age');

    assertEquals($trend->getGrowthRate(), -57.14);
});

it('shows correct growth rate for maxByYears method', function () {
    $sequence = new Sequence(
        ['age' => 20, 'created_at' => Date::now()],
        ['age' => 30, 'created_at' => Date::now()->subYears(1)],
        ['age' => 10, 'created_at' => Date::now()->subYears(2)],
        ['age' => 50, 'created_at' => Date::now()],
        ['age' => 25, 'created_at' => Date::now()->subYears(6)],
        ['age' => 40, 'created_at' => Date::now()->subYears(7)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->withGrowthRate()
        ->growthRateType(growthRateType::Value)
        ->maxByYears('age');

    assertEquals($trend->getGrowthRate(), -20);

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->withGrowthRate()
        ->growthRateType(growthRateType::Percentage)
        ->maxByYears('age');

    assertEquals($trend->getGrowthRate(), -40);
});

it('shows correct growth rate for minByYears method', function () {
    $sequence = new Sequence(
        ['age' => 20, 'created_at' => Date::now()],
        ['age' => 30, 'created_at' => Date::now()->subYears(1)],
        ['age' => 10, 'created_at' => Date::now()->subYears(2)],
        ['age' => 50, 'created_at' => Date::now()],
        ['age' => 25, 'created_at' => Date::now()->subYears(6)],
        ['age' => 40, 'created_at' => Date::now()->subYears(7)],
    );

    User::factory()
        ->count(count($sequence))
        ->state($sequence)
        ->create();

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->withGrowthRate()
        ->growthRateType(growthRateType::Value)
        ->minByYears('age');

    assertEquals($trend->getGrowthRate(), 10);

    $trend = Trend::make(User::class)
        ->ranges([10])
        ->withGrowthRate()
        ->growthRateType(growthRateType::Percentage)
        ->minByYears('age');

    assertEquals($trend->getGrowthRate(), 50);
});
