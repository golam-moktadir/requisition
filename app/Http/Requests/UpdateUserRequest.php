<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user       = $this->route('user');
        $password   = trim($this->input('password'));
        
        if($password!='' || ! empty($password)){
            return [
                'name'                  => 'required|max:250',
                'email1'                => 'required|regex:/(.+)@(.+)\.(.+)/i|max:99|unique:App\Models\User,email,' . $user->id,
                'roles'                 => 'required',
                'status'                => 'required',  
                'password'              => 'required|min:8|max:16|confirmed',  
                'password_confirmation' => 'required|min:8|max:16',  
            ];
        }
        else {
            return [
                'name'      => 'required|max:250',
                'email1'    => 'required|regex:/(.+)@(.+)\.(.+)/i|max:99|unique:App\Models\User,email,' . $user->id,
                'roles'     => 'required',
                'status'    => 'required',  
            ];
        } 
    }

    public function messages()
    {
        return [
            'email1.required'   => 'The email field is required.',
            'email1.regex'      => 'The email address format is not correct.',
            'email1.max'        => 'The email field max length 99.',
            'email1.unique'     => 'The email field value is already exists.'
        ];
    }

    protected function prepareForValidation()
    {
        // Example of accessing request data
        $this->merge([
            'email1' => trim($this->email1), // Trim whitespace from the name
            // 'password' => trim($this->password),
            // 'confirm_password' => trim($this->confirm_password),            
        ]);

        $email1 = $this->input('email1'); // Get specific input

        // You can perform additional logic based on $userInput
    }    
}
