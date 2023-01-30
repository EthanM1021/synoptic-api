<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class EmployeeController extends Controller
{
    public function show($id): JsonResponse
    {
        $employee = Employee::find($id);

        if ($employee) {
            // Returns a json object with the key of employees and sets the status code to 200
            return new JsonResponse(
                [
                    "employee" => Employee::find($id)
                ],
                Response::HTTP_OK
            );
        } else {
            return new JsonResponse(
                [
                    'error' => true,
                    'message' => 'No employees found with the id of ' . $id
                ],
                Response::HTTP_NOT_FOUND
            );
        }
    }
}
