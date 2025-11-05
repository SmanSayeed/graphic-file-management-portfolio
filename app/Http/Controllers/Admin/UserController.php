<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = User::latest();
        
        // Hide root_admin users from non-root-admin users
        if (!Auth::user()->isRootAdmin()) {
            $query->where('role', '!=', 'root_admin');
        }
        
        $users = $query->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Determine allowed roles based on current user
        $allowedRoles = ['admin', 'user'];
        if (Auth::user()->isRootAdmin()) {
            $allowedRoles[] = 'root_admin';
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => ['required', 'in:' . implode(',', $allowedRoles)],
            'is_active' => 'boolean',
        ]);

        // Prevent non-root-admin users from creating root_admin
        if ($validated['role'] === 'root_admin' && !Auth::user()->isRootAdmin()) {
            return back()->withErrors(['role' => 'You do not have permission to create root admin users.'])
                ->withInput();
        }

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active') ? true : false;

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Prevent non-root-admin users from viewing root_admin users
        if ($user->isRootAdmin() && !Auth::user()->isRootAdmin()) {
            abort(403, 'Access denied');
        }
        
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Prevent non-root-admin users from editing root_admin users
        if ($user->isRootAdmin() && !Auth::user()->isRootAdmin()) {
            abort(403, 'Access denied');
        }
        
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Prevent non-root-admin users from editing root_admin users
        if ($user->isRootAdmin() && !Auth::user()->isRootAdmin()) {
            abort(403, 'Access denied');
        }
        
        // Determine allowed roles based on current user
        $allowedRoles = ['admin', 'user'];
        if (Auth::user()->isRootAdmin()) {
            $allowedRoles[] = 'root_admin';
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'role' => ['required', 'in:' . implode(',', $allowedRoles)],
            'is_active' => 'boolean',
        ]);

        // Prevent non-root-admin users from changing user role to root_admin
        if ($validated['role'] === 'root_admin' && !Auth::user()->isRootAdmin()) {
            return back()->withErrors(['role' => 'You do not have permission to assign root admin role.'])
                ->withInput();
        }

        // Prevent changing root_admin role to something else (only root_admin can do this)
        if ($user->isRootAdmin() && $validated['role'] !== 'root_admin' && !Auth::user()->isRootAdmin()) {
            return back()->withErrors(['role' => 'You cannot change the role of a root admin user.'])
                ->withInput();
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting the current user
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account');
        }

        // Prevent non-root-admin users from deleting root_admin users
        if ($user->isRootAdmin() && !Auth::user()->isRootAdmin()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You do not have permission to delete root admin users');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully');
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus(User $user)
    {
        if ($user->id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot deactivate your own account'
            ], 403);
        }

        // Prevent non-root-admin users from toggling root_admin users
        if ($user->isRootAdmin() && !Auth::user()->isRootAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to change status of root admin users'
            ], 403);
        }

        $user->update(['is_active' => !$user->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $user->is_active
        ]);
    }
}
