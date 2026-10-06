<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SwapSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SwapSessionController extends Controller
{
    /**
     * Display a listing of swap sessions.
     */
    public function index(Request $request): View
    {
        $query = SwapSession::with(['requester.profile', 'partner.profile', 'requesterSkill', 'partnerSkill']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $search = (string) $request->input('search');
            $query->where(function ($q) use ($search): void {
                $q->whereHas('requester', fn ($uq) => $uq->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('partner', fn ($uq) => $uq->where('name', 'like', "%{$search}%"));
            });
        }

        $sessions = $query->latest('id')->paginate(10)->withQueryString();
        $totalSessions = SwapSession::count();
        $completedSessions = SwapSession::where('status', 'completed')->count();
        $ongoingSessions = SwapSession::where('status', 'ongoing')->count();
        $pendingSessions = SwapSession::where('status', 'pending')->count();

        return view('admin.swap-sessions.index', compact(
            'sessions',
            'totalSessions',
            'completedSessions',
            'ongoingSessions',
            'pendingSessions'
        ));
    }

    /**
     * Display the specified swap session details.
     */
    public function show(SwapSession $swapSession): View
    {
        $swapSession->load([
            'requester.profile',
            'partner.profile',
            'requesterSkill.category',
            'partnerSkill.category',
            'videoCalls',
            'reviews.reviewer',
            'reports.reporter',
        ]);

        return view('admin.swap-sessions.show', compact('swapSession'));
    }

    /**
     * Update the status of the specified swap session.
     */
    public function updateStatus(Request $request, SwapSession $swapSession): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,accepted,ongoing,completed,cancelled'],
        ]);

        $updates = ['status' => $validated['status']];

        if ($validated['status'] === 'ongoing' && ! $swapSession->started_at) {
            $updates['started_at'] = now();
        }

        if ($validated['status'] === 'completed' && ! $swapSession->completed_at) {
            $updates['completed_at'] = now();
        }

        $swapSession->update($updates);

        return back()->with('success', "Session #{$swapSession->id} status updated to '{$validated['status']}'.");
    }

    /**
     * Remove the specified swap session.
     */
    public function destroy(SwapSession $swapSession): RedirectResponse
    {
        $id = $swapSession->id;
        $swapSession->delete();

        return redirect()->route('admin.swap-sessions.index')
            ->with('success', "Swap Session #{$id} deleted successfully.");
    }
}
