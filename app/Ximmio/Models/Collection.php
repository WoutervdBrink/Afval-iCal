<?php

namespace App\Ximmio\Models;

use Carbon\Carbon;

readonly class Collection
{
    public function __construct(
        public string $type,
        public Carbon $pickupDate,
    )
    {
    }
}
