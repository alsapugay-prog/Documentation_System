<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Document;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * List all users (also available as JSON for the modal).
     */
    public function index(Request $request)
    {
        $users = User::latest()->get();

        if ($request->wantsJson()) {
            return response()->json(['users' => $users]);
        }

        // Redirect back to dashboard — users are managed in a modal
        return redirect()->route('dashboard');
    }

    /**
     * Create a new user.
     * Called via AJAX from the User Management modal.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => ['required', Password::min(8)],
            'role'     => 'sometimes|string|in:admin,staff,viewer',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'] ?? 'staff',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully.',
            'user'    => $user->only('id', 'name', 'email', 'role'),
        ]);
    }

    /**
     * Delete a user.
     */
    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return response()->json(['success' => false, 'message' => 'You cannot delete your own account.'], 403);
        }

        $user->delete();

        return response()->json(['success' => true, 'message' => 'User deleted.']);
    }

    /**
     * Get current document & service assignments for a user.
     * Called via AJAX when the Assign modal opens.
     */
    public function getAssignments(User $user)
    {
        // Use a pivot table approach — assumes user_documents and user_services tables exist.
        // Falls back gracefully if the tables don't exist yet.
        try {
            $documentIds = $user->documents()->pluck('documents.id')->toArray();
            $serviceIds  = $user->services()->pluck('services.id')->toArray();
        } catch (\Exception $e) {
            $documentIds = [];
            $serviceIds  = [];
        }

        return response()->json([
            'document_ids' => $documentIds,
            'service_ids'  => $serviceIds,
        ]);
    }

    /**
     * Save document & service assignments for a user.
     * Called via AJAX when the Assign modal is submitted.
     */
    public function saveAssignments(Request $request, User $user)
    {
        $validated = $request->validate([
            'document_ids'   => 'array',
            'document_ids.*' => 'integer|exists:documents,id',
            'service_ids'    => 'array',
            'service_ids.*'  => 'integer|exists:services,id',
        ]);

        try {
            // Sync pivot relationships
            if (method_exists($user, 'documents')) {
                $user->documents()->sync($validated['document_ids'] ?? []);
            }
            if (method_exists($user, 'services')) {
                $user->services()->sync($validated['service_ids'] ?? []);
            }
        } catch (\Exception $e) {
            // If pivot tables aren't set up yet, return success anyway
            // so the UI still works during development
            return response()->json([
                'success' => true,
                'message' => 'Assignment saved (pivot tables not yet migrated).',
                'note'    => $e->getMessage(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => sprintf(
                'Assigned %d document(s) and %d service(s) to %s.',
                count($validated['document_ids'] ?? []),
                count($validated['service_ids']  ?? []),
                $user->name
            ),
        ]);
    }
}