<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,'. $this->getUserId(),
            'password' => 'nullable|string|min:8',
            'hobbies' => 'required|array|min:1',
            'hobbies.*' => 'required|string|max:30'
        ];
    }

    public function getUserId(){
        if($id = $this->route('id')){
            return $userId = User::find(decrypt($id))->id ?: null;
        }
        $user = $this->filled('user_id') 
                ? User::find($this->user_id)
                : User::where('email', $this->user_email)->first();
        return $user?->id;
    }
    
    protected function failedValidation(Validator $validators)
    {
        if($this->expectsJson()){
            throw new HttpResponseException(response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan, Proses update data gagal',
                'errors' => $validators->errors(),
            ], 422));
        }

        parent::failedValidation($validators);
    }
}
