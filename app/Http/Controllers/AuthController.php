<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        // Debug: Clear any existing session
        if (Auth::check()) {
            Auth::logout();
            session()->flush();
            session()->regenerate();
        }
        
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            // Check if user is admin
            if (!$user->is_admin) {
                Auth::logout();
                return back()->with('error', 'Akses ditolak. Hanya admin yang dapat mengakses sistem ini.')
                           ->onlyInput('email');
            }
            
            $request->session()->regenerate();
            
            return redirect()->intended('/materials')->with('success', 'Login berhasil! Selamat datang, ' . $user->name . '.');
        }

        return back()->with('error', 'Email atau password tidak valid.')
                   ->onlyInput('email');
    }

    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('materials.index');
        }
        
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Silakan login dengan akun Anda.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }

    public function showCreateAdminForm()
    {
        // Only allow root admin to create new users
        if (Auth::user()->email !== 'admin@kemenlh.go.id') {
            return redirect()->route('admin.manage')->with('error', 'Hanya Root Admin yang dapat menambah user baru.');
        }
        
        return view('admin.create');
    }

    public function createAdmin(Request $request)
    {
        // Only allow root admin to create new users
        if (Auth::user()->email !== 'admin@kemenlh.go.id') {
            return redirect()->route('admin.manage')->with('error', 'Hanya Root Admin yang dapat menambah user baru.');
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => false, // Regular users are not admin
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.manage')->with('success', 'User baru berhasil dibuat!');
    }

    public function manageAdmins()
    {
        $admins = User::orderBy('created_at', 'desc')->get();
        return view('admin.manage', compact('admins'));
    }

    public function deleteAdmin($id)
    {
        // Only allow root admin to delete users
        if (Auth::user()->email !== 'admin@kemenlh.go.id') {
            return back()->with('error', 'Hanya Root Admin yang dapat menghapus user.');
        }
        
        $user = User::findOrFail($id);
        
        // Prevent deleting root admin
        if ($user->email === 'admin@kemenlh.go.id') {
            return back()->with('error', 'Akun Root Admin tidak dapat dihapus. Akun ini adalah akun sistem yang dilindungi.');
        }

        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }
}
