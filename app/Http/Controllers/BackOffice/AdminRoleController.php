<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminRoleController extends Controller
{
    /**
     * Assign a role to a user.
     */
    public function assignRole(Request $request, $userId)
    {
        // Validate the incoming request
        $request->validate([
            'role' => 'required|string',
        ]);

        // Find the user
        $user = User::findOrFail($userId);

        // Assign the role
        $user->assignRole($request->role);

        return response()->json([
            'message' => 'Role assigned successfully.',
            'user'    => $user->load('roles'), // Eager load roles to confirm
        ]);
    }

    /**
     * Remove a specific role from a user.
     */
    public function removeRole(Request $request, $userId)
    {
        // Validate the incoming request
        $request->validate([
            'role' => 'required|string',
        ]);

        $user = User::findOrFail($userId);

        // Remove the specified role
        $user->removeRole($request->role);

        return response()->json([
            'message' => 'Role removed successfully.',
            'user'    => $user->load('roles'),
        ]);
    }

    /**
     * Sync roles (replace all existing roles with a new set).
     */
    public function syncRoles(Request $request, $userId)
    {
        // Validate the incoming request
        $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'string',
        ]);

        $user = User::findOrFail($userId);

        // Sync roles: removes all old roles and assigns the new ones
        $user->syncRoles($request->roles);

        return response()->json([
            'message' => 'Roles synced successfully.',
            'user'    => $user->load('roles'),
        ]);
    }
}
