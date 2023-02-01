<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function adminlogin(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        $user = DB::table('users')->where('email', $request->email)->first();
        $role = $user->role;

        if ($role == 'ADMIN') {
            if (Auth::attempt($credentials)) {
                $success = true;
                $message = "Admin login successfully";
            } else {
                $success = false;
                $message = "Unautorised";
            }
        } else {
            $success = false;
            $message = "NOT ADMIN";
        }


        $response = [
            'success' => $success,
            'message' => $message
        ];

        return response()->json($response);
    }
}
