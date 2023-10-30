<?php

namespace SaKanjo\EasyMetrics\Enums;

use Carbon\CarbonImmutable;

enum Range: string
{
    case TODAY = 'TODAY';
    case YESTERDAY = 'YESTERDAY';
    case MTD = 'MTD';
    case QTD = 'QTD';
    case YTD = 'YTD';
    case ALL = 'ALL';

    public function getPreviousRange(): ?array
    {
        $now = CarbonImmutable::now();

        return match ($this) {
            Range::TODAY => [
                $now->subDay()->startOfDay(),
                $now->subDay(),
            ],
            Range::YESTERDAY => [
                $now->subDays(2)->startOfDay(),
                $now->subDays(2),
            ],
            Range::MTD => [
                $now->subMonthWithoutOverflow()->startOfMonth(),
                $now->subMonthWithoutOverflow(),
            ],
            Range::QTD => [
                $now->subQuarter()->startOfQuarter(),
                $now->subQuarter(),
            ],
            Range::YTD => [
                $now->subYear()->startOfYear(),
                $now->subYear(),
            ],
            Range::ALL => null,
        };
    }

    public function getRange(): ?array
    {
        $now = CarbonImmutable::now();

        return match ($this) {
            Range::TODAY => [
                $now->startOfDay(),
                $now,
            ],
            Range::YESTERDAY => [
                $now->subDay()->startOfDay(),
                $now->subDay(),
            ],
            Range::MTD => [
                $now->startOfMonth(),
                $now,
            ],
            Range::QTD => [
                $now->startOfQuarter(),
                $now,
            ],
            Range::YTD => [
                $now->startOfYear(),
                $now,
            ],
            Range::ALL => null,
        };
    }
}
