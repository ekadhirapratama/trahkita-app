<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:users,name',
        ], [
            'name.unique' => 'Username tersebut sudah terdaftar.'
        ]);

        $randomPassword = Str::random(12);

        $user = User::create([
            'name' => $request->name,
            'password' => Hash::make($randomPassword),
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'created new admin account',
            'target_type' => 'user',
            'target_id' => $user->id
        ]);

        return redirect()->route('admin.users.index')->with('new_admin', [
            'username' => $user->name,
            'password' => $randomPassword
        ]);
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')->withErrors(['error' => 'Anda tidak bisa menghapus akun Anda sendiri.']);
        }
        
        $userId = $user->id;
        $user->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'deleted admin account',
            'target_type' => 'user',
            'target_id' => $userId
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Akun admin berhasil dihapus.');
    }
}
