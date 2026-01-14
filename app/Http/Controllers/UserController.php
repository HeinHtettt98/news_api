<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdatProileRequest;

class UserController extends Controller
{
    function index()
    {
        return "User Controller";
    }

    function register(UserRegisterRequest $request)
    {
        try {
            $data = $request->validated();
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'user',
                'avatar' => '',
            ]);
            return response()->json([
                'user' => $user,
            ], 201);
            //   Auth::login($user);  auto-login after register
        } catch (\Exception $e) {

            Log::error('User registration failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Failed to create user',
            ], 500);
        }
    }

    function login(UserLoginRequest $request)
    {
        $credentials = $request->validated();
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        $request->session()->regenerate();

        return response()->json([
            'user' => Auth::user(),
        ]);
    }

    public function updateProfile(UserUpdatProileRequest $request)
    {
        $user = Auth::user();

        $data = $request->only(['name', 'email']);

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        $user->update($data);

        return response()->json([
            'user' => $user,
        ]);
    }

    function newsByUser(Request $request)
    {
        $user = $request->user(); // authenticated user

        // $allowedSorts = [
        //     'created_at',
        //     'title',
        // ];

        // ✅ Read query params with defaults
        // $sortBy  = $request->query('sort_by', 'created_at');
        $sortDir = $request->query('sort_dir', 'desc');

        // ✅ Security: validate sort column & direction
        // if (! in_array($sortBy, $allowedSorts)) {
        //     $sortBy = 'created_at';
        // }

        if (! in_array($sortDir, ['asc', 'desc'])) {
            $sortDir = 'desc';
        }

        $news = $user->news() // one-to-many relation
            ->with('user')
            ->orderBy('created_at', $sortDir)
            ->paginate(6)
            ->withQueryString(); // keeps sort params during pagination

        return response()->json([
            'data' => $news->items(),
            'meta' => [
                'current_page' => $news->currentPage(),
                'last_page'    => $news->lastPage(),
                'per_page'     => $news->perPage(),
                'total'        => $news->total(),
                'sort' => [
                    'by'  => 'created_at',
                    'dir' => $sortDir,
                ],
            ],
        ]);
    }

    public function logout(Request $request)
    {
        // Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out']);
    }
}
