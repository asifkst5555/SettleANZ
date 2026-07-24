<?php

use App\Http\Controllers\Api\ChatHistoryController;
use App\Http\Controllers\Api\ChatMessageController;
use App\Http\Controllers\Api\ChatSessionController;
use Illuminate\Support\Facades\Route;

Route::post('/chat/session', [ChatSessionController::class, 'store'])->middleware(['throttle:public_forms']);
Route::post('/chat/reset', [ChatSessionController::class, 'reset'])->middleware(['throttle:public_forms']);
Route::post('/chat/message/{conversation}', [ChatMessageController::class, 'store'])->middleware('throttle:api_chat');
Route::get('/chat/history/{conversation}', [ChatHistoryController::class, 'show'])->middleware('throttle:verification_refresh');
