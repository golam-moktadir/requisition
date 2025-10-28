<?php

namespace Modules\IncomeExpense\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AccountSubHeadsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'account_head_name'     => 'required|max:200',
            'status'                => 'required',
            'parent_id'             => 'required',
        ];
    }

    public function messages()
    {
        return [
            // 'status.required'       => 'The email field is required.',
        ];
    }
}
