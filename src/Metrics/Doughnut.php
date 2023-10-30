<?php

namespace SaKanjo\EasyMetrics\Metrics;

use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use SaKanjo\EasyMetrics\Result;

class Doughnut extends Metric
{
    protected string $groupBy;

    protected ?array $options = null;

    public function options(array|string $options): static
    {
        if (is_string($options)) {
            if (! enum_exists($options)) {
                throw new \Exception("Enum $options does not exist");
            }

            $options = Arr::pluck($options::cases(), 'value');
        }

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

    public function resolveValue(?array $range): array
    {
        $column = $this->query->getQuery()->getGrammar()->wrap($this->column);

        $results = $this->query
            ->clone()
            ->withoutEagerLoads()
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

        $data = array_replace($options, $results);

        return $data;
    }

    public function resolvePreviousValue(): array
    {
        $range = $this->previousRange();

        if (! $range) {
            return [];
        }

        return $this->resolveValue($range);
    }

    public function resolveCurrentValue(): array
    {
        return $this->resolveValue(
            $this->currentRange()
        );
    }

    public function resolveGrowthRate(array $previousData, array $currentData): array
    {
        $growthRate = [];

        foreach ($currentData as $key => $currentValue) {
            $previousValue = $previousData[$key] ?? 0;

            $growthRate[$key] = $this->growthRateType->getValue($previousValue, $currentValue);
        }

        return $growthRate;
    }

    public function resolve(): Result
    {
        if (! $this->withGrowthRate) {
            $data = $this->resolveCurrentValue();

            return Result::make(
                array_values($data),
                array_keys($data),
                null
            );
        }

        $previousData = $this->resolvePreviousValue();
        $currentData = $this->resolveCurrentValue();

        return Result::make(
            array_values($currentData),
            array_keys($currentData),
            $this->resolveGrowthRate($previousData, $currentData)
        );
    }
}
