<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StoreLoanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name' => 'required|string|max:255',
            'principal' => 'required|numeric|min:1',
            'tenure' => 'required|integer|min:1',
            'interest_rate' => 'required|numeric|min:0',
            'loan_type' => 'required|in:Flat,Reducing',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'status' => 'error',
            'data' => null,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);

        throw new ValidationException($validator, $response);
    }
}
