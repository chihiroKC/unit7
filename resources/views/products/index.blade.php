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

<div class="container">
    <h1>商品一覧</h1>

    <!-- 検索フォーム -->
<form id="search-form" class="mb-3">
    <div class="row">
        <!-- メーカー名検索 -->
        <div class="col-md-3">
            <select name="company_id" id="company_id" class="form-control">
                <option value="">メーカーを選択</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                @endforeach
            </select>
        </div>

        <!-- 商品名検索 -->
        <div class="col-md-3">
            <input type="text" name="product_name" id="product_name" class="form-control" placeholder="商品名">
        </div>

        <!-- 価格範囲検索 -->
        <div class="col-md-2">
            <input type="number" name="price_min" id="price_min" class="form-control" placeholder="価格 下限">
        </div>
        <div class="col-md-2">
            <input type="number" name="price_max" id="price_max" class="form-control" placeholder="価格 上限">
        </div>

        <!-- 在庫範囲検索 -->
        <div class="col-md-2">
            <input type="number" name="stock_min" id="stock_min" class="form-control" placeholder="在庫 下限">
        </div>
        <div class="col-md-2">
            <input type="number" name="stock_max" id="stock_max" class="form-control" placeholder="在庫 上限">
        </div>

        <!-- 検索ボタン -->
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">検索</button>
        </div>
    </div>
</form>

    <a href="{{ route('products.add') }}" class="btn btn-success mb-3">新規登録</a>

    <!-- 商品一覧 -->
    <table class="table table-striped">
    <thead>
        <tr>
            <th><a href="#" class="sort-link" data-column="id">商品ID</a></th>
            <th><a href="#" class="sort-link" data-column="product_name">商品名</a></th>
            <th><a href="#" class="sort-link" data-column="price">価格</a></th>
            <th><a href="#" class="sort-link" data-column="stock">在庫数</a></th>
            <th><a href="#" class="sort-link" data-column="company_id">メーカー名</a></th>
            <th>商品画像</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody id="product-list">
    @foreach ($products as $product)
        <tr id="product-row-{{ $product->id }}">
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
             <button class="btn btn-primary purchase-product" data-id="{{ $product->id }}">購入</button>
             <button class="btn btn-danger delete-product" data-id="{{ $product->id }}">削除</button>
            </td>
        </tr>
    @endforeach
</tbody>
</table>

<!-- ページネーション -->
{{ $products->appends(request()->query())->links() }}
</div>


<!-- jQueryの読み込み -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    function bindDeleteEvent() {
        $('.delete-product').off('click').on('click', function() {
            if (!confirm('削除しますか？')) {
                return;
            }

            let productId = $(this).data('id');

            $.ajax({
                url: "/products/" + productId,
                type: "DELETE",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        $('#product-row-' + productId).fadeOut(500, function() {
                            $(this).remove();
                            bindDeleteEvent();
                        });
                    } else {
                        alert('削除に失敗しました。');
                    }
                },
                error: function(xhr) {
    let errorMessage = "削除に失敗しました。";

    if (xhr.status === 404) {
        errorMessage = "商品が見つかりませんでした。";
    } else if (xhr.status === 403) {
        errorMessage = "権限がありません。";
    } else if (xhr.status === 500) {
        errorMessage = "サーバーエラーが発生しました。";
    } else {
        try {
            let response = JSON.parse(xhr.responseText);
            if (response.error) {
                errorMessage = response.error;
            }
        } catch (e) {
            console.error("JSON パースエラー:", e);
            errorMessage = "不明なエラーが発生しました。";
        }
    }

    console.error("削除エラー:", xhr.responseText);
    alert(errorMessage);
}
            });
        });
    }


    function bindPurchaseEvent() {
        $('.purchase-product').off('click').on('click', function() {
            let productId = $(this).data('id');
            let button = $(this);

            $.ajax({
                url: "/api/purchase",
                type: "POST",
                data: { product_id: productId },
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message);

                        // 在庫数を更新
                        let stockCell = $('#product-row-' + productId + ' td:nth-child(4)');
                        stockCell.text(response.new_stock);

                        // 在庫が0になったら購入ボタンを無効化
                        if (response.new_stock <= 0) {
                            button.prop('disabled', true).text('売り切れ');
                        }
                    } else {
                        alert(response.error || '購入に失敗しました。');
                    }
                },
                error: function(xhr) {
                    let errorMessage = "購入に失敗しました。";

                    if (xhr.status === 400) {
                        errorMessage = "在庫が不足しています。";
                    } else if (xhr.status === 404) {
                        errorMessage = "商品が見つかりませんでした。";
                    } else if (xhr.status === 500) {
                        errorMessage = "サーバーエラーが発生しました。";
                    }

                    alert(errorMessage);
                }
            });
        });
    }

    bindPurchaseEvent();

    $('.sort-link').click(function(event) {
        event.preventDefault();

        let column = $(this).data('column');
        let searchParams = new URLSearchParams(window.location.search);
        let currentOrder = searchParams.get('order_direction') || 'asc';

        let newOrder = (currentOrder === 'asc') ? 'desc' : 'asc';
        searchParams.set('order_by', column);
        searchParams.set('order_direction', newOrder);

        window.location.href = window.location.pathname + '?' + searchParams.toString();
    });

    $('#search-form').submit(function(event) {
        event.preventDefault();

        let company_id = $('#company_id').val();
        let product_name = $('#product_name').val();
        let price_min = $('#price_min').val() ? parseInt($('#price_min').val(), 10) : null;
        let price_max = $('#price_max').val() ? parseInt($('#price_max').val(), 10) : null;
        let stock_min = $('#stock_min').val() ? parseInt($('#stock_min').val(), 10) : null;
        let stock_max = $('#stock_max').val() ? parseInt($('#stock_max').val(), 10) : null;

        $.ajax({
            url: "/products/search",
            type: "GET",
            data: {
                company_id: company_id,
                product_name: product_name,
                price_min: price_min,
                price_max: price_max,
                stock_min: stock_min,
                stock_max: stock_max
            },
            dataType: "json",
            success: function(response) {
                let productList = $('#product-list');
                productList.empty();

                if (response.length > 0) {
                    $.each(response, function(index, product) {
                        let imageTag = product.image 
                            ? `<img src="{{ asset('storage') }}/${product.image}" alt="商品画像" width="50">`
                            : "画像なし";

                        let row = `
                            <tr id="product-row-${product.id}">
                                <td>${product.id}</td>
                                <td>${product.product_name}</td>
                                <td>${product.price}</td>
                                <td>${product.stock}</td>
                                <td>${product.company.company_name || '不明'}</td>
                                <td>${imageTag}</td>
                                <td>
                                    <a href="/products/${product.id}/info" class="btn btn-info">詳細</a>
                                    <button class="btn btn-danger delete-product" data-id="${product.id}">削除</button>
                                </td>
                            </tr>
                        `;
                        productList.append(row);
                    });

                    bindDeleteEvent();
                } else {
                    productList.append('<tr><td colspan="7">製品がありません。</td></tr>');
                }
            }
        });
    });

    bindDeleteEvent();
});
</script>
@endsection