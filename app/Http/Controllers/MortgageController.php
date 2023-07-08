<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalculateMortgageRequest;
use App\Services\MortgageSchedule;
use Illuminate\Http\Request;
use MortgageCalculator;
use Validator;

class MortgageController extends Controller
{
    /**
    * calculate action for the api/calculate-mortgage
    */
    public function calculate(CalculateMortgageRequest $request, MortgageSchedule $mortgageSchedule) {
        $input = $request->all();

        $extra = isset($input['extra']) ? $input['extra'] : 0;

        $monthlySchedule = MortgageCalculator::getMonthlyDetailInList($input['amount'], $input['interest'], $input['term'], $extra);
        $monthlyPayment = MortgageCalculator::calculateMonthlyPayment($input['amount'], $input['interest'], $input['term']);

        $totalInterest = round(array_sum(array_column($monthlySchedule, "interest")), 2);
        $totalTerm = count($monthlySchedule);
        $effectiveInterestRate = round($totalInterest * 100/ $input['amount'], 2);

        if (isset($input['save']) && $extra > 0) {
            $mortgageSchedule->storeExtraRepaymentSchedule($input['amount'], $extra, $monthlySchedule);
        } else if (isset($input['save'])) {
            $mortgageSchedule->storeLoanAmortizationSchedule($input['amount'], $monthlySchedule);
        }

        return response()->json([
            'schedule' => $monthlySchedule,
            'monthlyPayment' => $monthlyPayment,
            'totalInterest' => $totalInterest,
            'totalTerm' => $totalTerm,
            'effectiveInterestRate' => $effectiveInterestRate
        ]);
    }

}
