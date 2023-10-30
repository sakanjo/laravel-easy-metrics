<?php

namespace SaKanjo\EasyMetrics;

use ArrayAccess;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;

class ValueResult implements ArrayAccess, Responsable
{
    public array $container;

    public function __construct(
        protected float $value,
        protected ?float $growthRate = null
    ) {
        $this->container = [$value, $growthRate];
    }

    public static function make(float $value, ?float $growthRate): static
    {
        return App::make(static::class, [
            'value' => $value,
            'growthRate' => $growthRate,
        ]);
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getGrowthRate(): ?float
    {
        return $this->growthRate;
    }

    public function toResponse($request): Response
    {
        return new Response(
            $this->getValue()
        );
    }

    public function offsetSet($offset, $value): void
    {
        throw new \Exception('ValueResult is immutable');
    }

    public function offsetExists($offset): bool
    {
        return isset($this->container[$offset]);
    }

    public function offsetUnset($offset): void
    {
        throw new \Exception('ValueResult is immutable');
    }

    public function offsetGet($offset): mixed
    {
        return $this->container[$offset] ?? null;
    }
}
