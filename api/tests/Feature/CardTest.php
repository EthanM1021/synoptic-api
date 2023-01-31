<?php

namespace Tests\Feature;

use App\Models\Card;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CardTest extends TestCase
{
    /**
     * @test
     * Can get the pin for an employee
     *
     * @return void
     */
    public function getHashedPinForEmployee(): void
    {
        $factory = Card::factory()->create();
        $factoryArray = $factory->attributesToArray();

        $response = $this->getJson(
            route('pin.show', $factoryArray["id"])
        );

        $this->assertCount(1, $response->json());
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                "employee" => [
                    "pin"
                ]
            ])
            ->assertJson([
                "employee" => [
                    "pin" => $factoryArray["pin"]
                ]
            ]);
    }
}
