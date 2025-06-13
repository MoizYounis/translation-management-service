<?php

namespace App\Abstracts;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest as LaravelFormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

abstract class FormRequest extends LaravelFormRequest
{
    abstract public function rules();

    abstract public function authorize();

    public function validator($factory)
    {

        return $factory->make($this->formatRequest(), $this->container->call([$this, 'rules']), $this->messages());
    }

    protected function formatRequest()
    {
        if (method_exists($this, 'formatter')) {
            return $this->container->call([$this, 'formatter']);
        }

        return $this->all();
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->first();
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => $errors,
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
