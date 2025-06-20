<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function isAdmin()
    {
        $user = auth()->user();
        if (!$user) return false;
        $group = is_string($user->group) ? strtolower(trim($user->group)) : $user->group;
        return $group === 'admin';
    }

    public function index()
    {
        if (!$this->isAdmin()) abort(403);
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        if (!$this->isAdmin()) abort(403);
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        if (!$this->isAdmin()) abort(403);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'group' => 'required|in:admin,moderator,user',
            'phone' => 'nullable|string|max:20',
        ]);
        $data['password'] = Hash::make($data['password']);
        User::create($data);
        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        if (!$this->isAdmin()) abort(403);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if (!$this->isAdmin()) abort(403);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'group' => 'required|in:admin,moderator,user',
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
        ]);
        if ($data['password']) {
            $user->password = Hash::make($data['password']);
        }
        unset($data['password']);
        $user->update($data);
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if (!$this->isAdmin()) abort(403);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
