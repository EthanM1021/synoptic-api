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
            ->assertJson([
                'pin'
            ]);
    }
}
