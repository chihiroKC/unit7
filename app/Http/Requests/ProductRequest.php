<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * 認証の権限チェックを定義
     */
    public function authorize()
    {
        // trueを返すことで誰でもこのリクエストを使えるように設定
        return true;
    }

    /**
     * バリデーションルールを定義
     */
    public function rules()
    {
        return [
            'product_name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'company_id' => 'required|exists:companies,id',
            'comment' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    /**
     * エラーメッセージをカスタマイズ（必要に応じて）
     */
    public function messages()
    {
        return [
            'product_name.required' => '商品名は商品にとって命だよ',
            'price.required' => 'タダで売っちゃダメ！',
            'stock.required' => '何個売る？',
            // 他のカスタムメッセージを追加
        ];
    }
}
