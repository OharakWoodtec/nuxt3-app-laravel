<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
// 以下を追記↓
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use \Symfony\Component\HttpFoundation\Response;


class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //  とにかくtrueにしておく
        return true;
    }

    /**
     * バリテーションルール
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:6'],
        ];
    }

    //
    //  ValidationエラーをJsonで返す
    //
    protected function failedValidation(Validator $validator)
    {
        \Log::debug('validation strat');
        \Log::debug($validator->errors());
        $response = response()->json([
            'status' => 'Response::HTTP_BAD_REQUEST',
            'errors' => $validator->errors(),
        ], Response::HTTP_BAD_REQUEST);
        throw new HttpResponseException($response);
    }

}
