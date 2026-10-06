<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Display a listing of user reports.
     */
    public function index(Request $request): View
    {
        $query = Report::with(['reporter.profile', 'reported.profile', 'handler']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $reports = $query->latest('id')->paginate(10)->withQueryString();
        $admins = User::where('role', 'admin')->get();
        $totalReports = Report::count();
        $pendingReports = Report::where('status', 'pending')->count();
        $resolvedReports = Report::where('status', 'resolved')->count();

        return view('admin.reports.index', compact('reports', 'admins', 'totalReports', 'pendingReports', 'resolvedReports'));
    }

    /**
     * Update report resolution status.
     */
    public function updateStatus(Request $request, Report $report): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,investigating,resolved,dismissed'],
            'handled_by' => ['nullable', 'exists:users,id'],
        ]);

        $report->update($validated);

        return back()->with('success', "Report #{$report->id} status updated to '{$validated['status']}'.");
    }

    /**
     * Remove the specified report.
     */
    public function destroy(Report $report): RedirectResponse
    {
        $id = $report->id;
        $report->delete();

        return redirect()->route('admin.reports.index')
            ->with('success', "Report #{$id} deleted successfully.");
    }
}
