<?php

namespace App\Console\Commands;

use App\Models\Address;
use App\Models\Calendar;
use Illuminate\Console\Command;

class FlushCalendars extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calendars:flush';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush inactive calendars';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Calendar::where('updated_at', '<', now()->subMonth())->delete();
        Address::where('updated_at', '<', now()->subMonth())->delete();

        return Command::SUCCESS;
    }
}
