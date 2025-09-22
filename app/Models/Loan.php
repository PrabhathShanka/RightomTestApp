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
}
