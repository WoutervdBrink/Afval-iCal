<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'company_code',
        'id',
        'street',
        'house_number',
        'postal_code',
        'city'
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_code');
    }
}
