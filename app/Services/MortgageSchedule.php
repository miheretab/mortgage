<?php

namespace App\Services;

use App\Models\ExtraRepaymentSchedule;
use App\Models\LoanAmortizationSchedule;

class MortgageSchedule {

    /**
    * Assuming only one generated table to show schedule (not related to any user)
    * @param $startingBalance
    * @param $monthlySchedule generated based on morgate calculator
    * with first index - principal, second index - interest and third index - balance
    */
    public static function storeLoanAmortizationSchedule($startingBalance, $monthlySchedule) {
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
    * @param $extraPaymentMonthly
    * @param $monthlySchedule generated based on morgate calculator
    * with first index - principal, second index - interest, third index - balance and fourth index - remaining term
    */
    public static function storeExtraRepaymentSchedule($startingBalance, $extraPaymentMonthly, $monthlySchedule) {
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