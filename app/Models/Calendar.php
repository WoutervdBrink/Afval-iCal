<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Calendar extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'address_id',
        'remind_me_on',
        'remind_me_at',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'remind_me_at'
    ];

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }
}
