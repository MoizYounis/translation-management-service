<?php

namespace App\Abstracts;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest as LaravelFormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

abstract class FormRequest extends LaravelFormRequest
{
    abstract public function rules(): array;

    abstract public function authorize(): bool;

    public function validator($factory): Validator
    {

        return $factory->make($this->formatRequest(), $this->container->call([$this, 'rules']), $this->messages());
    }

    protected function formatRequest(): array
    {
        if (method_exists($this, 'formatter')) {
            return $this->container->call([$this, 'formatter']);
        }

        return $this->all();
    }

    protected function failedValidation(Validator $validator): void
    {
        $errors = $validator->errors()->first();
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => $errors,
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
