<?php

namespace Tests\Feature;

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\DataProviderTestSuite;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

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
                    "mobile_number"
                ]
            ])
            ->assertJson([
                "employee" => [
                    "id" => $factoryArray["id"],
                    "first_name" => $factoryArray["first_name"],
                    "last_name" => $factoryArray["last_name"],
                    "email_address" => $factoryArray["email_address"],
                    "mobile_number" => $factoryArray["mobile_number"]
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
                'message' => 'Employee not found with this id'
            ]);
    }

    /**
     * @test
     * Can insert an employee into the database with all fields which are required
     *
     * @return void
     */
    public function canInsertAnEmployeeIntoTheDatabase(): void
    {
        // Store the faker values on a variable so can make sure
        // they're the same in the test request and response
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;
        $emailAddress = $this->faker->email;
        $mobileNumber = $this->faker->phoneNumber;
        $pin = hash('md5', $this->faker->numberBetween(1000, 9999));

        // Employee to insert
        $requestBody = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email_address' => $emailAddress,
            'mobile_number' => $mobileNumber,
            'pin' => $pin
        ];

        // Calls the post endpoint with the body to insert
        $response = $this->postJson(
            route('employee.insert'),
            $requestBody
        );

        $response->assertStatus(201)
            ->assertJsonStructure([
                'first_name',
                'last_name',
                'email_address',
                'mobile_number',
                'pin'
            ])
            ->assertExactJson([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email_address' => $emailAddress,
                'mobile_number' => $mobileNumber,
                'pin' => $pin
            ]);

            $this->assertDatabaseCount('employees', 1);
            $this->assertDatabaseHas('employees', [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email_address' => $emailAddress,
                'mobile_number' => $mobileNumber
            ]);
    }

    /**
     * @test
     * @dataProvider invalidDataPassedToPostRequest
     * If any fields are invalid for some reason, give a message as to why
     *
     * @return void
     */
    public function returnsAnErrorIfOneOrMoreOfTheFieldsAreInvalid($employee): void
    {
        $response = $this->postJson(route('employee.insert'), $employee);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors'
            ]);

        $this->assertDatabaseCount('employees', 0);
    }

    /**
     * @dataProvider
     *
     * data provider is a way to reduce code duplication
     * in this scenario all these conditions should fail
     */
    private function invalidDataPassedToPostRequest(): array
    {
        return [
            'first name is invalid' => [
                'employee' => [
                    'first_name' => 1010,
                    'last_name' => 'test',
                    'email_address' => 'test@test.com',
                    'mobile_number' => '01928 475192',
                    'pin' => 'h28fbcbcn284cu',
                ]
            ],
            'last name is invalid' => [
                'employee' => [
                    'first_name' => 'test',
                    'last_name' => 1010,
                    'email_address' => 'test@test.com',
                    'mobile_number' => '01928 475192',
                    'pin' => 'h28fbcbcn284cu',
                ]
            ],
            'email address is invalid' => [
                'employee' => [
                    'first_name' => 'test',
                    'last_name' => 1010,
                    'email_address' => 'not an email address',
                    'mobile_number' => '01928 475192',
                    'pin' => 'h28fbcbcn284cu',
                ]
            ],
            'mobile number is invalid' => [
                'employee' => [
                    'first_name' => 'test',
                    'last_name' => 1010,
                    'email_address' => 'not an email address',
                    'mobile_number' => true,
                    'pin' => 'h28fbcbcn284cu',
                ]
            ],
            'pin is invalid' => [
                'employee' => [
                    'first_name' => 'test',
                    'last_name' => 1010,
                    'email_address' => 'not an email address',
                    'mobile_number' => true,
                    'pin' => 1029,
                ]
            ],
            'all fields invalid' => [
                'employee' => [
                    'first_name' => '',
                    'last_name' => '',
                    'email_address' => '',
                    'mobile_number' => '',
                    'pin' => 0,
                ]
            ],
        ];
    }

    /**
     * @test
     * Delete the employee if their id exists
     *
     * @return void
     */
    public function deletesExistingEmployeeFromDatabase(): void
    {
        Employee::factory()->count(5)->create();

        $response = $this->deleteJson(route('employee.destroy', range(1, 5)));

        $response->assertStatus(200)
            ->assertJson([
                'error' => false,
                'message' => 'Employee successfully deleted.'
            ]);

        $this->assertDatabaseCount('employees', 4);
    }

    /**
     * @test
     *
     * @return void
     */
    public function giveErrorIfEmployeeIdDoesNotExist(): void
    {
        $response = $this->deleteJson(route('employee.destroy', 1));

        $response->assertStatus(404)
            ->assertJson([
                'error' => true,
                'message' => 'There is no employee with that id'
            ]);

        $this->assertDatabaseCount('employees', 0);
    }
}
