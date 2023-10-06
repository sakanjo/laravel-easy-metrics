<?php

namespace SaKanjo\EasyMetrics\Metrics;

use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use SaKanjo\EasyMetrics\Result;

class Doughnut extends Metric
{
    protected string $groupBy;

    protected ?array $options = null;

    public function options(array $options): static
    {
        $this->options = $options;

        return $this;
    }

    public function min(string $column, string $groupBy)
    {
        return $this->setType('min', $groupBy, $column);
    }

    public function max(string $column, string $groupBy)
    {
        return $this->setType('max', $groupBy, $column);
    }

    public function sum(string $column, string $groupBy)
    {
        return $this->setType('sum', $groupBy, $column);
    }

    public function average(string $column, string $groupBy)
    {
        return $this->setType('avg', $groupBy, $column);
    }

    public function count(string $groupBy, string $column = '*')
    {
        return $this->setType('count', $groupBy, $column);
    }

    protected function setType(string $type, string $groupBy, string $column)
    {
        $this->type = $type;
        $this->column = $column;
        $this->groupBy = $groupBy;

        return $this->resolve();
    }

    public function resolve(): Result
    {
        $column = $this->query->getQuery()->getGrammar()->wrap($this->column);
        $range = $this->currentRange();

        $results = $this->query
            ->when($range, fn (Builder $query) => $query
                ->whereBetween(...$this->resolveBetween($range))
            )
            ->select([$this->groupBy, DB::raw("{$this->type}($column) as result")])
            ->groupBy($this->groupBy)
            ->get()
            ->mapWithKeys(function (Model $model) {
                $key = $model[$this->groupBy];
                $key = $key instanceof BackedEnum ? $key->value : $key;

                return [
                    $key => $this->transformResult($model['result']),
                ];
            })
            ->toArray();

        $options = array_fill_keys($this->options ?? [], 0);

        $data = collect($options)
            ->replace($results)
            ->toArray();

        return Result::make(
            array_values($data),
            array_keys($data)
        );
    }
}
