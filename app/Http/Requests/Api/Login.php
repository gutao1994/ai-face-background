<?php

namespace App\Http\Requests\Api;

class Login extends FormRequest
{

    public function rules()
    {
        return [
            'code' => ['required'],
            'encryptedData' => ['required'],
            'iv' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'code.required' => '不合法的登陆凭证',
            'encryptedData.required' => '不合法的登陆凭证',
            'iv.required' => '不合法的登陆凭证',
        ];
    }



}
