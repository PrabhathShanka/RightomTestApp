<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoanNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $customerName;
    public $loanAmount;
    public $outstandingBalance;
    public $nextDueDate;
    public $type;

    public function __construct($customerName, $loanAmount, $outstandingBalance, $nextDueDate, $type)
    {
        $this->customerName = $customerName;
        $this->loanAmount = $loanAmount;
        $this->outstandingBalance = $outstandingBalance;
        $this->nextDueDate = $nextDueDate;
        $this->type = $type;
    }

    public function build()
    {
        return $this->subject("Loan Notification")
                    ->view('emails.loan_notification');
    }
}
