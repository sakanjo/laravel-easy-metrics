<?php

use SaKanjo\EasyMetrics\Enums\GrowthRateType;

use function PHPUnit\Framework\assertEquals;

it('shows correct value for `value` type', function () {
    $growth = GrowthRateType::Value->getValue(0, 20);
    assertEquals($growth, 20);

    $growth = GrowthRateType::Value->getValue(0, 0);
    assertEquals($growth, 0);

    $growth = GrowthRateType::Value->getValue(20, 0);
    assertEquals($growth, -20);
});

it('shows correct value for `percentage` type', function () {
    $growth = GrowthRateType::Percentage->getValue(0, 20);
    assertEquals($growth, 100);

    $growth = GrowthRateType::Percentage->getValue(0, 0);
    assertEquals($growth, 0);

    $growth = GrowthRateType::Percentage->getValue(20, 0);
    assertEquals($growth, -100);

    $growth = GrowthRateType::Percentage->getValue(0, -20);
    assertEquals($growth, -100);

    $growth = GrowthRateType::Percentage->getValue(20, 40);
    assertEquals($growth, 100);

    $growth = GrowthRateType::Percentage->getValue(20, 10);
    assertEquals($growth, -50);
});
