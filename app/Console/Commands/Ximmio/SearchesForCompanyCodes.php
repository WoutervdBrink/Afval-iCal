<?php

namespace App\Console\Commands\Ximmio;

use App\Models\Company;
use function Laravel\Prompts\search;

trait SearchesForCompanyCodes
{
    protected function searchForCompanyCode(): string
    {
        return search(
            'Select a company:',
            options: fn($value) => strlen($value) > 0
                ? Company::where('name', 'like', '%' . $value . '%')
                    ->orWhere('code', 'like', '%' . $value . '%')
                    ->pluck('name', 'code')
                    ->all()
                : [],
            placeholder: 'E.g. TwenteMilieu',
            hint: 'No results? Run the database seeder first!',
        );
    }
}
