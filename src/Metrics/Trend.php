<?php

namespace SaKanjo\EasyMetrics\Metrics;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Date;
use SaKanjo\EasyMetrics\Concerns\OnlyIntegers;
use SaKanjo\EasyMetrics\Result;

class Trend extends Metric
{
    use OnlyIntegers;

    /**
     * @var int[]
     */
    protected array $ranges = [
        15, 30, 60, 365,
    ];

    protected string $unit;

    public function minByYears(string $column)
    {
        return $this->setType('min', 'year', $column);
    }

    public function minByMonths(string $column)
    {
        return $this->setType('min', 'month', $column);
    }

    public function minByWeeks(string $column)
    {
        return $this->setType('min', 'week', $column);
    }

    public function minByDays(string $column)
    {
        return $this->setType('min', 'day', $column);
    }

    public function minByHours(string $column)
    {
        return $this->setType('min', 'hour', $column);
    }

    public function minByMinutes(string $column)
    {
        return $this->setType('min', 'minute', $column);
    }

    public function maxByYears(string $column)
    {
        return $this->setType('max', 'year', $column);
    }

    public function maxByMonths(string $column)
    {
        return $this->setType('max', 'month', $column);
    }

    public function maxByWeeks(string $column)
    {
        return $this->setType('max', 'week', $column);
    }

    public function maxByDays(string $column)
    {
        return $this->setType('max', 'day', $column);
    }

    public function maxByHours(string $column)
    {
        return $this->setType('max', 'hour', $column);
    }

    public function maxByMinutes(string $column)
    {
        return $this->setType('max', 'minute', $column);
    }

    public function sumByYears(string $column)
    {
        return $this->setType('sum', 'year', $column);
    }

    public function sumByMonths(string $column)
    {
        return $this->setType('sum', 'month', $column);
    }

    public function sumByWeeks(string $column)
    {
        return $this->setType('sum', 'week', $column);
    }

    public function sumByDays(string $column)
    {
        return $this->setType('sum', 'day', $column);
    }

    public function sumByHours(string $column)
    {
        return $this->setType('sum', 'hour', $column);
    }

    public function sumByMinutes(string $column)
    {
        return $this->setType('sum', 'minute', $column);
    }

    public function averageByYears(string $column)
    {
        return $this->setType('avg', 'year', $column);
    }

    public function averageByMonths(string $column)
    {
        return $this->setType('avg', 'month', $column);
    }

    public function averageByWeeks(string $column)
    {
        return $this->setType('avg', 'week', $column);
    }

    public function averageByDays(string $column)
    {
        return $this->setType('avg', 'day', $column);
    }

    public function averageByHours(string $column)
    {
        return $this->setType('avg', 'hour', $column);
    }

    public function averageByMinutes(string $column)
    {
        return $this->setType('avg', 'minute', $column);
    }

    public function countByYears(string $column = '*')
    {
        return $this->setType('count', 'year', $column);
    }

    public function countByMonths(string $column = '*')
    {
        return $this->setType('count', 'month', $column);
    }

    public function countByWeeks(string $column = '*')
    {
        return $this->setType('count', 'week', $column);
    }

    public function countByDays(string $column = '*')
    {
        return $this->setType('count', 'day', $column);
    }

    public function countByHours(string $column = '*')
    {
        return $this->setType('count', 'hour', $column);
    }

    public function countByMinutes(string $column = '*')
    {
        return $this->setType('count', 'minute', $column);
    }

    protected function setType(string $type, string $unit, string $column)
    {
        $this->type = $type;
        $this->unit = $unit;
        $this->column = $column;

        return $this->resolve();
    }

