<?php

use Illuminate\Database\Eloquent\Factories\Sequence;
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
