<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Hobby;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // get token
    public function login(Request $r)
    {
        $validators = Validator::make($r->all(),[
            'email' => 'required|email|max:100',
            'password' => 'required|string|min:8',
        ]);

        if($validators->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan, proses login gagal',
                'errors' => $validators->errors(),
            ], 422);
        }

        $credentials = $r->only(['email', 'password']);

        if (! $token = auth('api')->attempt($credentials)) {
             return response()->json([
                'status' => false,
                'message' => 'email atau password salah',
            ], 401);
        }

        return response()->json([
            'status' => true,
            'message' => 'Login berhasil',
            'user' => Auth::guard('api')->user(),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->getTTL() * 60
        ], 200);
    }

    public function logout(Request $r){
        auth('api')->logout();
        return response()->json([
            'status' => true,
            'message' => 'berhasil logout'
        ], 201);
    }

    public function refresh()
    {
        return response()->json([
            'status' => true,
            'message' => 'Login berhasil',
            'user' => Auth::guard('api')->user(),
            'refresh_token' => auth('api')->refresh(),
            'token_type' => 'bearer',
        ], 200);
    }

    // crud user
    public function register(StoreUserRequest $r){
        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $r->name,
                'email' => $r->email,
                'password' => $r->password,
            ]);
            
            foreach($r->hobbies as $item){
                Hobby::create([
                    'user_id' => $user->id,
                    'hobby' => $item
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Proses berhasil',
            ], 201);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan, hubungi developer',
                'errors' => $th->getMessage(),
            ], 409);
        }
    }

    public function update(UpdateUserRequest $r){
        $validatorUser = Validator::make($r->all(),[
            'user_id' => 'nullable|exists:users,id',
            'user_email' => 'required_without:user_id|nullable|email|exists:users,email'
        ]);

        if($validatorUser->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Terjadi Kesalahan, mohon pastikan email atau id diisi',
                'errors' => $validatorUser->errors(),
            ], 422);
        }

        $user = User::find($r->getUserId());

        try {
            DB::beginTransaction();
            $data = [
                'name' => $r->name,
                'email' => $r->email,
            ];
            if($r->filled('password') && ! Hash::check($r->password, $user->password)){
                $data['password'] = $r->password;
            }

            $user->update($data);
            $user->hobbies()->delete();
            foreach($r->hobbies as $item){
                $user->hobbies()->create(['hobby' => $item]);
            }

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Proses berhasil',
            ], 201);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan, hubungi developer',
                'errors' => $th->getMessage(),
            ], 409);
        }

    }

    public function delete(Request $r){
        $validatorUser = Validator::make($r->all(),[
            'user_id' => 'nullable|exists:users,id',
            'user_email' => 'required_without:user_id|email'
        ]);

        if($validatorUser->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Terjadi Kesalahan, mohon pastikan email atau id diisi',
                'errors' => $validatorUser->errors(),
            ], 422);
        }

        $user = $r->filled('user_id') 
                ? User::find($r->user_id)
                : User::where('email', $r->user_email)->first();
        
        if(!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User tidak terdaftar',
                'errors' => 'user not found',
            ], 401);
        }
        
        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'Proses berhasil',
        ], 201);
    }
}
