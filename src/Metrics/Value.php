<?php

namespace SaKanjo\EasyMetrics\Metrics;

use Illuminate\Database\Eloquent\Builder;
use SaKanjo\EasyMetrics\ValueResult;

class Value extends Metric
{
    public function min(string $column)
    {
        return $this->setType('min', $column);
    }

    public function max(string $column)
    {
        return $this->setType('max', $column);
    }

    public function sum(string $column)
    {
        return $this->setType('sum', $column);
    }

    public function average(string $column)
    {
        return $this->setType('avg', $column);
    }

    public function count(string $column = '*')
    {
        return $this->setType('count', $column);
    }

    protected function setType(string $type, string $column): float|ValueResult
    {
        $this->type = $type;
        $this->column = $column;

        return $this->resolve();
    }

    protected function resolveValue(?array $range): float
    {
        $value = $this->query
            ->clone()
            ->withoutEagerLoads()
            ->when($range, fn (Builder $query) => $query
                ->whereBetween(...$this->resolveBetween($range))
            )
            ->{$this->type}($this->column);

        return $this->transformResult($value);
    }

    public function resolvePreviousValue(): float
    {
        $range = $this->previousRange();

        if (! $range) {
            return 0;
        }

        return $this->resolveValue($range);
    }

    public function resolveCurrentValue(): float
    {
        return $this->resolveValue(
            $this->currentRange()
        );
    }

    protected function resolve(): float|ValueResult
    {
        if (! $this->withGrowthRate) {
            return $this->resolveCurrentValue();
        }

        $currentValue = $this->resolveCurrentValue();
        $previousValue = $this->resolvePreviousValue();

        return ValueResult::make(
            $currentValue,
            $this->growthRateType->getValue($previousValue, $currentValue)
        );
    }
}
