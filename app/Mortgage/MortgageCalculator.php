<?php

namespace App\Mortgage;

class MortgageCalculator {

    /**
    * This function calculate monthly payment based on the ff params
    * @param amount
    * @param interest
    * @param term
    * and logic (Loan amount * Monthly interest rate) / (1 - (1 + Monthly interest rate)^(-Number of months))
    * and returns @return monthlyPayment
    */
    public function calculateMonthlyPayment($amount, $interest, $term) {
        $monthlyInterestRate = ($interest / 12) / 100;
        $numberOfMonths = $term * 12;
        $monthlyPayment = ($amount * $monthlyInterestRate) / (1 - pow((1 + $monthlyInterestRate), (-1 * $numberOfMonths)));

        return round($monthlyPayment, 2);
    }

    /**
    * This function calculate monthly interest based on the ff params
    * @param amount
    * @param interest
    * and logic (Loan amount * Monthly interest rate)
    * and returns @return monthly interest
    */
    public function calculateMonthlyInterest($amount, $interest) {
        $monthlyInterestRate = ($interest / 12) / 100;

        return $amount * $monthlyInterestRate;
    }

    /**
    * This function get monthly details in list
    * @param amount
    * @param interest
    * @param term
    * @param extra
    * and returns @return array of principal, monthly interest, remaining amount
    */
    public function getMonthlyDetailInList($amount, $interest, $term, $extra = 0) {
        $monthlyList = [];

        $termInMonth = $term * 12;
        $monthlyPayment = MortgageCalculator::calculateMonthlyPayment($amount, $interest, $term);
        $lastRemainingAmount = $amount;

        foreach (range(0, $termInMonth) as $i) {
            $monthlyInterest = MortgageCalculator::calculateMonthlyInterest($amount, $interest);

            $principal = $monthlyPayment - $monthlyInterest;
            $amount = $amount - $principal;
            $remainingTerm = $termInMonth - ($i+1);

            //if it is last term, use last remaining amount as principal
            //check if last remaing amount is less than twice monthly paid amount to know if it is last term
            $remainingAmount = $amount - (($i+1) * $extra);
            if ($remainingTerm == 0 || $lastRemainingAmount < ($monthlyPayment + $extra)) {
                $principal = $lastRemainingAmount;
                $remainingAmount = 0;
            }
            $lastRemainingAmount = $remainingAmount;

            //round values
            $remainingAmount = round($remainingAmount, 2, PHP_ROUND_HALF_EVEN);
            $amount = round($amount, 2, PHP_ROUND_HALF_EVEN);
            $principal = round($principal, 2, PHP_ROUND_HALF_EVEN);
            $monthlyInterest = round($monthlyInterest, 2, PHP_ROUND_HALF_EVEN);

            $monthlyList[] = ["principal" => $principal,
                            "interest" => $monthlyInterest,
                            "balance" => $remainingAmount,
                            "remainingTerm" => $remainingTerm];

            if ($remainingAmount == 0) {
                break;
            }

        }

        //if there is extra payment recalculate remaining term
        if ($extra > 0) {
            $remainingTerm = $termInMonth - $remainingTerm;
            foreach ($monthlyList as $i => $monthDetail) {
                $monthlyList[$i]["remainingTerm"] = $remainingTerm - ($i+1);
            }
        }

        return $monthlyList;
    }

}
