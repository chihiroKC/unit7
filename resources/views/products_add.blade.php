@extends('layouts.app')

@section('content')
<div class="container">
    <h1>商品登録</h1>
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="product_name">商品名 <span class="text-danger">*</span></label>
            <input type="text" name="product_name" id="product_name" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="price">価格 <span class="text-danger">*</span></label>
            <input type="number" name="price" id="price" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="stock">在庫数 <span class="text-danger">*</span></label>
            <input type="number" name="stock" id="stock" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="company_id">メーカー名 <span class="text-danger">*</span></label>
            <select name="company_id" id="company_id" class="form-control" required>
                <option value="" disabled selected>選択してください</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="comment">コメント</label>
            <textarea name="comment" id="comment" class="form-control" rows="3"></textarea>
        </div>

        <div class="form-group">
            <label for="image">商品画像</label>
            <input type="file" name="image" id="image" class="form-control-file">
        </div>

        <button type="submit" class="btn btn-primary">登録</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">戻る</a>
    </form>
</div>
@endsection
