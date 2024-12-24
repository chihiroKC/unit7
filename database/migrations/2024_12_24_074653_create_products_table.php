<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();  // 自動インクリメントのID
            $table->string('product_name');  // 商品名
            $table->integer('price');  // 価格
            $table->integer('stock');  // 在庫数
            $table->unsignedBigInteger('company_id');  // メーカーID（外部キー）
            $table->text('comment')->nullable();  // コメント
            $table->string('image')->nullable();  // 商品画像
            $table->timestamps();  // 作成・更新日時

            // 外部キー制約を追加
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
