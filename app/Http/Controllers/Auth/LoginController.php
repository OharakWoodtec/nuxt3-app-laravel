<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest; 


//
//  sanctum用ログインController 
//
class LoginController extends Controller
{
    
    //
    //  ユーザー登録
    //
    public function register(RegisterRequest $request)
    {
        //  Validationはフォームクラスで実施
        //  ユーザーをＤＢ登録する
        \Log::debug("DB start");
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        //  ログインする
        \Log::debug("login start");
        Auth::login($user);
        //  ログイン情報を返す
        return response()->json(Auth::user());
    }

    //
    //  ログイン処理
    //
    public function login(Request $request)
    {
        $credentials = $request->validate([
            "email" => ["required", "email"],
            "password" => ["required"],
        ]);
  
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return response()->json(Auth::user());
        }
        return response()->json([], 401);
    }

    //
    //  ログアウト処理
    //
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json(['message' => 'ログアウトしました']);
    }
}
