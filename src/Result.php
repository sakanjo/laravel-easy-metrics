<?php

namespace SaKanjo\EasyMetrics;

use ArrayAccess;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;

class Result implements ArrayAccess, Responsable
{
    public array $container;

    public function __construct(
        protected array $data,
        protected array $labels
    ) {
        $this->container = [$labels, $data];
    }

    public static function make(array $data, array $labels): static
    {
        return app(static::class, [
            'data' => $data,
            'labels' => $labels,
        ]);
    }

    public function getOptions(): array
    {
        return array_combine($this->labels, $this->data);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getLabels(): array
    {
        return $this->labels;
    }

    public function toResponse($request): Response
    {
        return new Response(
            $this->getData()
        );
    }

    public function offsetSet($offset, $value): void
    {
        throw new \Exception('Result is immutable');
    }

    public function offsetExists($offset): bool
    {
        return isset($this->container[$offset]);
    }

    public function offsetUnset($offset): void
    {
        throw new \Exception('Result is immutable');
    }

    public function offsetGet($offset): mixed
    {
        return $this->container[$offset] ?? null;
    }
}
