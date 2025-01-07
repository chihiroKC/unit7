<?php 

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('company');

        // メーカー名検索
        if ($request->filled('company_name')) {
            $query->whereHas('company', function ($q) use ($request) {
                $q->where('company_name', 'like', '%' . $request->company_name . '%');
            });
        }

        // 商品名検索
        if ($request->filled('product_name')) {
            $query->where('product_name', 'like', '%' . $request->product_name . '%');
        }

        $products = $query->get();

        return view('products.index', compact('products'));
    }

    public function add()
    {
        $companies = Company::all();
        return view('products_add', compact('companies'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'product_name' => 'required|string|max:255',
                'price' => 'required|integer|min:0',
                'stock' => 'required|integer|min:0',
                'company_id' => 'required|exists:companies,id',
                'comment' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $data = $request->except('image');

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

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'product_name' => 'required|string|max:255',
                'price' => 'required|numeric',
                'stock' => 'required|integer',
                'company_id' => 'required|exists:companies,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $product = Product::findOrFail($id);

            $data = $request->except('image');

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
