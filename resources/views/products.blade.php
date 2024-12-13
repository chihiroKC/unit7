@extends('layouts.app')

@section('content')
<div class="container">
    <h1>商品一覧</h1>
    <a href="{{ route('products.add') }}" class="btn btn-success mb-3">新規登録</a>

    <table class="table table-striped">
    <thead>
    <tr>
        <th>商品ID</th>
        <th>商品名</th>
        <th>価格</th>
        <th>在庫数</th>
        <th>メーカー名</th>
        <th>商品画像</th>
        <th>操作</th>
    </tr>
</thead>
<tbody>
    @forelse ($products as $product)
        <tr>
            <td>{{ $product->id }}</td>
            <td>{{ $product->product_name }}</td>
            <td>{{ $product->price }}</td>
            <td>{{ $product->stock }}</td>
            <td>{{ $product->company->company_name ?? '不明' }}</td>
            <td>
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="商品画像" width="50">
                @else
                    画像なし
                @endif
            </td>
            <td>
                <a href="{{ route('products.info', $product->id) }}" class="btn btn-info">詳細</a>
                <form action="{{ route('products.delete', $product->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('削除しますか？')">削除</button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7">製品がありません。</td>
        </tr>
    @endforelse
</tbody>

    </table>
</div>
@endsection
