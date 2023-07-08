<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class MortgageTest extends TestCase
{

    /**
    * Test Api's
    */
    public function test_calculator_invalid_get_method()
    {

        $response = $this->json('get', 'api/calculate-mortgage');

        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    public function test_calculator_invalid_input_format1()
    {
        $payload = [
            'amount' => -1,
            'interest' => 5,
            'term' => 2,
            'extra' => 0
        ];

        $response = $this->json('post', 'api/calculate-mortgage', $payload);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure(['message', 'errors' => ['amount']]);
    }

    public function test_calculator_invalid_input_format2()
    {
        $payload = [
            'amount' => 10000,
            'interest' => -5,
            'term' => 0,
            'extra' => 0
        ];

        $response = $this->json('post', 'api/calculate-mortgage', $payload);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure(['message', 'errors' => ['interest']]);
    }

    public function test_calculator_valid_input()
    {
        $payload = [
            'amount' => 10000,
            'interest' => 5,
            'term' => 2,
            'extra' => 0
        ];

        $response = $this->json('post', 'api/calculate-mortgage', $payload);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(['schedule']);
    }

    public function test_calculator_valid_input_with_extra()
    {
        $payload = [
            'amount' => 10000,
            'interest' => 5,
            'term' => 10,
            'extra' => 50
        ];

        $response = $this->json('post', 'api/calculate-mortgage', $payload);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(['schedule']);
        $this->assertEquals($response['schedule'][0]["principal"], 64.40);
        $this->assertEquals($response['schedule'][0]["interest"], 41.67);
        $this->assertEquals($response['schedule'][0]["balance"], 9885.60);
        $this->assertEquals($response['schedule'][0]["remainingTerm"], 79);
        $this->assertEquals($response['effectiveInterestRate'], 23.86);
        $this->assertEquals($response['totalInterest'], 2385.62);
    }

    /**
    * Testing Views
    */
    public function test_calculator_view()
    {
        $response = $this->get('/');

        $response->assertSee("Mortgage Calculator");
        $response->assertSee("Loan Amount");
        $response->assertSee("Loan Term");
        $response->assertSee("Shortened Term");
        $response->assertSee("Annual Interest Rate");
        $response->assertSee("Effective Interest Rate");
        $response->assertSee("Total Interest");
        $response->assertSee("Monthly Payment");
    }
}
