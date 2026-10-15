<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Hobby;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index(){
        $data = User::with('hobbies')
            ->select('name', 'email', 'id')
            ->paginate(10);

        return view('pages.users.index', [
            'title' => 'Manajemen User & Hobby',
            'data' => $data
        ]);
    }
    public function show(string $userId){
        try {
            $item = User::with('hobbies')
                ->select('name', 'email', 'id')
                ->where('id', decrypt($userId))
                ->first();
    
            return response()->json([
                'status' => true,
                'data' => $item ?: null
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'data' => null
            ]);
        }
    }

    public function store(StoreUserRequest $r){
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
            return back()->with('success', 'Berhasil simpan data user');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            DB::rollBack();
            return back()
                ->with('error', 'terjadi kesalahan saat simpan data, hubungi developer');
        }
    }

    public function update(UpdateUserRequest $r, $id){
        $user = User::find($r->getUserId());
        if(!$user){
            return back()->with('error', 'user tidak ditemukan');
        }

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
            return back()->with('success', 'Berhasil simpan data user');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            DB::rollBack();
            return back()
                ->with('error', 'terjadi kesalahan saat simpan data, hubungi developer');
        }
    }

    public function destroy($id){
        try {
            $user = User::findOrFail(decrypt($id));
            $user->delete();
            
            return back()->with('success','Berhasil hapus data');
        } catch (ModelNotFoundException $e) {
            Log::error($e->getMessage());
            return back()
                ->with('error', 'user tidak ditemukan');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return back()
                ->with('error', 'terjadi kesalahan saat hapus data, hubungi developer');
        }
    }
}
