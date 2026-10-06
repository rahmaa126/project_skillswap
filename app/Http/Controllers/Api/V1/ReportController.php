<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ReportResource;
use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ReportController extends Controller
{
    /**
     * Display a listing of reports.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Report::with(['reporter.profile', 'reported.profile', 'handler']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('reporter_id')) {
            $query->where('reporter_id', $request->input('reporter_id'));
        }

        if ($request->filled('reported_id')) {
            $query->where('reported_id', $request->input('reported_id'));
        }

        $reports = $query->latest('id')->paginate((int) $request->input('per_page', 15));

        return ReportResource::collection($reports);
    }

    /**
     * Store a newly created report.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'reporter_id' => ['required', 'exists:users,id'],
            'reported_id' => ['required', 'exists:users,id', 'different:reporter_id'],
            'session_id' => ['nullable', 'exists:swap_sessions,id'],
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $report = Report::create([
            ...$validated,
            'status' => 'pending',
            'created_at' => now(),
        ]);

        return (new ReportResource($report->load(['reporter', 'reported'])))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified report.
     */
    public function show(Report $report): ReportResource
    {
        $report->load(['reporter.profile', 'reported.profile', 'session', 'handler']);

        return new ReportResource($report);
    }

    /**
     * Update the specified report status.
     */
    public function update(Request $request, Report $report): ReportResource
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,investigating,resolved,dismissed'],
            'handled_by' => ['nullable', 'exists:users,id'],
        ]);

        $report->update($validated);

        return new ReportResource($report->fresh(['reporter', 'reported', 'handler']));
    }
}
