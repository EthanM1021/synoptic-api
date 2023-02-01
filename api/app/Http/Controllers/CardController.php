<?php

namespace App\Http\Controllers;

use App\Http\Resources\CardResource;
use App\Models\Card;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Psy\Util\Json;

class CardController extends Controller
{
    public function show($id): JsonResponse
    {
        $employee = Employee::find($id);

        if ($employee) {
            return new JsonResponse(
                [
                    'pin' => $employee->pin
                ],
                Response::HTTP_OK
            );
        } else {
            return new JsonResponse(
                [
                    'error' => true,
                    'message' => 'No employee found with this is'
                ],
                Response::HTTP_NOT_FOUND
            );
        }
    }

    public function pay(Request $request, $employeeId): JsonResponse
    {
        $amountToDeduct = $request->input('productTotal');

        $card = Card::where('_fk_employee_id', '=', strval($employeeId))->firstOrFail();

        if ($amountToDeduct < $card->credit) {
            $card->credit = $card->credit - $amountToDeduct;
            $card->save();

            return new JsonResponse(
                new CardResource($card),
                Response::HTTP_OK
            );
        } else {
            return new JsonResponse(
                [
                    'error' => true,
                    'message' => 'Employee does not have the funds for this purchase'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
