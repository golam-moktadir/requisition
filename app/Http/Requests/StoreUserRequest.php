<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'          => 'required|max:250',
            'email1'        => 'required|email:rfc,dns|regex:/(.+)@(.+)\.(.+)/i|max:99|unique:App\Models\User,email',
            'roles'         => 'required',
            'emp_id'        => 'required',
            'password'              => 'required|min:8|max:16|confirmed',
            'password_confirmation' => 'required|min:8|max:16',
        ];
    }

    public function messages()
    {
        return [
            'email1.required'       => 'The email field is required.',
            'email1.max'            => 'The email field max length 99.',
            'email1.unique'         => 'The email field value is already exists.',            
            'password.min'          => 'The password length must be at least 8 characters',
        ];
    }
}
