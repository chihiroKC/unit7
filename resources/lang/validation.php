<?php

return [
    'accepted'             => ':attributeを承認してください。',
    'active_url'           => ':attributeが有効なURLではありません。',
    'after'                => ':attributeには、:dateより後の日付を指定してください。',
    'after_or_equal'       => ':attributeには、:date以降の日付を指定してください。',
    'alpha'                => ':attributeには、アルファベットのみ使用できます。',
    'alpha_dash'           => ':attributeには、英数字、ハイフン、アンダースコアのみ使用できます。',
    'alpha_num'            => ':attributeには、英数字のみ使用できます。',
    'array'                => ':attributeには、配列を指定してください。',
    'before'               => ':attributeには、:dateより前の日付を指定してください。',
    'before_or_equal'      => ':attributeには、:date以前の日付を指定してください。',
    'between'              => [
        'numeric' => ':attributeには、:minから:maxまでの数値を指定してください。',
        'file'    => ':attributeには、:min KBから:max KBまでのファイルを指定してください。',
        'string'  => ':attributeには、:min文字から:max文字までの文字列を指定してください。',
        'array'   => ':attributeには、:min個から:max個までのアイテムを含めてください。',
    ],
    'boolean'              => ':attributeには、trueかfalseを指定してください。',
    'confirmed'            => ':attributeと確認用の値が一致しません。',
    'email'                => ':attributeには、有効なメールアドレスを指定してください。',
    'required'             => ':attributeは必須項目です。',
    'unique'               => ':attributeはすでに存在しています。',
    // 他のエラーメッセージも追加
    'attributes' => [
        'name' => 'ユーザー名',
        'email' => 'メールアドレス',
        'password' => 'パスワード',
    ],
];
