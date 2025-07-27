<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // 1. Index: Tampilkan semua users
    public function index()
    {
        $users = User::all();
        return view('user.index', compact('users'));
    }

    // 2. Create: Form tambah user
    public function create()
    {
        return view('user.create');
    }

    // 3. Store: Proses simpan user baru
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username',
            'password' => 'required|confirmed', // 'confirmed' akan cek password_confirmation
            'role' => 'required',
        ]);

        User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password), // gunakan hash
            'role' => $request->role,
            'status' => 'active',
        ]);

        return redirect()->route('user.index')->with('success', 'User successfully added.');
    }

    // 4. Edit: Form edit user
    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    // 5. Update: Proses update user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => 'required|unique:users,username,' . $user->id,
            'role'     => 'required',
            'status'   => 'required',
            'password' => 'nullable|confirmed', // nullable supaya boleh kosong, confirmed untuk validasi konfirmasi
        ]);

        $user->username = $request->username;
        $user->role     = $request->role;
        $user->status   = $request->status;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('user.index')->with('success', 'User was successfully updated.');
    }

    // 6. Destroy: Hapus user
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('user.index')->with('success', 'User berhasil dihapus.');
    }
}
