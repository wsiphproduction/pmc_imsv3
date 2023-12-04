<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\PaymentSchedule;
use App\PO;
use App\DailyPending;
use Carbon\Carbon;

class DailyPendingUpdate extends Command
{
   
    protected $signature = 'update:daily-pending';
    protected $description = 'Update daily pending records.';

    public function handle()
    {
        $overdue_payables = PaymentSchedule::totalOverduePayables();
        $overdue_completion = PO::totalOverduePO_new();
        $total_open_po = PO::totalOpenPO();

        $date_exists = DailyPending::where('date', Carbon::now())->first();

        if ($date_exists == null) {
            DailyPending::create([
                'date' => Carbon::now(),
                'overdue_payable' => $overdue_payables,
                'overdue_completion' => $overdue_completion,
                'total_open_po' => $total_open_po,
                'created_at' => Carbon::now()->format('Y-m-d H:i'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i')
            ]);

            $this->info('Daily Pending records updated successfully.');
        } else {
            $this->info('Daily Pending records already exist for today.');
        }
    }
}

//     protected $signature = 'command:name';

//     /**
//      * The console command description.
//      *
//      * @var string
//      */
//     protected $description = 'Command description';

//     /**
//      * Create a new command instance.
//      *
//      * @return void
//      */
//     public function __construct()
//     {
//         parent::__construct();
//     }

//     /**
//      * Execute the console command.
//      *
//      * @return mixed
//      */
//     public function handle()
//     {
//         //
//     }
// }
