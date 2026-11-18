<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display list of users with search and role filters.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', User::class);

        $search = $request->query('search');
        $roleId = $request->query('role_id');
        $status = $request->query('status');

        $query = User::query()
            ->with('role')
            ->orderBy('name');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($roleId) {
            $query->where('role_id', $roleId);
        }

        if ($status !== null && $status !== '') {
            $query->where('is_active', $status === 'active' ? 1 : 0);
        }

        $users = $query->paginate(15)->withQueryString();
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles', 'search', 'roleId', 'status'));
    }

    /**
     * Show form to edit user role and active state.
     */
    public function edit(User $user): View
    {
        Gate::authorize('update', $user);

        $roles = Role::all();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update user role.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('update', $user);

        $validated = $request->validate([
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        // Prevent admin from removing their own admin role
        if ($user->id === Auth::id() && $user->isAdmin()) {
            $adminRole = Role::where('name', 'admin')->first();
            if ((int) $validated['role_id'] !== $adminRole->id) {
                return redirect()->route('admin.users.index')
                    ->with('error', __('You cannot remove your own admin role.'));
            }
        }

        $user->role_id = $validated['role_id'];
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('status', __('User updated successfully.'));
    }

    /**
     * Toggle active/inactive state of a user.
     */
    public function toggleActive(User $user): RedirectResponse
    {
        Gate::authorize('toggleActive', $user);

        $user->is_active = ! $user->is_active;
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('status', __('User status toggled successfully.'));
    }
}
