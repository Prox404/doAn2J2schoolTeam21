<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $current_user_level = session('level');
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'level' => 'required|lte:'.$current_user_level,
            'birthday' => 'required|date',
        ];
    }
}
