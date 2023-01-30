<?php

namespace Tests\Feature;

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

        Employee::factory()->create();

        $response = $this->getJson(route('employee.show'));

        $this->assertCount(1, $response->json());
    }
}
