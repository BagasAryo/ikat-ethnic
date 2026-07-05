<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AdminLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminAccountController extends Controller
{
    public function index()
    {
        $admins = User::whereIn('role', ['admin', 'superadmin'])->get();
        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        AdminLog::create([
            'user_id' => auth()->id(),
            'action' => 'CREATE_ADMIN',
            'description' => "Menambahkan admin baru: {$admin->name} ({$admin->email})",
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil ditambahkan.');
    }

    public function edit(User $admin)
    {
        if (!in_array($admin->role, ['admin', 'superadmin'])) {
            abort(404);
        }

        return view('admin.admins.edit', compact('admin'));
    }

    public function update(Request $request, User $admin)
    {
        if (!in_array($admin->role, ['admin', 'superadmin'])) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($admin->id)],
            'password' => 'nullable|string|min:8',
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        AdminLog::create([
            'user_id' => auth()->id(),
            'action' => 'UPDATE_ADMIN',
            'description' => "Memperbarui data admin: {$admin->name} ({$admin->email})",
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'Data admin berhasil diperbarui.');
    }

    public function destroy(User $admin)
    {
        if (!in_array($admin->role, ['admin', 'superadmin'])) {
            abort(404);
        }

        if ($admin->id === auth()->id()) {
            return redirect()->route('admin.admins.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $name = $admin->name;
        $email = $admin->email;
        
        $admin->delete();

        AdminLog::create([
            'user_id' => auth()->id(),
            'action' => 'DELETE_ADMIN',
            'description' => "Menghapus admin: {$name} ({$email})",
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil dihapus.');
    }
}
