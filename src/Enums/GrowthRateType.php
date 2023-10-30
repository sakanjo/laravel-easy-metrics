<?php

namespace SaKanjo\EasyMetrics\Enums;

enum GrowthRateType: string
{
    case Percentage = 'percentage';
    case Value = 'value';

    public function getValue(float $previousValue, float $currentValue): float
    {
        $value = match ($this) {
            self::Percentage => $previousValue == 0 ?
                ($currentValue == 0 ? 0 : 100 * ($currentValue <=> 0)) :
                (($currentValue - $previousValue) / ($previousValue)) * 100,
            self::Value => $currentValue - $previousValue,
        };

        return round($value, 2);
    }
}
