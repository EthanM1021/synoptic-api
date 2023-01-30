<?php

namespace Tests\Feature;

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * Can get an employee from the database if the id exists
     *
     * @return void
     */
    public function getsEmployeeWhenThereIsOneEmployeeInTheTable(): void
    {
        // Creates an employee record from the EmployeeFactory with the faker library
        $factory = Employee::factory()->create();
        // Casts the attributes json object to an array
        $factoryArray = $factory->attributesToArray();

        // Gets the endpoint with the id of the employee record above
        $response = $this->getJson(
            route('employee.show', $factoryArray["id"])
        );

        // Asserts the amount of employees returned is 1 which it always will be when using the factory method above
        $this->assertCount(1, $response->json());
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                "employee" => [
                    "id",
                    "first_name",
                    "last_name",
                    "email_address",
                    "mobile_number",
                    "pin"
                ]
            ])
            ->assertJson([
                "employee" => [
                    "id" => $factoryArray["id"],
                    "first_name" => $factoryArray["first_name"],
                    "last_name" => $factoryArray["last_name"],
                    "email_address" => $factoryArray["email_address"],
                    "mobile_number" => $factoryArray["mobile_number"],
                    "pin" => $factoryArray["pin"]
                ]
            ]);
    }

    /**
     * @test
     * Returns an error when an invalid id is given as a url parameter
     *
     * @returns void
     */
    public function returnsAnErrorMessageWhenThereIsAnInvalidIdSupplied(): void
    {
        // Hits the get endpoint with an invalid id
        $response = $this->getJson(
            route('employee.show', 'invalidId')
        );

        // From the controller, there is two json fields, error and message.
        $this->assertCount(2, $response->json());
        $response
            ->assertStatus(400)
            ->assertJsonStructure([
                'error',
                'message'
            ])
            ->assertJson([
                'error' => true,
                'message' => 'Invalid Id supplied'
            ]);
    }

    /**
     * @test
     * Returns an error when an id is not found is given as a url parameter
     *
     * @returns void
     */
    public function returnsAnErrorMessageWhenThereAnIdCannotBeFound(): void
    {
        // Hits the get endpoint with an id that does not exist
        $response = $this->getJson(
            route('employee.show', '1029384756')
        );

        // From the controller, there is two json fields, error and message.
        $this->assertCount(2, $response->json());
        $response
            ->assertStatus(404)
            ->assertJsonStructure([
                'error',
                'message'
            ])
            ->assertJson([
                'error' => true,
                'message' => 'No employees found with the id of 1029384756'
            ]);
    }
}
