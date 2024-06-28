<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\EmployeeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/sendPusher', [EmployeeController::class,'testing']);
Route::get('/check', [EmployeeController::class,'check_temp']);
Route::post('login',[UserAuthController::class,'login']);

Route::get('/fired', function () {
	        $activity = [
            'type' => 'user_created',
            'user_id' => 1,
            'message' => 'A new user has been created.',
        ];

        broadcast(new App\Events\ActivityPerformed($activity));
    // event(new App\Events\ActivityPerformed($activity));
    dd('fired');
});

Route::get('/try-websocket', function() {
    \App\Events\SendMessageToClientEvent::dispatch();
    return response('try-websocket');
});