<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(): View
    {
        $logs = ActivityLog::with('user')
            ->latest()
            ->paginate(50);

        return view('admin.system.activity-logs.index', compact('logs'));
    }

    public function show(ActivityLog $activityLog): View
    {
        $activityLog->load('user');
        return view('admin.system.activity-logs.show', compact('activityLog'));
    }
}