    protected function getExpression(): string
    {
        $grammar = $this->query->getQuery()->getGrammar();
        $driver = $this->query->getConnection()->getDriverName(); // @phpstan-ignore-line
        $dateColumn = $grammar->wrap($this->getDateColumn());

        if (! in_array($this->unit, ['year', 'month', 'week', 'day', 'hour', 'minute'], true)) {
            throw new \InvalidArgumentException("Invalid unit: {$this->unit}");
        }

        if (static::hasMacro($driver)) {
            return static::$driver($dateColumn, $this->unit);
        }

        return match ($driver) {
            'sqlite' => match ($this->unit) {
                'year' => "strftime('%Y', $dateColumn)",
                'month' => "strftime('%Y-%m', $dateColumn)",
                'week' => "strftime('%Y-', $dateColumn) ||
                        printf('%02d', strftime('%W', $dateColumn) + (1 - strftime('%W', strftime('%Y', $dateColumn) || '-01-04')) )",
                'day' => "strftime('%Y-%m-%d', $dateColumn)",
                'hour' => "strftime('%Y-%m-%d %H:00', $dateColumn)",
                'minute' => "strftime('%Y-%m-%d %H:%M:00', $dateColumn)",
            },
            'mysql' => match ($this->unit) {
                'year' => "date_format($dateColumn, '%Y')",
                'month' => "date_format($dateColumn, '%Y-%m')",
                'week' => "date_format($dateColumn, '%x-%v')",
                'day' => "date_format($dateColumn, '%Y-%m-%d')",
                'hour' => "date_format($dateColumn, '%Y-%m-%d %H:00')",
                'minute' => "date_format($dateColumn, '%Y-%m-%d %H:%i:00')",
            },
            'pgsql' => match ($this->unit) {
                'year' => "to_char($dateColumn, 'YYYY')",
                'month' => "to_char($dateColumn, 'YYYY-MM')",
                'week' => "to_char($dateColumn, 'IYYY-IW')",
                'day' => "to_char($dateColumn, 'YYYY-MM-DD')",
                'hour' => "to_char($dateColumn, 'YYYY-MM-DD HH24:00')",
                'minute' => "to_char($dateColumn, 'YYYY-MM-DD HH24:mi:00')",
            },
            'sqlsrv' => match ($this->unit) {
                'year' => "FORMAT($dateColumn, 'yyyy')",
                'month' => "FORMAT($dateColumn, 'yyyy-MM')",
                'week' => "concat(YEAR($dateColumn), '-', datepart(ISO_WEEK, $dateColumn))",
                'day' => "FORMAT($dateColumn, 'yyyy-MM-dd')",
                'hour' => "FORMAT($dateColumn, 'yyyy-MM-dd HH:00')",
                'minute' => "FORMAT($dateColumn, 'yyyy-MM-dd HH:mm:00')",
            },
            default => throw new \InvalidArgumentException('Laravel Easy Metrics is not supported for this database.')
        };
    }

    protected function getFormat(): string
    {
        return match ($this->unit) {
            'year' => 'Y',
            'month' => 'Y-m',
            'week' => 'Y-W',
            'day' => 'Y-m-d',
            'hour' => 'Y-m-d H:00',
            'minute' => 'Y-m-d H:i:00',
            default => throw new \InvalidArgumentException("Invalid unit: {$this->unit}"),
        };
    }

    protected function getStartingDate(): CarbonImmutable
    {
        $now = CarbonImmutable::now();
        $range = $this->getRange() - 1;

        return match ($this->unit) {
            'year' => $now
                ->subYearsWithoutOverflow($range)
                ->firstOfYear()
                ->setTime(0, 0),
            'month' => $now
                ->subMonthsWithoutOverflow($range)
                ->firstOfMonth()
                ->setTime(0, 0),
            'week' => $now
                ->subWeeks($range)
                ->startOfWeek()
                ->setTime(0, 0),
            'day' => $now
                ->subDays($range)
                ->setTime(0, 0),
            'hour' => $now
                ->subHours($range),
            'minute' => $now
                ->subMinutes($range),
            default => throw new \InvalidArgumentException("Invalid unit: {$this->unit}"),
        };
    }

    protected function resolve(): Result
    {
        $dateColumn = $this->getDateColumn();
        $startingDate = $this->getStartingDate();
        $endingDate = Date::now();

        $expression = $this->getExpression();
        $column = $this->query->getQuery()->getGrammar()->wrap($this->column);

        $results = $this->query
            ->withoutEagerLoads()
            ->selectRaw("{$expression} as date_result, {$this->type}($column) as result")
            ->whereBetween($dateColumn, [$startingDate, $endingDate])
            ->groupBy('date_result')
            ->get()
            ->mapWithKeys(fn (mixed $result) => [
                $result['date_result'] => $this->transformResult($result['result']),
            ])
            ->toArray();

        $periods = collect(CarbonPeriod::create($startingDate, "1 {$this->unit}", $endingDate))
            ->mapWithKeys(fn (CarbonInterface $date) => [
                $date->format($this->getFormat()) => 0,
            ])
            ->toArray();

        $data = collect(array_replace($periods, $results))
            ->take(-count($periods))
            ->toArray();

        $growth = count($data) < 2
            ? null
            : $this->growthRateType->getValue(end($data), prev($data));

        return Result::make(
            array_values($data),
            array_keys($data),
            $growth
        );
    }
}
