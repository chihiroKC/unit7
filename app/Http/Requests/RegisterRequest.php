<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8|confirmed',
        ];
    }

    /**
     * エラーメッセージをカスタマイズ
     */
    public function messages()
    {
        return [
            'name.required' => 'ユーザー名がなきゃ始まらない',
            'name.max' => '名前が長すぎて入りきらないや、、',
            'email.required' => 'メールアドレスを教えてください〜',
            'email.max' => 'メールアドレスが長すぎて入りきらないや、、',
            'email.unique' => 'そのメールアドレスは既に知ってます！',
            'password.required' => 'パスワードつくろ＾＾',
            'password.confirmed'=> 'パスワードが一致していないみたい',
            'password.min' => '8文字以上のパスワードを求む!',
            'password_confirmation.required' => 'こっちにも入力して！',
            'password_confirmation.confirmed'=> 'パスワードが一致していないみたい',
            'password_confirmation.min' => '8文字以上のパスワードを求む!',
        ];
    }
}
