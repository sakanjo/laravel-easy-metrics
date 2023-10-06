<?php

namespace SaKanjo\EasyMetrics\Metrics;

use Illuminate\Database\Eloquent\Builder;

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

    protected function setType(string $type, string $column): float
    {
        $this->type = $type;
        $this->column = $column;

        return $this->resolve();
    }

    protected function resolve(): float
    {
        $range = $this->currentRange();

        $value = $this->query
            ->when($range, fn (Builder $query) => $query
                ->whereBetween(...$this->resolveBetween($range))
            )
            ->{$this->type}($this->column);

        return $this->transformResult($value);
    }
}
