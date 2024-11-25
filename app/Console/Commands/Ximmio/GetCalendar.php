<?php

namespace App\Console\Commands\Ximmio;

use App\Ximmio\Models\Address;
use App\Ximmio\Models\Collection;
use App\Ximmio\Ximmio;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Http\Client\HttpClientException;

class GetCalendar extends Command implements PromptsForMissingInput
{
    use SearchesForCompanyCodes;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ximmio:calendar
                            {--company= : The code of the company to which the address belongs}
                            {addressId : The ID of the address for which to retrieve the calendar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve a Ximmio calendar';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $companyCode = $this->option('company');

        if (empty($companyCode)) {
            $companyCode = $this->searchForCompanyCode();
        }

        $address = Address::of($companyCode, $this->argument('addressId'));

        try {
            $collections = Ximmio::getCollections($address);
        } catch (HttpClientException $e) {
            $this->error('Error retrieving address: ' . $e->getMessage());
            return;
        }

        $this->table(
            ['Type', 'Pickup Date'],
            collect($collections)
                ->sortBy('pickupDate')
                ->map(fn(Collection $collection): array => [$collection->type, $collection->pickupDate])
                ->toArray()
        );
    }
}
