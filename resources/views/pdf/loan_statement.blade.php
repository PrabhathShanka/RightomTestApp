<h1>Loan Statement</h1>
<p><strong>Customer:</strong> {{ $loan->customer_name }}</p>
<p><strong>Loan Amount:</strong> {{ number_format($loan->principal, 2) }}</p>
<p><strong>Outstanding Balance:</strong> {{ number_format($remainingPrincipal, 2) }}</p>
<p><strong>Status:</strong> {{ $loan->status }}</p>

<h3>EMI Schedule</h3>
<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th>Installment</th>
            <th>Principal Payment</th>
            <th>Interest</th>
            <th>Total EMI</th>
            <th>Remaining Principal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($emiSchedule as $emi)
            <tr>
                <td>{{ $emi['installment'] }}</td>
                <td>{{ number_format($emi['principal_payment'], 2) }}</td>
                <td>{{ number_format($emi['interest'], 2) }}</td>
                <td>{{ number_format($emi['total_emi'], 2) }}</td>
                <td>{{ number_format($emi['remaining_principal'], 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
