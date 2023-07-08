<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalculateMortgageRequest;
use Illuminate\Http\Request;
use MortgageCalculator;
use Validator;

class MortgageController extends Controller
{
    public function calculate(CalculateMortgageRequest $request) {
        $input = $request->all();

        $extra = isset($input['extra']) ? $input['extra'] : 0;

        $monthlySchedule = MortgageCalculator::getMonthlyDetailInList($input['amount'], $input['interest'], $input['term'], $extra);
        $monthlyPayment = MortgageCalculator::calculateMonthlyPayment($input['amount'], $input['interest'], $input['term']);

        $totalInterest = round(array_sum(array_column($monthlySchedule, 1)), 2);
        $totalTerm = count($monthlySchedule);
        $effectiveInterestRate = round($totalInterest * 100/ $input['amount'], 2);

        if (isset($input['save']) && $extra > 0) {
            $this->storeExtraRepaymentSchedule($input['amount'], $extra, $monthlySchedule);
        } else if (isset($input['save'])) {
            $this->storeLoanAmortizationSchedule($input['amount'], $monthlySchedule);
        }

        return response()->json([
            'schedule' => $monthlySchedule,
            'monthlyPayment' => $monthlyPayment,
            'totalInterest' => $totalInterest,
            'totalTerm' => $totalTerm,
            'effectiveInterestRate' => $effectiveInterestRate
        ]);
    }

    /**
    * Assuming only one generated table to show schedule (not related to any user)
    * @param $startingBalance
    * @param $monthlySchedule generated based on morgate calculator
    * with index 0 - principal, index 1 - interest and index 2 - balance
    */
    public function storeLoanAmortizationSchedule($startingBalance, $monthlySchedule) {
        LoanAmortizationSchedule::truncate();

        foreach ($monthlySchedule as $i => $monthlyDetail) {
            $monthlyPayment = $monthlyDetail[0] + $monthlyDetail[1];

            LoanAmortizationSchedule::create([
                'month_number' => $i+1,
                'starting_balance' => $startingBalance,
                'monthly_payment' => $monthlyPayment,
                'principal_component' => $monthlyDetail[0],
                'interest_component' => $monthlyDetail[1],
                'ending_balance' => $monthlyDetail[2]
            ]);
        }
    }

    /**
    * Assuming only one generated table to show schedule (not related to any user)
    * @param $startingBalance
    * @param $monthlySchedule generated based on morgate calculator
    * with index 0 - principal, index 1 - interest, index 2 - balance and index 3 - remaining term
    */
    public function storeExtraRepaymentSchedule($startingBalance, $extraPaymentMonthly, $monthlySchedule) {
        ExtraRepaymentSchedule::truncate();

        foreach ($monthlySchedule as $i => $monthlyDetail) {
            $monthlyPayment = $monthlyDetail[0] + $monthlyDetail[1];

            ExtraRepaymentSchedule::create([
                'month_number' => $i+1,
                'starting_balance' => $startingBalance,
                'monthly_payment' => $monthlyPayment,
                'principal_component' => $monthlyDetail[0],
                'interest_component' => $monthlyDetail[1],
                'extra_repayment' => $extraPaymentMonthly,
                'ending_balance' => $monthlyDetail[2],
                'remaining_loan_term' => $monthlyDetail[3]
            ]);
        }
    }
}
