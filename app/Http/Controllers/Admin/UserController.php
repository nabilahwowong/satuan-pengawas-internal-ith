<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WorkUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function index()
    {
        $users = User::with('workUnit')->latest()->paginate(10);
        $units = WorkUnit::where('is_active', true)->get();
        return view('admin.users.index', compact('users', 'units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in(['admin', 'auditor', 'auditee', 'approver'])],
            'work_unit_id' => ['nullable', 'exists:work_units,id'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'Akun pengguna berhasil dibuat.');
    }

    public function destroy(User $user)
{
    if ($user->id === Auth::id()) {
        return back()->withErrors(['error' => 'Anda tidak dapat menghapus akun Anda sendiri.']);
    }

    $user->delete();
    return redirect()->route('admin.users.index')->with('success', 'Akun pengguna berhasil dihapus.');
}
}
