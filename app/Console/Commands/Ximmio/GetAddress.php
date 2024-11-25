<?php

namespace App\Console\Commands\Ximmio;

use App\Ximmio\Ximmio;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Http\Client\HttpClientException;

class GetAddress extends Command implements PromptsForMissingInput
{
    use SearchesForCompanyCodes;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ximmio:address
                            {--company= : The code of the company to which the address belongs}
                            {postalCode : The postal code of the address}
                            {houseNumber : The house number of the address}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Look up an address in the Ximmio API';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $companyCode = $this->option('company');

        if (empty($companyCode)) {
            $companyCode = $this->searchForCompanyCode();
        }

        try {
            $address = Ximmio::getAddress($companyCode, $this->argument('postalCode'), $this->argument('houseNumber'));
        } catch (HttpClientException $e) {
            $this->error('Error retrieving address: ' . $e->getMessage());
            return;
        }

        if (is_null($address)) {
            $this->error('No address was found!');
            return;
        }

        $this->table(
            ['Field', 'Value'],
            [
                ['ID', $address->id],
                ['Street', $address->street],
                ['House number', $address->houseNumber],
                ['City', $address->city],
                ['Postal Code', $address->postalCode],
            ]
        );
    }
}
