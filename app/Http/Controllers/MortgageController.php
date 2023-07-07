<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use MortgageCalculator;

class MortgageController extends Controller
{
    public function calculate(Request $request) {
        $input = $request->all();

        $validator = Validator::make($input, [
            'amount' => 'required|numeric|min:0',
            'interest' => 'required|numeric|min:0|max:100',
            'term' => 'required|numeric|min:1',
            'extra' => 'numeric|min:0',
        ], [
            'amount.required' => 'The Loan Amount is required.',
            'interest.required' => 'The Annual Interest Rate is required.',
            'term.required' => 'The Loan Term is required.',
            'interest.min' => 'The Annual Interest Rate should be between 0 and 100.',
            'interest.max' => 'The Annual Interest Rate should be between 0 and 100.',
            'term.min' => 'The Loan Term should be greater than 1.',
            'amount.min' => 'The Loan Amount should be greater than 0.'
        ]);

        if ($validator->fails() || count($validator->errors()) > 0) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $extra = isset($input['extra']) ? $input['extra'] : 0;

        return response()->json(MortgageCalculator::getMonthlyDetailInList($input['amount'], $input['interest'], $input['term'], $extra));
    }
}
