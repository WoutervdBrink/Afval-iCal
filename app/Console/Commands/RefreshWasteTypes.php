<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\WasteType;
use App\Ximmio\Models\WasteType as XimmioWasteType;
use App\Ximmio\Ximmio;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RefreshWasteTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:refresh-waste-types';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh waste types processed by the companies';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $companies = Company::all();

        foreach ($companies as $company) {
            $this->comment('Checking company ' . $company->name . ' (Code ' . $company->code . ')');
            $types = Ximmio::getWasteTypes($company->code);

            foreach ($types as $wasteType) {
                $instance = WasteType::upsert($wasteType);

                if ($instance->wasRecentlyCreated) {
                    $this->info('  New type: ' . $instance->name);
                }
            }
        }
    }
}
