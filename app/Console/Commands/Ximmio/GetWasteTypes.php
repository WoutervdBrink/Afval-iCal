<?php

namespace App\Console\Commands\Ximmio;

use App\Ximmio\Models\WasteType;
use App\Ximmio\Ximmio;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Http\Client\HttpClientException;

class GetWasteTypes extends Command implements PromptsForMissingInput
{
    use SearchesForCompanyCodes;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ximmio:types
                            {company : The code of the company for which to query waste types}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the waste types processed by a company';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $companyCode = $this->argument('company');

        try {
            $types = Ximmio::getWasteTypes($companyCode);
        } catch (HttpClientException $e) {
            $this->error('Error retrieving waste types: ' . $e->getMessage());
            return;
        }

        $this->table(
            ['ID', 'Name'],
            collect($types)
                ->sort('name')
                ->map(fn(WasteType $type): array => [$type->code, $type->name])
                ->toArray()
        );
    }

    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'companyCode' => fn() => $this->searchForCompanyCodes()
        ];
    }
}
