<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = ActivityLog::with('user')
            ->when($request->module, fn($q) => $q->where('module', $request->module))
            ->latest()
            ->paginate(20);

        return view('admin.logs.index', compact('logs'));
    }
}
