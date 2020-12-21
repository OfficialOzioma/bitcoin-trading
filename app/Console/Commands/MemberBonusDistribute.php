<?php

namespace App\Console\Commands;

use App\Repository\ClubRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MemberBonusDistribute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:membershipbonus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'distribute monthly membership bonus distribution to add bonus to user default wallet';

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
    public function handle(ClubRepository $clubRepository)
    {
        Log::info('Member bonus cron job called');
        $clubRepository->clubBonusDistributionProcess();
        Log::info('Member bonus cron job run successful');
    }
}
