<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index()
    {
        // products テーブルのデータを取得
        $products = Product::with('company')->get();

        // ビューにデータを渡す
        return view('products', compact('products'));
    }
    public function add()
    {
        $companies = Company::all();
        return view('products_add', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'company_id' => 'required|exists:companies,id',
            'comment' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 画像バリデーション
        ]);

        Product::create($request->all());
        return redirect()->route('products.index')->with('success', '商品を登録しました。');
    }

    public function info($id)
{
    $product = Product::with('company')->findOrFail($id);
    return view('info', compact('product'));
}

    public function delete($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', '商品を削除しました。');
    }
    public function edit($id)
{
    $product = Product::findOrFail($id);
    $companies = Company::all();
    return view('products_edit', compact('product', 'companies'));
}
public function update(Request $request, $id)
{
    $request->validate([
        'product_name' => 'required|string|max:255',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        'company_id' => 'required|exists:companies,id',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 画像バリデーション
    ]);

    $product = Product::findOrFail($id);

    if ($request->hasFile('image')) {
        if ($product->image) {
            Storage::disk('public')->delete($product->image); // 古い画像を削除
        }
        $product->image = $request->file('image')->store('product_images', 'public');
    }

    $product->update($request->except('image'));

    return redirect()->route('products.index')->with('success', '商品を更新しました');
}


}
