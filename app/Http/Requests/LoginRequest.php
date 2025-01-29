<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * 認証の権限チェックを定義
     */
    public function authorize()
    {
        return true; // 誰でもこのリクエストを使用可能
    }

    /**
     * バリデーションルールを定義
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ];
    }

    /**
     * エラーメッセージをカスタマイズ
     */
    public function messages()
    {
        return [
            'email.required' => 'メールアドレスを教えてください〜',
            'email.email' => 'それはメールアドレスではない、、',
            'password.required' => 'パスワードが無ければ通れません！',
            'password.min' => 'パスワードは8文字以上で!',
        ];
    }
}
