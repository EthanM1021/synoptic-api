<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class EmployeeController extends Controller
{
    public function show(): JsonResponse
    {
        // Returns a json object with the key of employees and sets the status code to 200
        return new JsonResponse(
            [
                "employees" => Employee::all()
            ],
            Response::HTTP_OK
        );
    }
}
