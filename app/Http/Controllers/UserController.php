<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
{
    $query = User::query();

    // Pencarian nama
    if ($search = $request->get('search')) {
        $query->where('name', 'like', '%' . $search . '%');
    }

    // Filter role
    if ($role = $request->get('role')) {
        $query->where('role', $role);
    }

    // Pengurutan berdasarkan nama
    $sort = $request->get('sort', 'asc'); // default asc
    $sort = in_array($sort, ['asc', 'desc']) ? $sort : 'asc';
    $query->orderBy('name', $sort);

    // Paginasi
    $users = $query->select('id', 'name', 'username', 'role', 'created_at')
                   ->paginate(2)
                   ->appends($request->query());

    return view('user.index', compact('users', 'sort'));
}
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:30',
            'username' => 'required|string|max:30|unique:users,username',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:admin,crew',
        ], [
            'name.required'     => 'Nama wajib diisi.',
            'name.max'          => 'Nama maksimal 30 karakter.',
            'username.required' => 'Username wajib diisi.',
            'username.max'      => 'Username maksimal 30 karakter.',
            'username.unique'   => 'Username sudah digunakan.',
            'password.min'      => 'Password minimal 8 karakter.',
            'password.confirmed'=> 'Konfirmasi password tidak cocok.',
            'role.in'           => 'Role tidak valid. Pilih admin atau crew.',
        ]);

        // Password otomatis di-hash oleh model (cast: 'hashed')
        User::create($request->only(['name', 'username', 'password', 'role']));

        return redirect()
            ->route('user.create')
            ->with('success', 'User berhasil didaftarkan!');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        

        $user->delete();

        return redirect()
            ->route('user.create')
            ->with('success', 'User berhasil dihapus.');
    }


    public function showLogin()
{
    return view('auth.login');
}

public function login(Request $request)
{
    $credentials = $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ], [
        'username.required' => 'Username wajib diisi.',
        'password.required' => 'Password wajib diisi.',
    ]);

    if (Auth::attempt($credentials, $request->filled('remember'))) {
        $request->session()->regenerate();

        // Redirect berdasarkan role
        return redirect()->intended(
            Auth::user()->role === 'admin' ? route('dashboard.admin') : route('dashboard.crew')
        );
    }

    throw ValidationException::withMessages([
        'username' => 'Username atau password salah.',
    ]);
}

public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
}
}
