@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('ログインに成功しました！') }}

                    <p>{{ __('商品一覧へ移ります。少々お待ちください。') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // 3秒後に products.blade.php にリダイレクト
    setTimeout(function() {
        window.location.href = "{{ route('products.index') }}";
    }, 3000);
</script>
@endsection
