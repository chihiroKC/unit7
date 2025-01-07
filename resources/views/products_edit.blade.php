@extends('layouts.app')

@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container">
    <h1>商品編集</h1>
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- 商品情報IDを固定表示 -->
        <div class="form-group">
            <label for="product_id">商品情報ID</label>
            <input type="text" id="product_id" class="form-control" value="{{ $product->id }}" disabled>
        </div>

        <div class="form-group">
            <label for="product_name">商品名 <span class="text-danger">*</span></label>
            <input type="text" name="product_name" id="product_name" class="form-control" value="{{ $product->product_name }}" required>
        </div>

        <div class="form-group">
            <label for="price">価格 <span class="text-danger">*</span></label>
            <input type="number" name="price" id="price" class="form-control" value="{{ $product->price }}" required>
        </div>

        <div class="form-group">
            <label for="stock">在庫数 <span class="text-danger">*</span></label>
            <input type="number" name="stock" id="stock" class="form-control" value="{{ $product->stock }}" required>
        </div>

        <div class="form-group">
            <label for="company_id">メーカー名 <span class="text-danger">*</span></label>
            <select name="company_id" id="company_id" class="form-control" required>
                <option value="" disabled>選択してください</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}" {{ $product->company_id == $company->id ? 'selected' : '' }}>
                        {{ $company->company_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="comment">コメント</label>
            <textarea name="comment" id="comment" class="form-control">{{ $product->comment }}</textarea>
        </div>

        <div class="form-group">
            <label for="image">商品画像</label>
            @if($product->image)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="商品画像" width="100">
                </div>
            @endif
            <input type="file" name="image" id="image" class="form-control-file">
        </div>

        <button type="submit" class="btn btn-success">更新</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">戻る</a>
    </form>
</div>
@endsection
