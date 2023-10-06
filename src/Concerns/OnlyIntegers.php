<?php

namespace SaKanjo\EasyMetrics\Concerns;

trait OnlyIntegers
{
    public function ranges(array $ranges): static
    {
        foreach ($ranges as $range) {
            if (! is_int($range)) {
                throw new \InvalidArgumentException('The provided range must be an integer.');
            }
        }

        $this->ranges = $ranges;

        return $this;
    }

    public function getRange(): int
    {
        return $this->range ?? $this->getRanges()[0] ?? 15;
    }
}
