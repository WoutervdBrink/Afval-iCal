<?php

namespace App\Models;

use App\Ximmio\Models\WasteType as XimmioWasteType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WasteType extends Model
{
    protected $primaryKey = 'code';
    public $incrementing = false;

    public $timestamps = false;

    /**
     * @return BelongsTo<Company, $this>
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public static function upsert(XimmioWasteType $wasteType): WasteType
    {
        $instance = WasteType::where('company_code', $wasteType->companyCode)
            ->where('code', $wasteType->code)
            ->first();

        if (is_null($instance)) {
            $instance = new WasteType();
        }

        $instance->company_code = $wasteType->companyCode;
        $instance->code = $wasteType->code;
        $instance->name = $wasteType->name;

        $instance->save();

        return $instance;
    }
}
