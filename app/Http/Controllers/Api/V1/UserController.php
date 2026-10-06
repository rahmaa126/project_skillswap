<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\SkillResource;
use App\Http\Resources\V1\UserResource;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = User::with('profile');

        if ($request->filled('search')) {
            $search = (string) $request->input('search');
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        if ($request->has('is_active')) {
            $query->where('is_active', filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN));
        }

        $users = $query->latest('id')->paginate((int) $request->input('per_page', 15));

        return UserResource::collection($users);
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['sometimes', 'in:user,admin'],
            'bio' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user = DB::transaction(function () use ($validated): User {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password_hash' => Hash::make($validated['password']),
                'role' => $validated['role'] ?? 'user',
                'is_active' => true,
            ]);

            Profile::create([
                'user_id' => $user->id,
                'bio' => $validated['bio'] ?? null,
                'city' => $validated['city'] ?? null,
                'phone' => $validated['phone'] ?? null,
            ]);

            return $user->load('profile');
        });

        return (new UserResource($user))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified user.
     */
    public function show(User $user): UserResource
    {
        $user->load([
            'profile',
            'offeredSkills.skill.category',
            'wantedSkills.skill.category',
            'skillScores.skill',
        ]);

        return new UserResource($user);
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user): UserResource
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:100'],
            'email' => ['sometimes', 'required', 'string', 'email', 'max:150', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['sometimes', 'in:user,admin'],
            'is_active' => ['sometimes', 'boolean'],
            'bio' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'avatar_url' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($user, $validated): void {
            $userData = [];
            if (isset($validated['name'])) {
                $userData['name'] = $validated['name'];
            }
            if (isset($validated['email'])) {
                $userData['email'] = $validated['email'];
            }
            if (! empty($validated['password'])) {
                $userData['password_hash'] = Hash::make($validated['password']);
            }
            if (isset($validated['role'])) {
                $userData['role'] = $validated['role'];
            }
            if (isset($validated['is_active'])) {
                $userData['is_active'] = $validated['is_active'];
            }

            if (! empty($userData)) {
                $user->update($userData);
            }

            $profileData = array_filter([
                'bio' => $validated['bio'] ?? null,
                'city' => $validated['city'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'avatar_url' => $validated['avatar_url'] ?? null,
            ], fn ($v) => $v !== null);

            if (! empty($profileData)) {
                $user->profile()->updateOrCreate(['user_id' => $user->id], $profileData);
            }
        });

        return new UserResource($user->fresh(['profile']));
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json([
            'message' => 'User successfully deleted.',
        ]);
    }

    /**
     * Get user's offered and wanted skills.
     */
    public function skills(User $user): JsonResponse
    {
        $offered = $user->offeredSkills()->with('skill.category')->get()->pluck('skill');
        $wanted = $user->wantedSkills()->with('skill.category')->get()->pluck('skill');

        return response()->json([
            'offered' => SkillResource::collection($offered),
            'wanted' => SkillResource::collection($wanted),
        ]);
    }
}
