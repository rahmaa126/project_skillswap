<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AiLogController extends Controller
{
    /**
     * Display a listing of AI engine logs.
     */
    public function index(Request $request): View
    {
        $query = AiLog::with(['user.profile', 'skill']);

        if ($request->filled('feature')) {
            $query->where('feature', $request->input('feature'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $logs = $query->latest('id')->paginate(15)->withQueryString();
        $totalLogs = AiLog::count();
        $successLogs = AiLog::where('status', 'success')->count();
        $failedLogs = AiLog::where('status', 'failed')->count();
        $totalTokens = (int) AiLog::sum('prompt_tokens') + (int) AiLog::sum('completion_tokens');

        return view('admin.ai-logs.index', compact('logs', 'totalLogs', 'successLogs', 'failedLogs', 'totalTokens'));
    }
}
