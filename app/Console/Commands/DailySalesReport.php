<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BackOffice\ReportService;
use App\Mail\DailySalesReportMail;
use Illuminate\Support\Facades\Mail;

class DailySalesReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:sales';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily sales report';

    /**
     * Execute the console command.
     */
    public function handle(ReportService $reportService)
    {
        $report = $reportService->generateDailyReport();
        Mail::to(config('mail.admin_mail'))
            ->send(new DailySalesReportMail($report));
        $this->info('Daily report sent successfully');
    }
}
