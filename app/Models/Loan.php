<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'principal',
        'tenure',
        'interest_rate',
        'loan_type',
        'emi',
        'remaining_principal',
    ];

    public function payments()
    {
        return $this->hasMany(LoanPayment::class);
    }


    // Calculate remaining principal dynamically
    public function calculateRemainingPrincipal()
    {
        return $this->principal - $this->payments()->sum('amount');
    }

    // Generate EMI schedule
    public function emiSchedule()
    {
        $emiSchedule = [];
        $principalRemaining = $this->principal;
        $monthlyInterestRate = ($this->interest_rate / 100) / 12;

        for ($i = 1; $i <= $this->tenure; $i++) {
            if ($this->loan_type === 'Flat') {
                $interest = $this->principal * $monthlyInterestRate;
                $principalPayment = $this->principal / $this->tenure;
            } else { // Reducing
                $interest = $principalRemaining * $monthlyInterestRate;
                $principalPayment = $this->emi - $interest;
            }

            $emiSchedule[] = [
                'installment' => $i,
                'principal_payment' => round($principalPayment, 2),
                'interest' => round($interest, 2),
                'total_emi' => round($principalPayment + $interest, 2),
                'remaining_principal' => round($principalRemaining - $principalPayment, 2)
            ];

            $principalRemaining -= $principalPayment;
        }

        return $emiSchedule;
    }
}
