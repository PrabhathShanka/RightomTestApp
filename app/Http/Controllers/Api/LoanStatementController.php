<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateLoanStatementJob;
use App\Models\Loan;

class LoanStatementController extends Controller
{
    public function generate($loanId)
    {
        $loan = Loan::find($loanId);
        if (!$loan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Loan not found',
            ], 404);
        }

        GenerateLoanStatementJob::dispatch($loanId);

        return response()->json([
            'status' => 'success',
            'message' => 'Loan statement generation started. You can download it shortly.'
        ]);
    }
}
