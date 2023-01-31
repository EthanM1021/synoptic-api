<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CardController extends Controller
{
    public function show($id): JsonResponse
    {
        // Find employee with id that is passed as a parameter
        $employee = Employee::find(intval($id));

        return new JsonResponse(
            [
                'employee' => [
                    'pin' => $employee->pin
                ]
            ],
            Response::HTTP_OK
        );
    }
}
