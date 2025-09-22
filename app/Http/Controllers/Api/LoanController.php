<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLoanRequest;
use App\Models\Loan;
use Illuminate\Http\Request;
use App\Mail\LoanNotificationMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class LoanController extends Controller
{
    // public function apply(StoreLoanRequest $request)
    // {

    //     try {
    //         $loan = Loan::create($request->validated());

    //         return response()->json([
    //             'status' => 'success',
    //             'data' => $loan,
    //             'message' => 'Loan created successfully'
    //         ], 201);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'data' => null,
    //             'message' => $e->getMessage()
    //         ], 500);
    //     }
    // }


    public function apply(StoreLoanRequest $request)
    {
        try {
            $validated = $request->validated();

            $emi = $this->calculateEmi(
                $validated['principal'],
                $validated['tenure'],
                $validated['interest_rate'],
                $validated['loan_type']
            );

            $loan = Loan::create([
                'customer_name' => $validated['customer_name'],
                'principal' => $validated['principal'],
                'tenure' => $validated['tenure'],
                'interest_rate' => $validated['interest_rate'],
                'loan_type' => $validated['loan_type'],
                'emi' => $emi,
                'remaining_principal' => $validated['principal'],
            ]);
            $nextDueDate = Carbon::now()->addMonth()->format('Y-m-d');

            Mail::to('customer_email@example.com')
                ->queue(new LoanNotificationMail(
                    $loan->customer_name,
                    $loan->principal,
                    $loan->principal,
                     $nextDueDate,
                    'approved'
                ));

            return response()->json([
                'status' => 'success',
                'data' => [
                    'loan_id' => $loan->id,
                    'emi_schedule' => $emi,
                ],
                'message' => 'Loan created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'message' => $e->getMessage()
            ], 500);
        }
    }



    private function calculateEmi($principal, $tenure, $rate, $loanType)
    {
        $monthlyRate = $rate / 12 / 100;

        if ($loanType === 'Flat') {
            $emi = ($principal + ($principal * $rate * $tenure) / 1200) / $tenure;
        } else {
            $emi = ($principal * $monthlyRate * pow(1 + $monthlyRate, $tenure)) /
                (pow(1 + $monthlyRate, $tenure) - 1);
        }

        return round($emi, 2);
    }


    public function show($id)
    {
        try {
            $loan = Loan::find($id);

            return response()->json([
                'status' => 'success',
                'data' => $loan,
                'message' => 'Loan details fetched successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function emiSchedule($loanId)
    {
        $loan = Loan::find($loanId);

        if (!$loan) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'message' => 'Loan not found'
            ], 404);
        }

        $schedule = $this->calculateEmiSchedule(
            $loan->principal,
            $loan->tenure,
            $loan->interest_rate,
            $loan->loan_type
        );

        return response()->json([
            'status' => 'success',
            'data' => [
                'loan_id' => $loan->id,
                'emi_schedule' => $schedule
            ],
            'message' => 'EMI schedule retrieved successfully'
        ]);
    }

    private function calculateEmiSchedule($principal, $tenure, $rate, $loanType)
    {
        $monthlyRate = $rate / 12 / 100;
        $schedule = [];

        if ($loanType === 'Flat') {
            $emi = ($principal + ($principal * $rate * $tenure) / 1200) / $tenure;

            for ($i = 1; $i <= $tenure; $i++) {
                $schedule[] = [
                    'month' => $i,
                    'emi' => round($emi, 2),
                    'principal_remaining' => round(max($principal - ($emi * $i), 0), 2),
                ];
            }
        } else {
            $emi = ($principal * $monthlyRate * pow(1 + $monthlyRate, $tenure)) /
                (pow(1 + $monthlyRate, $tenure) - 1);
            $balance = $principal;

            for ($i = 1; $i <= $tenure; $i++) {
                $interest = $balance * $monthlyRate;
                $principalComponent = $emi - $interest;
                $balance -= $principalComponent;

                $schedule[] = [
                    'month' => $i,
                    'emi' => round($emi, 2),
                    'principal_component' => round($principalComponent, 2),
                    'interest_component' => round($interest, 2),
                    'principal_remaining' => round(max($balance, 0), 2),
                ];
            }
        }

        return $schedule;
    }
}
