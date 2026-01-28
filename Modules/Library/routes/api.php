<?php

use Illuminate\Support\Facades\Route;
use Modules\Library\App\Http\Controllers\API\LibraryController;
	
	Route::post('/login', [LibraryController::class, 'login']);

	Route::middleware('auth:sanctum')->group(function () {
		Route::post('/logout', [LibraryController::class, 'logout']);
		
		//book crud
		Route::get('books', [LibraryController::class,'index']); //list
		Route::get('books/{id}/edit', [LibraryController::class,'edit']);
		Route::get('books/{id}/view', [LibraryController::class,'show']);
		
		Route::middleware(['auth', 'check_is_admin'])->group(function () {
			Route::post('books/store', [LibraryController::class,'store']);
			Route::post('books/{id}/update', [LibraryController::class,'update']);
			Route::post('books/{id}/destroy', [LibraryController::class,'destroy']);
		});
	
	});
