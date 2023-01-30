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
    public function getsEmployee(): void
    {
        // Creates an employee record from the EmployeeFactory with the faker library
        $factory = Employee::factory()->create();
        // Casts the attributes json object to an array
        $factoryArray = $factory->attributesToArray();

        // Gets the endpoint with the id of the employee record above
        $response = $this->getJson(
            route('employee.show', $factoryArray["id"])
        );

        // Asserts the amount of employees returned is 1
        $this->assertCount(1, $response->json());
    }
}
