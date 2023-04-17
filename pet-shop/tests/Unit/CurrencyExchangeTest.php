<?php


namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class CurrencyExchangeTest extends TestCase
{
    /**
     * Test the currency exchange endpoint.
     *
     * @return void
     */
    public function testCurrencyExchange()
    {
        $response = $this->json('POST', '/currency-exchange', [
            'amount'        => 100,
            'currency_code' => 'USD'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                         'exchangedAmount' => true ,// check if exchangedAmount is returned
                         'rate'            => true ,// check if rate is returned
                         'exchangeCurrency'=> true // check if exchangeCurrency is returned
                 ]);
    }
}
