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

Route::get('/employees/{id}', [EmployeeController::class, 'show'])->name('employee.show');

Route::post('/employees', [EmployeeController::class, 'insert'])->name('employee.insert');


Route::get('/employee/pin/{id}', [CardController::class, 'show'])->name('pin.show');
