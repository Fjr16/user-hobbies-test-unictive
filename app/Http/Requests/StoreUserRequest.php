<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreUserRequest extends FormRequest
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
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:8',
            'hobbies' => 'required|array|min:1',
            'hobbies.*' => 'required|string|max:30'
        ];
    }

    protected function failedValidation(Validator $validators)
    {
        if($this->expectsJson()){
            throw new HttpResponseException(response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan, Proses registrasi gagal',
                'errors' => $validators->errors(),
            ], 422));
        }

        parent::failedValidation($validators);
    }
}
