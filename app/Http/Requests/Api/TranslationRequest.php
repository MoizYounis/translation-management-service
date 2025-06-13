<?php

namespace App\Http\Requests\Api;

use App\Abstracts\FormRequest;
use Illuminate\Validation\Rule;

class TranslationRequest extends FormRequest
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
        $id = $this->route('id');

        return [
            'locale' => 'required|string|max:5',
            'key' => [
                'required',
                'string',
                'max:255',
                // isset($id) && $id ? Rule::unique('translations', 'key')->ignore($id) : 'unique:translations,key'
            ],
            'value' => 'required|string',
            'tags' => 'nullable|array',
            'cdn_ready' => 'boolean',
        ];
    }

    public function messages()
    {
        return [];
    }

    public function prepareRequest()
    {
        $request = $this;
        return [
            'locale' => $request['locale'],
            'key' => $request['key'],
            'value' => $request['value'],
            'tags' => $request['tags'],
            'cdn_ready' => $request['cdn_ready'],
        ];
    }
}
