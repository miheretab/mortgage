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

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonStructure(['error' => ['amount']]);
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

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonStructure(['error' => ['interest']]);
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
        $response->assertJsonStructure([]);
    }

}
