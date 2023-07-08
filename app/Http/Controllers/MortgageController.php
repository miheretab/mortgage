<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalculateMortgageRequest;
use App\Models\ExtraRepaymentSchedule;
use App\Models\LoanAmortizationSchedule;
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

        $totalInterest = round(array_sum(array_column($monthlySchedule, "interest")), 2);
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
            $monthlyPayment = $monthlyDetail["principal"] + $monthlyDetail["interest"];

            LoanAmortizationSchedule::create([
                'month_number' => $i+1,
                'starting_balance' => $startingBalance,
                'monthly_payment' => $monthlyPayment,
                'principal_component' => $monthlyDetail["principal"],
                'interest_component' => $monthlyDetail["interest"],
                'ending_balance' => $monthlyDetail["balance"]
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
            $monthlyPayment = $monthlyDetail["principal"] + $monthlyDetail["interest"];

            ExtraRepaymentSchedule::create([
                'month_number' => $i+1,
                'starting_balance' => $startingBalance,
                'monthly_payment' => $monthlyPayment,
                'principal_component' => $monthlyDetail["principal"],
                'interest_component' => $monthlyDetail["interest"],
                'extra_repayment' => $extraPaymentMonthly,
                'ending_balance' => $monthlyDetail["balance"],
                'remaining_loan_term' => $monthlyDetail["remainingTerm"]
            ]);
        }
    }
}
