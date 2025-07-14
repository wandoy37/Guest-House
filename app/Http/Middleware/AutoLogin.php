<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AutoLogin
{
    public function handle(Request $request, Closure $next)
    {
        // Logout user saat ini jika sudah login
        if (Auth::check()) {
            Auth::logout();
        }

        // Login dengan user ID yang diinginkan
        $user = User::find(2); // Ganti ke ID yang diinginkan
        if ($user) {
            Auth::login($user);
        }

        return $next($request);
    }
}
