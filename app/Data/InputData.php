<?php

namespace App\Data;

final class InputData
{
    public function __construct(
        public readonly int $customerId,
        public readonly float $amountUsd
    ) {
    }
}
