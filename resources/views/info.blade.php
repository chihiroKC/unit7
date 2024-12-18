@extends('layouts.app')

@section('content')
<div class="container">
    <h1>商品詳細</h1>
    <table class="table table-bordered">
        <tr>
            <th>商品情報ID</th> <!-- 商品情報IDを追加 -->
            <td>{{ $product->id }}</td>
        </tr>
        <tr>
            <th>商品名</th>
            <td>{{ $product->product_name }}</td>
        </tr>
        <tr>
            <th>価格</th>
            <td>{{ $product->price }}</td>
        </tr>
        <tr>
            <th>在庫数</th>
            <td>{{ $product->stock }}</td>
        </tr>
        <tr>
            <th>メーカー名</th>
            <td>{{ $product->company->company_name ?? '不明' }}</td>
        </tr>
        <tr>
            <th>コメント</th>
            <td>{{ $product->comment ?? 'なし' }}</td>
        </tr>
        <tr>
            <th>商品画像</th> <!-- 商品画像を追加 -->
            <td>
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="商品画像" width="150">
                @else
                    画像なし
                @endif
            </td>
        </tr>
    </table>
    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">編集</a>

    <a href="{{ route('products.index') }}" class="btn btn-secondary">戻る</a>
</div>
@endsection
