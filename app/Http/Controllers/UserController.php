<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException; // TAMBAHKAN INI
use Illuminate\Validation\Rule;

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
                   ->paginate(100)
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
            ->route('user.index')
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

public function showRegister()
{
    return view('auth.register');
}

public function register(Request $request)
{
    $request->validate([
        'name'     => 'required|string|max:30',
        'username' => 'required|string|max:30|unique:users,username',
        'password' => 'required|string|min:8|confirmed',
    ], [
        'name.required'     => 'Nama wajib diisi.',
        'name.max'          => 'Nama maksimal 30 karakter.',
        'username.required' => 'Username wajib diisi.',
        'username.max'      => 'Username maksimal 30 karakter.',
        'username.unique'   => 'Username sudah digunakan.',
        'password.min'      => 'Password minimal 8 karakter.',
        'password.confirmed'=> 'Konfirmasi password tidak cocok.',
    ]);

    User::create([
        'name'     => $request->name,
        'username' => $request->username,
        'password' => Hash::make($request->password),
        'role'     => 'crew', // OTOMATIS crew
    ]);

    return redirect()->route('login')
                     ->with('success', 'Akun berhasil dibuat! Silakan login.');
}

public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
}

public function showProfile()
{
    $user = Auth::user();
    return view('user.profile', compact('user'));
}

public function editProfile()
{
    $user = Auth::user();
    return view('user.edit', compact('user'));
}

public function updateProfile(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'name'     => 'required|string|max:30',
        'username' => [
            'required',
            'string',
            'max:30',
            Rule::unique('users')->ignore($user->id), // izinkan username sendiri
        ],
        'password' => 'nullable|string|min:8|confirmed', // nullable agar bisa kosong
    ], [
        'name.required'     => 'Nama wajib diisi.',
        'name.max'          => 'Nama maksimal 30 karakter.',
        'username.required' => 'Username wajib diisi.',
        'username.max'      => 'Username maksimal 30 karakter.',
        'username.unique'   => 'Username sudah digunakan.',
        'password.min'      => 'Password minimal 8 karakter.',
        'password.confirmed'=> 'Konfirmasi password tidak cocok.',
    ]);

    $data = [
        'name'     => $request->name,
        'username' => $request->username,
    ];

    // Hanya update password jika diisi
    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }

    $user->update($data);

    return redirect()->route('profile.show')
                     ->with('success', 'Profil berhasil diperbarui!');
}

public function showForgot()
{
    return view('auth.forgot');
}

public function forgot(Request $request)
{
    $request->validate([
        'username' => 'required|exists:users,username',
        'password' => 'required|min:8|confirmed',
    ], [
        'username.exists' => 'Username tidak ditemukan.',
        'password.min' => 'Password minimal 8 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
    ]);

    $user = User::where('username', $request->username)->first();
    $user->password = Hash::make($request->password);
    $user->save();

    return redirect()->route('login')->with('success', 'Password berhasil diubah. Silakan login ulang.');
}

}
