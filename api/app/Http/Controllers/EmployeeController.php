<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class EmployeeController extends Controller
{
    public function show($id): JsonResponse
    {
        $integerId = intval($id);
        $employee = Employee::find($integerId);

        if ($employee) {
            // Returns a json object with the key of employees and sets the status code to 200
            return new JsonResponse(
                [
                    "employee" => Employee::find($integerId)
                ],
                Response::HTTP_OK
            );
        } elseif ($integerId) {
            // If no employee is found but the id is an integer, this statement will always be fulfilled
            // If no employee is found, returns an error which explains why
            return new JsonResponse(
                [
                    'error' => true,
                    'message' => 'No employees found with the id of ' . $integerId
                ],
                Response::HTTP_NOT_FOUND
            );
        } else {
            // If id is invalid, error explaining why is shown
            return new JsonResponse(
                [
                    'error' => true,
                    'message' => 'Invalid Id supplied'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
