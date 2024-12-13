<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('products', function (Blueprint $table) {
        $table->string('image')->nullable()->after('comment'); // 商品画像パス
    });
}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn('image');
    });
}

};