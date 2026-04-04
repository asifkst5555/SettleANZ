<?php

use App\Http\Controllers\Api\ChatHistoryController;
use App\Http\Controllers\Api\ChatMessageController;
use App\Http\Controllers\Api\ChatSessionController;
use Illuminate\Support\Facades\Route;

Route::post('/chat/session', [ChatSessionController::class, 'store']);
Route::post('/chat/reset', [ChatSessionController::class, 'reset']);
Route::post('/chat/message/{conversation}', [ChatMessageController::class, 'store']);
Route::get('/chat/history/{conversation}', [ChatHistoryController::class, 'show']);
