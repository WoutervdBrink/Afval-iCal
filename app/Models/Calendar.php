<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class Calendar extends Model
{
    use HasFactory;
    use HasUuids;
    use Notifiable;

    protected $fillable = [
        'address_id',
        'remind_me_on',
        'remind_me_at',
        'pushover_key',
        'pushover_pushed_at',
    ];

    protected $casts = [
        'remind_me_at' => 'datetime',
        'pushover_pushed_at' => 'datetime',
    ];

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function routeNotificationForPushover()
    {
        return $this->pushover_key;
    }
}
