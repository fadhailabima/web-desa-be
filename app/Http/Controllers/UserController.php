<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected function okResponse($message, $data = [])
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], 200);
    }

    protected function unautheticatedResponse($message)
    {
        return response()->json([
            'message' => $message,
        ], 401);
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $loginData['username'])->first();

        if (!$user || !Hash::check($loginData['password'], $user->password)) {
            return $this->unautheticatedResponse('Kombinasi username dan password tidak valid.');
        }

        $token = $user->createToken('authToken')->plainTextToken;
        $userData = array_merge($user->toArray(), ['token' => $token]);

        return $this->okResponse("Login Berhasil", ['user' => $userData]);
    }

    public function tambahUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = new User;
        $user->nama = $request->input('nama');
        $user->username = $request->input('username');
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return response()->json([
            'message' => 'User berhasil ditambahkan',
            'data' => $user
        ]);
    }

    public function hapusUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User tidak ditemukan',
            ], 404);
        }

        $user->delete();

        return response()->json([
            'message' => 'User berhasil dihapus',
        ]);
    }
}
