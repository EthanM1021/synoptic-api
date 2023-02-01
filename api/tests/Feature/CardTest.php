<?php

namespace Tests\Feature;

use App\Models\Card;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @return void
     */
    public function getEmployeePin(): void
    {
        Employee::factory()->count(10)->create();
        Card::factory()->count(5)->create();

        $response = $this->getJson(route('pin.show', range('1', '5')));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'pin'
            ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function employeeDoesNotExistWhenGettingPin(): void
    {
        $response = $this->getJson(route('pin.show', 1));

        $response->assertStatus(404)
            ->assertJsonStructure([
                'error',
                'message'
            ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function passingAnInvalidIdWhenGettingThePin(): void
    {
        Employee::factory()->count(4)->create();
        Card::factory()->count(1)->create();

        $response = $this->getJson(route('pin.show', 'invalidId'));

        $response->assertStatus(404)
            ->assertJsonStructure([
                'error',
                'message'
            ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function employeeCanPayAndTheTotalWillBeDeductedFromTheirCredit(): void
    {
        Employee::factory()->count(1)->create();
        Card::factory()->count(1)->create();

        $requestBody = [
            'total' => floatval(rand(1, 100))
        ];

        $response = $this->putJson(
            route('card.pay', rand(1, 1)),
            $requestBody
        );

        $response->assertStatus(200)
            ->assertJsonStructure([
                "card_id",
                "employee_id",
                "credit"
            ]);
    }
}
