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

    // メーカー名検索（セレクトボックス用に修正）
    if ($request->filled('company_id')) {
        $query->where('company_id', $request->company_id);
    }

    // 商品名検索
    if ($request->filled('product_name')) {
        $query->where('product_name', 'like', '%' . $request->product_name . '%');
    }

    $products = $query->get();

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
        try {
            $product = Product::findOrFail($id);

            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $product->delete();

            return redirect()->route('products.index')->with('success', '商品を削除しました。');
        } catch (\Exception $e) {
            return redirect()->route('products.index')->with('error', '商品を削除できませんでした: ' . $e->getMessage());
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
}
