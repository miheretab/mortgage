<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Mortgage\MortgageCalculator;

class MortgageTest extends TestCase
{
    /**
    * Test calculator logics
    */
    public function test_calculator_logic() {
        $term = 10;
        $termInMonth = $term * 12;
        $amount = 10000;
        $interest = 5;

        $interests = [41.67, 41.40, 41.13, 40.86,
                    40.59, 40.31, 40.04, 39.76,
                    39.49, 39.21, 38.93, 38.65,
                    38.37, 38.09, 37.81, 37.52];

        $mortgageCalculator = new MortgageCalculator();
        $monthlyPayment = $mortgageCalculator->calculateMonthlyPayment($amount, $interest, $term);
        $this->assertEquals($monthlyPayment, 106.07);

        foreach (range(0, $termInMonth) as $i) {
            $monthlyInterest = $mortgageCalculator->calculateMonthlyInterest($amount, $interest);

            $this->assertEquals(round($monthlyInterest, 2), $interests[$i]);

            $amount -= ($monthlyPayment - $monthlyInterest);

            if ($i == 15) {
                break;
            }
        }
    }

    public function test_monthly_payment_list_logic() {
        $term = 10;
        $amount = 10000;
        $interest = 5;

        $detailList = [["principal" => 64.40, "interest" => 41.67, "balance" => 9935.60, "remainingTerm" => 119],
                    ["principal" => 64.67, "interest" => 41.40, "balance" => 9870.93, "remainingTerm" => 118],
                    ["principal" => 64.94, "interest" => 41.13, "balance" => 9805.99, "remainingTerm" => 117],
                    ["principal" => 65.21, "interest" => 40.86, "balance" => 9740.78, "remainingTerm" => 116],
                    ["principal" => 65.48, "interest" => 40.59, "balance" => 9675.30, "remainingTerm" => 115],
                    ["principal" => 65.76, "interest" => 40.31, "balance" => 9609.54, "remainingTerm" => 114],
                    ["principal" => 66.03, "interest" => 40.04, "balance" => 9543.51, "remainingTerm" => 113],
                    ["principal" => 66.31, "interest" => 39.76, "balance" => 9477.20, "remainingTerm" => 112]];

        $mortgageCalculator = new MortgageCalculator();
        $monthlyList = $mortgageCalculator->getMonthlyDetailInList($amount, $interest, $term);

        foreach ($detailList as $i => $detail) {

            $this->assertEquals($monthlyList[$i], $detail);

            if ($i == 7) {
                break;
            }
        }

        $lastPrincipal = $monthlyList[count($monthlyList) - 1]["principal"];
        $lastInterest = $monthlyList[count($monthlyList) - 1]["interest"];
        $monthlyPayment = 106;
        $this->assertEquals(round((($term * 12) - 1) * $monthlyPayment + $lastPrincipal + $lastInterest), 12719);
    }

    public function test_monthly_payment_list_with_extra_logic() {
        $term = 10;
        $amount = 10000;
        $interest = 5;
        $extra = 50;

        $detailList = [["principal" => 64.40, "interest" => 41.67, "balance" => 9885.60, "remainingTerm" => 79],
                    ["principal" => 64.67, "interest" => 41.40, "balance" => 9770.93, "remainingTerm" => 78],
                    ["principal" => 64.94, "interest" => 41.13, "balance" => 9655.99, "remainingTerm" => 77],
                    ["principal" => 65.21, "interest" => 40.86, "balance" => 9540.78, "remainingTerm" => 76],
                    ["principal" => 65.48, "interest" => 40.59, "balance" => 9425.30, "remainingTerm" => 75],
                    ["principal" => 65.76, "interest" => 40.31, "balance" => 9309.54, "remainingTerm" => 74],
                    ["principal" => 66.03, "interest" => 40.04, "balance" => 9193.51, "remainingTerm" => 73],
                    ["principal" => 66.31, "interest" => 39.76, "balance" => 9077.20, "remainingTerm" => 72]];

        $mortgageCalculator = new MortgageCalculator();
        $monthlyList = $mortgageCalculator->getMonthlyDetailInList($amount, $interest, $term, $extra);

        foreach ($detailList as $i => $detail) {

            $this->assertEquals($monthlyList[$i], $detail);

            if ($i == 7) {
                break;
            }
        }

        $lastPrincipal = $monthlyList[count($monthlyList) - 1]["principal"];
        $lastInterest = $monthlyList[count($monthlyList) - 1]["interest"];
        $monthlyPayment = 156;
        $remainingTerm = 79;
        $this->assertEquals(round($remainingTerm * $monthlyPayment + $lastPrincipal + $lastInterest), 12380);
    }
}
