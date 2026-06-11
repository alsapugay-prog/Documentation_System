<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use App\Models\UserProfile;

class ProfileController extends Controller
{
    /**
     * Display the authenticated user's settings/profile page.
     * Automatically seeds the page with name + email from their account.
     */
    public function show(): View
    {
        $user    = auth()->user();

        // Load existing profile OR return an empty model so Blade never errors
        $profile = $user->profile ?? new UserProfile();

        return view('settings.profile', compact('user', 'profile'));
    }

    /**
     * Update account-level fields (name, email) and
     * extended profile fields in a single form submission.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        // ---------------------------------------------------
        // Validation
        // ---------------------------------------------------
        $validated = $request->validate([
            // Core account fields
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],

            // Optional password change (only if user fills it in)
            'current_password'  => ['nullable', 'string'],
            'password'          => ['nullable', 'string', 'min:8', 'confirmed'],

            // Extended profile fields
            'contact_number' => ['nullable', 'string', 'max:30'],
            'address'        => ['nullable', 'string', 'max:500'],
            'bio'            => ['nullable', 'string', 'max:1000'],
            'position'       => ['nullable', 'string', 'max:100'],
            'department'     => ['nullable', 'string', 'max:100'],
            'birthdate'      => ['nullable', 'date', 'before:today'],
            'gender'         => ['nullable', 'string', Rule::in(['male', 'female', 'non-binary', 'prefer_not_to_say'])],
            'facebook'       => ['nullable', 'url', 'max:255'],
            'linkedin'       => ['nullable', 'url', 'max:255'],

            // Avatar upload
            'avatar'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // ---------------------------------------------------
        // Password change (optional)
        // ---------------------------------------------------
        if ($request->filled('password')) {
            if (! Hash::check($request->current_password, $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'Your current password is incorrect.'])
                    ->withInput();
            }
            $user->password = Hash::make($validated['password']);
        }

        // ---------------------------------------------------
        // Update core user fields
        // ---------------------------------------------------
        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

        // ---------------------------------------------------
        // Avatar upload
        // ---------------------------------------------------
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            // Delete old avatar to avoid orphaned files
            if ($user->profile?->avatar) {
                Storage::disk('public')->delete($user->profile->avatar);
            }
            $avatarPath = $request->file('avatar')
                ->store('avatars/' . $user->id, 'public');
        }

        // ---------------------------------------------------
        // Upsert the profile record (create if none, update if exists)
        // ---------------------------------------------------
        $profileData = [
            'contact_number' => $validated['contact_number'] ?? null,
            'address'        => $validated['address']        ?? null,
            'bio'            => $validated['bio']            ?? null,
            'position'       => $validated['position']       ?? null,
            'department'     => $validated['department']     ?? null,
            'birthdate'      => $validated['birthdate']      ?? null,
            'gender'         => $validated['gender']         ?? null,
            'facebook'       => $validated['facebook']       ?? null,
            'linkedin'       => $validated['linkedin']       ?? null,
        ];

        // Only overwrite avatar if a new one was uploaded
        if ($avatarPath) {
            $profileData['avatar'] = $avatarPath;
        }

        // updateOrCreate ensures each user has exactly one profile row
        UserProfile::updateOrCreate(
            ['user_id' => $user->id],   // WHERE clause
            $profileData                // values to set
        );

        return back()->with('status', 'Profile updated successfully!');
    }
}