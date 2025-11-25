<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Brick\Math\BigInteger;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{

    public function index()
    {
        $users = User::all();
        // return response()->json($users);
        return response()->json([
            'success' => true,
            'data' => $users,
            // "message" => "hell yeahhh!"
        ]);
    }

    public function show(Request $request, int $id)
    {
        $user = User::where('is_active', false)
            ->whereNull('deleted_at')
            ->find($id);

        if (!$user)
            return response()->json([
                'success' => false,
                'data' => []
            ], 404);

        return response()->json([
            'success' => true,
            'data' => [$user]
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_id' => ['required'],
            'name' => ['required', 'min:2', 'max:50'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()],
            'first_name' => ['required', 'min:1', 'max:50'],
            'last_name' => ['required', 'min:1', 'max:50'],
        ]);

        $user = User::create($validated);

    }
}
