<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            Gate::authorize('manage-users');
            return $next($request);
        });
    }

    public function index()
    {
        $users = User::paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function ban(User $user)
    {
        if ($user->role === 'Admin') {
            return redirect()->route('admin.users.index')->with('error', 'Không thể cấm quản trị viên.');
        }
        $user->status = 'banned';
        $user->save();
        return redirect()->route('admin.users.index')->with('success', 'Người dùng đã bị cấm.');
    }

    public function unban(User $user)
    {
        $user->status = 'active';
        $user->save();
        return redirect()->route('admin.users.index')->with('success', 'Người dùng đã được bỏ cấm.');
    }

    public function destroy(User $user)
    {
        if ($user->role === 'Admin') {
            return redirect()->route('admin.users.index')->with('error', 'Không thể xóa quản trị viên.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Người dùng đã bị xóa.');
    }

    public function comments(User $user)
    {
        $comments = $user->comments()->with('post')->paginate(10);
        return view('admin.users.comments', compact('user', 'comments'));
    }

    public function updateRole(Request $request, User $user)
    {
        // Prevent changing the role of an Admin user
        if ($user->role === 'Admin') {
            return redirect()->route('admin.users.show', $user)->with('error', 'Không thể thay đổi vai trò của quản trị viên.');
        }

        $validatedData = $request->validate([
            'role' => ['required', Rule::in(['User', 'Vip', 'Editor', 'Admin'])],
        ]);

        $user->role = $validatedData['role'];
        $user->save();

        return redirect()->route('admin.users.show', $user)->with('success', 'Vai trò người dùng đã được cập nhật.');
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->save();

        return redirect()->route('admin.users.show', $user)->with('success', 'Thông tin người dùng đã được cập nhật.');
    }
}
