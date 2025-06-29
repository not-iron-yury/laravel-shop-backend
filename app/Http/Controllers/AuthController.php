<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Product;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AuthController extends Controller
{
    use AuthorizesRequests; // authorize()

    // регистрация
    public function register(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => $fields['password'],
        ]);

        /**
         * Имя токена ('api_token') только для идентификации токенов,
         * задаётся вручную при вызове createToken('имя').
         *
         * Свойство plainTextToken возвращает готовый к использованию строковый токен.
         */
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }


    // авторизация
    public function login(Request $request){
        $fields = $request->validate([
            'email' => 'required|string|email|',
            'password' => 'required|string|',
        ]);

        /**
         * SELECT * FROM users WHERE email = ...
         * Метод first() возвращает только первую найденную запись, или null.
         */
        $user = User::where('email', $fields['email'])->first();


        /**
         * Hash::check(...) — сравнивает введённый пароль с захешированным из базы.
         * Если пароль не совпадает, check() вернёт false
         */
        if(!$user || !Hash::check($fields['password'], $user->password)){
            throw ValidationException::withMessages([
                'email' => ['Данные указаны неверно.']
            ]);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logged out'
        ]);
    }

    public function me(Request $request) {
        // $product = Product::find(1); // временно, для проверки ProductPolicy
        // $this->authorize('update', $product); // временно, для проверки ProductPolicy
        return response()->json($request->user());
    }
}
