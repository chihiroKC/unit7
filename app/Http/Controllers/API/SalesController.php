<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalesController extends Controller
{
    /**
     * 購入処理API
     */
    public function purchase(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id'
        ]);

        return DB::transaction(function () use ($request) {
            try {
                $product = Product::lockForUpdate()->findOrFail($request->product_id);

                // 在庫チェック
                if ($product->stock <= 0) {
                    return response()->json(['error' => '在庫がありません'], 400);
                }

                // 在庫を減らす
                $product->decrement('stock');

                // 購入履歴を追加（購入日時などのデータを含める）
                Sale::create([
                    'product_id' => $product->id,
                    'created_at' => now()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => '購入完了',
                    'new_stock' => $product->stock
                ], 200);
            } catch (\Exception $e) {
                Log::error('購入処理エラー: ' . $e->getMessage());
                return response()->json(['error' => '購入処理に失敗しました'], 500);
            }
        });
    }
}