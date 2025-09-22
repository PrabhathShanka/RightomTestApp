<h2>Loan {{ ucfirst($type) }}</h2>

<p>Hello {{ $customerName }},</p>

@if($type === 'approved')
<p>Your loan of amount <strong>{{ $loanAmount }}</strong> has been approved.</p>
@else
<p>Your repayment of <strong>{{ $loanAmount }}</strong> has been recorded.</p>
<p>Outstanding balance: <strong>{{ $outstandingBalance }}</strong></p>
<p>Next due date: <strong>{{ $nextDueDate }}</strong></p>
@endif

<p>Thank you for using our service.</p>
