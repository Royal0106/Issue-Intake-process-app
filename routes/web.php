<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IssueController;



Route::get('/', [IssueController::class, 'index'])->name('index');        // List all issues
Route::get('/create', [IssueController::class, 'create'])->name('create'); // Show create form
Route::post('/', [IssueController::class, 'store'])->name('store');       // Store new issue
Route::get('/{id}', [IssueController::class, 'show'])->name('show');      // View issue
Route::get('/{id}/edit', [IssueController::class, 'edit'])->name('edit'); // Show edit form
Route::put('/{id}', [IssueController::class, 'update'])->name('update');  // Update issue
Route::delete('/{id}', [IssueController::class, 'destroy'])->name('destroy'); // Delete

