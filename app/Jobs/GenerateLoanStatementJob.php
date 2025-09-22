<?php

namespace App\Jobs;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class GenerateLoanStatementJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $loanId;

    public function __construct($loanId)
    {
        $this->loanId = $loanId;
    }

    public function handle()
    {
        $loan = Loan::with('emiSchedule')->find($this->loanId);

        if (!$loan) return;

        $data = [
            'customer' => $loan->customer_name,
            'loan' => $loan,
            'emi_schedule' => $loan->emiSchedule
        ];

        $pdf = Pdf::loadView('pdf.loan_statement', $data);
        $fileName = 'loan_statement_' . $loan->id . '.pdf';

        Storage::put('loan_statements/' . $fileName, $pdf->output());
    }
}
