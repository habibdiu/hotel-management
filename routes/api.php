<?php

use App\Http\Controllers\backend\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::match(['get','post'],'login',[ApiController::class,'login']);
// case start 
Route::post('case/add',[ApiController::class,'case_add']);
Route::post('case/edit',[ApiController::class,'case_edit']);
Route::post('case/list',[ApiController::class,'case_list']);
Route::post('case/delete',[ApiController::class,'case_delete']);
// case dates start 
Route::post('case-date/add',[ApiController::class,'case_date_add']);
Route::post('case-date/edit',[ApiController::class,'case_date_edit']);
Route::post('case-date/list',[ApiController::class,'case_date_list']);
Route::post('case-date/delete',[ApiController::class,'case_date_delete']);

//library
Route::post('library/add',[ApiController::class,'library_add']); 
Route::post('library/edit',[ApiController::class,'library_edit']); 
Route::post('library/list',[ApiController::class,'library_list']); 
Route::post('library/delete',[ApiController::class,'library_delete']); 
//racks 
 Route::post('rack/add',[ApiController::class,'rack_add']);
 Route::post('rack/edit',[ApiController::class,'rack_edit']);
 Route::post('rack/list',[ApiController::class,'rack_list']);
 Route::post('rack/delete',[ApiController::class,'rack_delete']);
// rack files
Route::post('rack-files/add',[ApiController::class,'rack_files_add']);
Route::post('rack-files/edit',[ApiController::class,'rack_files_edit']);
Route::post('rack-files/list',[ApiController::class,'rack_files_list']);
Route::post('rack-files/delete',[ApiController::class,'rack_files_delete']);
//profile 
Route::post('profile',[ApiController::class,'profile']);
Route::post('profile/update',[ApiController::class,'profile_update']);

