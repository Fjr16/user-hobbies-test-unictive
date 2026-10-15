<?php

namespace App\Http\Controllers;

use App\Models\Hobby;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(){
        // data table user
    }
    public function create(){
        // form input user
    }
    public function edit(){
        // form edit user
    }

    public function store(Request $r){
        $validators = Validator::make($r->all(),[
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:8',
            'hobbies' => 'required|array',
            'hobbies.*' => 'required|string|max:30'
        ]);

        if($validators->fails()){
            return back()
                ->withErrors($validators)
                ->withInput($r->except('password'));
        }

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

    public function update(Request $r, $id){
        $user = User::find(decrypt($id));
        if(!$user){
            return back()->with('error', 'user tidak ditemukan');
        }

        $validators = Validator::make($r->all(),[
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8',
            'hobbies' => 'required|array',
            'hobbies.*' => 'required|string|max:30'
        ]);

        if($validators->fails()){
            return back()
                ->withErrors($validators)
                ->withInput($r->except('password'));
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

    public function delete($id){
        try {
            $user = User::findOrFail(decrypt($id));
            $user->delete();
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
