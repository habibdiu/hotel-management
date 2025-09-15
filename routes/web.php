<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\backend\ApiController;
use App\Http\Controllers\Backend\RoomController;
use App\Http\Controllers\backend\MessageController;
use App\Http\Controllers\backend\ProfileController;
use App\Http\Controllers\backend\AdvocateController;
use App\Http\Controllers\backend\CategoryController;
use App\Http\Controllers\backend\RoomTypeController;
use App\Http\Controllers\backend\DashboardController;
use App\Http\Controllers\backend\SubcategoryController;
use App\Http\Middleware\backendAuthenticationMiddleware;
use App\Http\Controllers\backend\AuthenticationController;

Route::redirect('/', 'login');
// backend
Route::match(['get', 'post'], 'login', [AuthenticationController::class, 'login'])->name('login');
// route prefix
Route::prefix('admin')->group(function () {
    // route name prefix
    Route::name('admin.')->group(function () {
        //middleware
        Route::middleware(backendAuthenticationMiddleware::class)->group(function () {
            Route::get('logout', [AuthenticationController::class, 'logout'])->name('logout');
            //profile
            Route::get('profile', [ProfileController::class, 'profile'])->name('profile');
            Route::post('profile-info/update', [ProfileController::class, 'profile_info_update'])->name('profile.info.update');
            Route::post('profile-password/update', [ProfileController::class, 'profile_password_update'])->name('profile.password.update');
            //dashboard
            Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');


            //advocates
            Route::match(['get','post'],'advocate/add',[AdvocateController::class,'advocate_add'])->name('advocate.add');
            Route::match(['get','post'],'advocate/edit/{id}',[AdvocateController::class,'advocate_edit'])->name('advocate.edit');
            Route::get('advocate/list',[AdvocateController::class,'advocate_list'])->name('advocate.list');
            Route::get('advocate/delete/{id}',[AdvocateController::class,'advocate_delete'])->name('advocate.delete');


            //Message
            Route::match(['get','post'],'message/add',[MessageController::class, 'message_add'])->name('message.add');
            Route::match(['get','post'],'message/edit/{id}',[MessageController::class, 'message_edit'])->name('message.edit');
            Route::get('message/list',[MessageController::class, 'message_list'])->name('message.list');
            Route::get('message/delete/{id}',[MessageController::class, 'message_delete'])->name('message.delete');

            //Room Type
            Route::match(['get','post'],'room/type',[RoomTypeController::class, 'room_type'])->name('room.type');
            Route::match(['get','post'],'room/type/update/{id}',[RoomTypeController::class, 'room_type_update'])->name('room.type.update');
            Route::delete('room/type/{id}',[RoomTypeController::class, 'room_type_delete'])->name('room.type.delete');
            

            //Room Category
            Route::match(['get','post'],'room/category',[CategoryController::class, 'room_category'])->name('room.category');
            Route::match(['get','post'],'room/category/update/{id}',[CategoryController::class, 'room_category_update'])->name('room.category.update');
            Route::delete('room/category/{id}',[CategoryController::class, 'room_category_delete'])->name('room.category.delete');

            //Room Subcategory
            Route::match(['get','post'],'room/subcategory',[SubcategoryController::class, 'room_subcategory'])->name('room.subcategory');
            Route::match(['get','post'],'room/subcategory/update/{id}',[SubcategoryController::class, 'room_subcategory_update'])->name('room.subcategory.update');
            Route::delete('room/subcategory/{id}',[SubcategoryController::class, 'room_subcategory_delete'])->name('room.subcategory.delete');


            //Room Manage
            Route::match(['get','post'],'room/add',[RoomController::class, 'room_add'])->name('room.add');
            Route::match(['get','post'],'room/edit/{id}',[RoomController::class, 'room_edit'])->name('room.edit');
            Route::get('room/list',[RoomController::class, 'room_list'])->name('room.list');
            Route::get('room/delete/{id}',[RoomController::class, 'room_delete'])->name('room.delete');

           
        });
    });
});
