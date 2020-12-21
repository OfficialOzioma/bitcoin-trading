<?php

namespace App\Console\Commands;

use App\Repository\AffiliateRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AffiliationFeeCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:affiliationfee';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'deposit last month\'s affiliate reward to user\'s
                                 wallet at the first day of the month for cpocket wallet';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(AffiliateRepository $affiliateRepository)
    {
        Log::info('Affiliation bonus cron job called');
        $affiliateRepository->depositAffiliationFees();
        Log::info('Affiliation bonus cron job run successful');

    }
}
