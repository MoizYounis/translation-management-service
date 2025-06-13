<?php

namespace App\Http\Requests\Api;

use App\Abstracts\FormRequest;

class LoginRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|string|max:255|exists:users,email',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => __('lang.required', ['attribute' => __('lang.attributes.email')]),
            'email.email' => __('lang.email', ['attribute' => __('lang.attributes.email')]),
            'email.string' => __('lang.string', ['attribute' => __('lang.attributes.email')]),
            'email.max' => __('lang.max', ['attribute' => __('lang.attributes.email')]),
            'email.exists' => __('lang.exists', ['attribute' => __('lang.attributes.email')]),
            'password.required' => __('lang.required', ['attribute' => __('lang.attributes.password')]),
        ];
    }

    public function prepareRequest()
    {
        $request = $this;

        return [
            'email' => $request['email'],
            'password' => $request['password'],
        ];
    }
}
