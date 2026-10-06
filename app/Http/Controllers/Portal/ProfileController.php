<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form.
     */
    public function edit(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $user->load('profile');

        return view('portal.profile.edit', compact('user'));
    }

    /**
     * Update the user profile.
     */
    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:150', Rule::unique('users')->ignore($user->id)],
            'city' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        DB::transaction(function () use ($user, $validated): void {
            $userUpdates = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];

            if (! empty($validated['password'])) {
                $userUpdates['password_hash'] = Hash::make($validated['password']);
            }

            $user->update($userUpdates);

            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'city' => $validated['city'] ?? null,
                    'phone' => $validated['phone'] ?? null,
                    'bio' => $validated['bio'] ?? null,
                ]
            );
        });

        return back()->with('success', 'Profil Anda berhasil diperbarui!');
    }
}
