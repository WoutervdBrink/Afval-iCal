<?php

namespace App\Http\Ximmio;

use Carbon\Carbon;

class Collection
{
    public function __construct(
        public readonly Carbon $pickupDate,
        public readonly string $type
    )
    {
    }
}
