<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/employee/{id}', [EmployeeController::class, 'show'])->name('employee.show');

Route::post('/employee', [EmployeeController::class, 'insert'])->name('employee.insert');

Route::delete('/employee/{id}', [EmployeeController::class, 'destroy'])->name('employee.destroy');

Route::get('/employee/pin/{id}', [CardController::class, 'show'])->name('pin.show');

Route::patch('/employee/pay/{employee_id}', [CardController::class, 'pay'])->name('card.pay');

Route::patch('/employee/topup/{employee_id}', [CardController::class, 'topup'])->name('card.topup');

Route::patch('/employee/update_timestamp/{card_id}', [CardController::class, 'updateTimestamp'])
    ->name('timestamp.update');

Route::get('/employee/last_timestamp/{card_id}', [CardController::class, 'showTimestamp'])
    ->name('timestamp.show');
