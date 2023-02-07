<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {
            $success = true;
            $message = "User login successfully";
        } else {
            $success = false;
            $message = "Unautorised";
        }

        $response = [
            'success' => $success,
            'message' => $message
        ];

        return response()->json($response);
    }


    public function register(Request $request)
    {
        try {
//            $user = new User();
//            $user->name = $request->name;
//            $user->email = $request->email;
//            $user->role = $request->role;
//            $user->password = Hash::make($request->password);
//            $user->save();

            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'role' => $request['role'],
                'password' => Hash::make($request['password'])
            ]);

            event(new Registered($user));
            $user->save();


            $success = true;
            $message = "User register successfully";

        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        $response = [
            'success' => $success,
            'message' => $message
        ];

        return response()->json($response);

    }

    public function logout()
    {
        try {
            Session::flush();
            $success = true;
            $message = "Logout successfully";
        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        $response = [
            'success' => $success,
            'message' => $message
        ];

        return response()->json($response);
    }

    public function index()
    {
        $posts = User::all()->toArray();
        return array_reverse($posts);
    }

    public function edit($id)
    {
        $post = User::find($id);
        return response()->json($post);
    }

    public function update($id, Request $request)
    {
        $user = User::find($id);
        $request->validate([
            'name',

        ]);


        $input = $request->all();

        $user->update($input);

        return response()->json(['success' => 'User update successfully']);
    }

    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json(['success' => 'User deleted successfully']);

    }
}
