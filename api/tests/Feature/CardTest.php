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

        $response = $this->patchJson(
            route('card.pay', 1),
            $requestBody
        );

        $response->assertStatus(200)
            ->assertJsonStructure([
                "card_id",
                "employee_id",
                "credit"
            ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function employeeCannotPayIfThereIsAnInsufficientAmountOnTheirRecord(): void
    {
        Employee::factory()->count(1)->create();
        Card::factory()->count(1)->create();

        $requestBody = [
            'productTotal' => "2000.98"
        ];

        $response = $this->patchJson(
            route('card.pay', 1),
            $requestBody
        );

        $response->assertStatus(400)
            ->assertJsonStructure([
                "error",
                "message"
            ])
            ->assertJson([
                "error" => true,
                "message" => 'Employee does not have the funds for this purchase'
            ]);
    }

    public function errorOccursWhenAnEmployeeIdIsNotFound(): void
    {
        Employee::factory()->count(1)->create();
        Card::factory()->count(1)->create();

        $requestBody = [
            'productTotal' => "10.00"
        ];

        $response = $this->patchJson(
            route('card.pay', 10),
            $requestBody
        );

        $response->assertStatus(404)
            ->assertJsonStructure([
                "error",
                "message"
            ])
            ->assertJson([
                "error" => true,
                "message" => 'Employee does not exist in our records'
            ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function employeeCanTopUpTheirCreditOnTheirCard(): void
    {
        Employee::factory()->count(1)->create();
        Card::factory()->count(1)->create([
            "id" => 'abcd1928fghi1847',
            "credit" => '15.00',
        ]);

        $requestBody = [
            'card_id' => 'abcd1928fghi1847',
            'amount' => '30.00'
        ];

        $response = $this->patchJson(
            route('card.topup', 1),
            $requestBody
        );

        $response->assertStatus(200)
            ->assertJsonStructure([
                "card_id",
                "credit",
                "message"
            ])
            ->assertJson([
                "card_id" => 'abcd1928fghi1847',
                "employee_id" => "1",
                "credit" => "45.00",
                "message" => "Credit updated successfully"
            ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function employeeCannotTopUpTheirCreditOnTheirCard(): void
    {
        Employee::factory()->count(1)->create();
        Card::factory()->count(1)->create([
            "credit" => '15.00',
        ]);

        $requestBody = [
            'amount' => '30.00'
        ];

        $response = $this->patchJson(
            route('card.topup', 100),
            $requestBody
        );

        $response->assertStatus(404)
            ->assertJsonStructure([
                "error",
                "message"
            ])
            ->assertJson([
                "error" => true,
                "message" => 'Card not found with this id'
            ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function cannotTopupIfRequestBodyIsEmpty(): void
    {
        Employee::factory()->count(1)->create();
        Card::factory()->count(1)->create([
            "credit" => '15.00',
        ]);

        $requestBody = [
            'amount' => ''
        ];

        $response = $this->patchJson(
            route('card.topup', 1),
            $requestBody
        );

        $response->assertStatus(400)
            ->assertJsonStructure([
                "error",
                "message"
            ])
            ->assertJson([
                "error" => true,
                "message" => 'No amount to add to employee card'
            ]);
    }
}
