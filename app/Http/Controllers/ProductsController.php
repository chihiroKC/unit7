<?php 

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest; // フォームリクエストをインポート
use App\Models\Product;
use App\Models\Company;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(Request $request)
{
    $query = Product::with('company');
    $companies = Company::all(); // メーカー一覧を取得

    // ソートの設定（初期値: id降順）
    $orderBy = $request->input('order_by', 'id');
    $orderDirection = $request->input('order_direction', 'desc');

    // メーカー名検索（セレクトボックス用に修正）
    if ($request->filled('company_id')) {
        $query->where('company_id', $request->company_id);
    }

    // 商品名検索
    if ($request->filled('product_name')) {
        $query->where('product_name', 'like', '%' . $request->product_name . '%');
    }

     // 価格範囲検索
     if ($request->filled('price_min')) {
        $query->where('price', '>=', $request->price_min);
    }
    if ($request->filled('price_max')) {
        $query->where('price', '<=', $request->price_max);
    }

    // 在庫範囲検索
    if ($request->filled('stock_min')) {
        $query->where('stock', '>=', $request->stock_min);
    }
    if ($request->filled('stock_max')) {
        $query->where('stock', '<=', $request->stock_max);
    }

    // ソートを適用
    $products = $query->orderBy($orderBy, $orderDirection)->paginate(10);

    return view('products.index', compact('products', 'companies'));
}

    public function add()
    {
        $companies = Company::all();
        return view('products_add', compact('companies'));
    }

    public function store(ProductRequest $request)
    {
        try {
            $data = $request->validated(); // バリデーション済みデータを取得

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('products', 'public');
            }

            Product::create($data);

            return redirect()->route('products.index')->with('success', '商品を登録しました。');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', '商品登録に失敗しました: ' . $e->getMessage());
        }
    }

    public function info($id)
    {
        $product = Product::with('company')->findOrFail($id);
        return view('info', compact('product'));
    }

    public function delete($id)
    {
        $product = Product::find($id);

    if (!$product) {
        return response()->json(['success' => false, 'message' => '商品が見つかりません。']);
    }

    try {
        $product->delete();
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => '削除に失敗しました。']);
    }
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $companies = Company::all();
        return view('products_edit', compact('product', 'companies'));
    }

    public function update(ProductRequest $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            $data = $request->validated(); // バリデーション済みデータを取得

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('products', 'public');

                // 古い画像の削除
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
            }

            $product->update($data);

            return redirect()->route('products.index')->with('success', '商品情報を更新しました。');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', '商品情報を更新できませんでした: ' . $e->getMessage());
        }
    }
    
    public function search(Request $request)
{
    $query = Product::with('company');

    // メーカーで絞り込み
    if ($request->filled('company_id')) {
        $query->where('company_id', $request->company_id);
    }

    // 商品名で絞り込み
    if ($request->filled('product_name')) {
        $query->where('product_name', 'like', '%' . $request->product_name . '%');
    }

    // 価格範囲検索
    if ($request->filled('price_min')) {
        $query->where('price', '>=', $request->price_min);
    }
    if ($request->filled('price_max')) {
        $query->where('price', '<=', $request->price_max);
    }

    // 在庫範囲検索
    if ($request->filled('stock_min')) {
        $query->where('stock', '>=', $request->stock_min);
    }
    if ($request->filled('stock_max')) {
        $query->where('stock', '<=', $request->stock_max);
    }

    // 商品リストを取得
    $products = $query->get();

    // JSONレスポンスを返す
    return response()->json($products->map(function ($product) {
        return [
            'id' => $product->id,
            'product_name' => $product->product_name,
            'price' => $product->price,
            'stock' => $product->stock,
            'company' => [
                'company_name' => $product->company->company_name ?? '不明'
            ],
            'image' => $product->image ? asset('storage/' . $product->image) : null
        ];
    }));
}
}
