<?php

namespace App\Http\Controllers\Api;

use App\Models\Loan;
use App\Models\LoanPayment;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLoanPaymentRequest;
use App\Mail\LoanNotificationMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;


class LoanPaymentController extends Controller
{
    public function repay(StoreLoanPaymentRequest $request, $loanId)
    {
        try {
            $loan = Loan::find($loanId);

            if (!$loan) {
                return response()->json([
                    'status' => 'error',
                    'data' => null,
                    'message' => 'Loan not found'
                ], 404);
            }

            $payment = LoanPayment::create([
                'loan_id' => $loan->id,
                'amount' => $request->amount,
                'payment_date' => $request->payment_date,
            ]);

            $loan->remaining_principal -= $request->amount;
            $loan->save();


            $message = $loan->remaining_principal <= 0
                ? 'Loan fully paid'
                : 'Payment recorded successfully';

            $nextDueDate = Carbon::now()->addMonth()->format('Y-m-d');


            Mail::to('customer_email@example.com')
                ->queue(new LoanNotificationMail(
                    $loan->customer_name,
                    $request->amount,
                    $loan->remaining_principal,
                    $nextDueDate,
                    'repayment'
                ));


            return response()->json([
                'status' => 'success',
                'data' => [
                    'loan_id' => $loan->id,
                    'paid_amount' => $payment->amount,
                    'remaining_principal' => $loan->remaining_principal,
                    'payment_id' => $payment->id
                ],
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
