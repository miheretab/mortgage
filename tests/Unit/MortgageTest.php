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

        $detailList = [[64.40, 41.67, "9,935.60", 119], [64.67, 41.40, "9,870.93", 118],
                    [64.94, 41.13, "9,805.99", 117], [65.21, 40.86, "9,740.78", 116],
                    [65.48, 40.59, "9,675.30", 115], [65.76, 40.31, "9,609.54", 114],
                    [66.03, 40.04, "9,543.51", 113], [66.31, 39.76, "9,477.20", 112]];

        $mortgageCalculator = new MortgageCalculator();
        $monthlyList = $mortgageCalculator->getMonthlyDetailInList($amount, $interest, $term);

        foreach ($detailList as $i => $detail) {

            $this->assertEquals($monthlyList[$i], $detail);

            if ($i == 7) {
                break;
            }
        }

        $lastPrincipal = $monthlyList[count($monthlyList) - 1][0];
        $lastInterest = $monthlyList[count($monthlyList) - 1][1];
        $monthlyPayment = 106;
        $this->assertEquals(round((($term * 12) - 1) * $monthlyPayment + $lastPrincipal + $lastInterest), 12719);
    }

    public function test_monthly_payment_list_with_extra_logic() {
        $term = 10;
        $amount = 10000;
        $interest = 5;
        $extra = 50;

        $detailList = [[64.40, 41.67, "9,885.60", 79], [64.67, 41.40, "9,770.93", 78],
                    [64.94, 41.13, "9,655.99", 77], [65.21, 40.86, "9,540.78", 76],
                    [65.48, 40.59, "9,425.30", 75], [65.76, 40.31, "9,309.54", 74],
                    [66.03, 40.04, "9,193.51", 73], [66.31, 39.76, "9,077.20", 72]];

        $mortgageCalculator = new MortgageCalculator();
        $monthlyList = $mortgageCalculator->getMonthlyDetailInList($amount, $interest, $term, $extra);

        foreach ($detailList as $i => $detail) {

            $this->assertEquals($monthlyList[$i], $detail);

            if ($i == 7) {
                break;
            }
        }

        $lastPrincipal = $monthlyList[count($monthlyList) - 1][0];
        $lastInterest = $monthlyList[count($monthlyList) - 1][1];
        $monthlyPayment = 156;
        $remainingTerm = 79;
        $this->assertEquals(round($remainingTerm * $monthlyPayment + $lastPrincipal + $lastInterest), 12380);
    }
}
