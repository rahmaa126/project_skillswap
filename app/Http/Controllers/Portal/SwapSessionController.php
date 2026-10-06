<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\SwapSession;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SwapSessionController extends Controller
{
    /**
     * Display a listing of user's swap sessions.
     */
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $incomingRequests = SwapSession::with(['requester.profile', 'requesterSkill', 'partnerSkill'])
            ->where('partner_id', $user->id)
            ->where('status', 'pending')
            ->latest('id')
            ->get();

        $outgoingRequests = SwapSession::with(['partner.profile', 'requesterSkill', 'partnerSkill'])
            ->where('requester_id', $user->id)
            ->where('status', 'pending')
            ->latest('id')
            ->get();

        $activeSessions = SwapSession::with(['requester.profile', 'partner.profile', 'requesterSkill', 'partnerSkill'])
            ->where(function ($q) use ($user): void {
                $q->where('requester_id', $user->id)
                    ->orWhere('partner_id', $user->id);
            })
            ->whereIn('status', ['accepted', 'ongoing'])
            ->latest('id')
            ->get();

        $pastSessions = SwapSession::with(['requester.profile', 'partner.profile', 'requesterSkill', 'partnerSkill', 'reviews'])
            ->where(function ($q) use ($user): void {
                $q->where('requester_id', $user->id)
                    ->orWhere('partner_id', $user->id);
            })
            ->whereIn('status', ['completed', 'cancelled'])
            ->latest('id')
            ->paginate(10);

        return view('portal.swaps.index', compact('user', 'incomingRequests', 'outgoingRequests', 'activeSessions', 'pastSessions'));
    }

    /**
     * Store a new swap session request.
     */
    public function store(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'partner_id' => ['required', 'exists:users,id', 'different:requester_id'],
            'requester_skill_id' => ['required', 'exists:skills,id'],
            'partner_skill_id' => ['required', 'exists:skills,id'],
            'scheduled_at' => ['nullable', 'date'],
        ]);

        SwapSession::create([
            'requester_id' => $user->id,
            'partner_id' => $validated['partner_id'],
            'requester_skill_id' => $validated['requester_skill_id'],
            'partner_skill_id' => $validated['partner_skill_id'],
            'scheduled_at' => $validated['scheduled_at'] ?? now()->addDays(2),
            'status' => 'pending',
            'created_at' => now(),
        ]);

        $partner = User::find($validated['partner_id']);

        return redirect()->route('portal.swaps.index')
            ->with('success', "Permintaan sesi swap berhasil dikirimkan ke {$partner->name}.");
    }

    /**
     * Respond to an incoming swap session request (accept/reject).
     */
    public function respond(Request $request, SwapSession $swapSession): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if ($swapSession->partner_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'action' => ['required', 'in:accept,reject'],
        ]);

        if ($validated['action'] === 'accept') {
            $swapSession->update([
                'status' => 'accepted',
            ]);
            $msg = 'Permintaan sesi swap berhasil diterima!';
        } else {
            $swapSession->update([
                'status' => 'cancelled',
            ]);
            $msg = 'Permintaan sesi swap telah ditolak.';
        }

        return back()->with('success', $msg);
    }

    /**
     * Mark swap session as completed.
     */
    public function complete(SwapSession $swapSession): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if ($swapSession->requester_id !== $user->id && $swapSession->partner_id !== $user->id) {
            abort(403);
        }

        $swapSession->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Increment total swaps for both profiles
        $swapSession->requester->profile()->increment('total_swaps');
        $swapSession->partner->profile()->increment('total_swaps');

        return back()->with('success', 'Sesi pertukaran skill berhasil diselesaikan! Silakan beri ulasan untuk rekan Anda.');
    }
}
