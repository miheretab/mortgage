<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CalculateMortgageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'amount' => 'required|numeric|min:0',
            'interest' => 'required|numeric|min:0|max:100',
            'term' => 'required|numeric|min:1',
            'extra' => 'numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'amount.required' => 'The Loan Amount is required.',
            'interest.required' => 'The Annual Interest Rate is required.',
            'term.required' => 'The Loan Term is required.',
            'interest.min' => 'The Annual Interest Rate should be between 0 and 100.',
            'interest.max' => 'The Annual Interest Rate should be between 0 and 100.',
            'term.min' => 'The Loan Term should be greater than 1.',
            'amount.min' => 'The Loan Amount should be greater than 0.'
        ];
    }
}
