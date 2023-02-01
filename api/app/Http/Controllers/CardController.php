<?php

namespace App\Http\Controllers;

use App\Http\Resources\CardResource;
use App\Models\Card;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
                    'message' => 'No employee found with this id'
                ],
                Response::HTTP_NOT_FOUND
            );
        }
    }

    public function pay(Request $request, $employeeId): JsonResponse
    {
        $amountToDeduct = $request->input('productTotal');

        $card = Card::where('_fk_employee_id', '=', strval($employeeId))->first();

        if (!$card) {
            return new JsonResponse(
                [
                    "error" => true,
                    "message" => 'Employee does not exist in our records'
                ],
                Response::HTTP_NOT_FOUND
            );
        } elseif ($amountToDeduct < 0.01) {
            return new JsonResponse(
                [
                    'error' => true,
                    'message' => 'No amount to deduct to employee card'
                ],
                Response::HTTP_BAD_REQUEST
            );
        } elseif ($amountToDeduct < $card->credit) {
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

    public function topup($employeeId, Request $request): JsonResponse
    {
        $amountToTopup = $request->input('amount');

        $card = Card::where('_fk_employee_id', '=', strval($employeeId))->first();

        if (!$card) {
            return new JsonResponse(
                [
                    "error" => true,
                    "message" => "Card not found with this id"
                ],
                Response::HTTP_NOT_FOUND
            );
        } elseif (empty($amountToTopup) || $amountToTopup < 0.01) {
            return new JsonResponse(
                [
                    "error" => true,
                    "message" => "No amount to add to employee card"
                ],
                Response::HTTP_BAD_REQUEST
            );
        } else {

            $card->credit = $card->credit += $amountToTopup;
            $card->save();

            return new JsonResponse(
                [
                    "card_id" => $card->id,
                    "employee_id" => $card->_fk_employee_id,
                    "credit" => $card->credit,
                    "message" => "Credit updated successfully"
                ],
                Response::HTTP_OK
            );
        }
    }

    public function updateTimestamp($cardId, Request $request): JsonResponse
    {
        $timestamp = $request->input('last_timestamp');

        $cardToUpdate = Card::where('id', '=', strval($cardId))->first();

        if (empty($timestamp)) {
            return new JsonResponse(
                [
                    'error' => true,
                    'message' => 'No timestamp provided'
                ],
                Response::HTTP_BAD_REQUEST
            );
        } elseif (strtotime($timestamp) > time()) {
            return new JsonResponse(
                [
                    'error' => true,
                    'message' => 'Timestamp cannot be in the future'
                ],
                Response::HTTP_BAD_REQUEST
            );
        } {
            $cardToUpdate->last_scanned = $timestamp;
            $cardToUpdate->is_logged_in = !$cardToUpdate->is_logged_in;
            $cardToUpdate->save();

            return new JsonResponse(
                [
                    "last_timestamp" => $cardToUpdate->last_scanned
                ],
                Response::HTTP_OK
            );
        }
    }

    public function showTimestamp($cardId): JsonResponse
    {
        $card = Card::find($cardId);

        if ($card === null) {
            return new JsonResponse(
                [
                    "error" => true,
                    "message" => "This card id does not exist"
                ],
                Response::HTTP_NOT_FOUND
            );
        } else {
            return new JsonResponse(
                [
                    "last_scanned" => $card->last_scanned,
                    "last_60_mins" => $card->is_logged_in
                ],
                Response::HTTP_OK
            );
        }
    }
}
