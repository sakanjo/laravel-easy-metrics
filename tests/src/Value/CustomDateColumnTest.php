<?php

use Illuminate\Support\Facades\Date;
use SaKanjo\EasyMetrics\Metrics\Value;
use SaKanjo\EasyMetrics\Tests\Models\User;
use SaKanjo\EasyMetrics\Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

uses(TestCase::class);

it('shows correct data for count method with custom date column', function () {
    User::factory()->create([
        'email_verified_at' => Date::now()->subDays(2),
    ]);

    User::factory()->create([
        'email_verified_at' => Date::now()->subDays(10),
    ]);

    User::factory()->create([
        'email_verified_at' => Date::now()->subDays(20),
    ]);

    User::factory()->create([
        'email_verified_at' => null,
    ]);

    $data = Value::make(User::class)
        ->dateColumn('email_verified_at')
        ->range(15)
        ->count();

    assertEquals(2, $data);
});
