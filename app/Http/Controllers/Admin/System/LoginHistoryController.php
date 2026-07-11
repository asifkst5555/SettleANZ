<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\LoginHistory;
use Illuminate\View\View;

class LoginHistoryController extends Controller
{
    public function index(): View
    {
        $history = LoginHistory::with('user')
            ->latest()
            ->paginate(50);

        return view('admin.system.login-history.index', compact('history'));
    }
}
