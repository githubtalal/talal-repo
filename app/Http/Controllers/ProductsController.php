<?php

namespace App\Http\Controllers;

use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Product;
use App\Requests\ProductRequest;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Product::query();

        if (request('search')) {
            $products->where('name', 'like', '%' . request('search') . '%');
        }

        if (request('category')) {
            $products->where('category_id', request('category'));
        }

        if (request('type')) {
            $products->where('type', request('type'));
        }

        if (request()->filled('active')) {
            $products->where('active', request('active'));
        }

        $products = $products->paginate(10)->withQueryString();

        // get types of products
        $types = Product::groupBy('type')->pluck('type')->toArray();

        // get categories of products
        $categories = Category::query()->has('products')->get();

        $categoriesCount = Category::query()->count();

        return view('newDashboard.products', compact('products', 'types', 'categories', 'categoriesCount'));
    }

    public function edit(Product $product)
    {
        $categories = [];

        $items = Category::get(['id', 'name']);

        if ($items) {
            foreach ($items as $item) {
                $categories[$item->id] = $item->name;
            }
        }

        return view('newDashboard.ecommerce.edit-product', compact('product', 'categories'));
    }

    public function update(Product $product, ProductRequest $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required',
            'fees_type' => 'required_if:has_special_fees,on',
            'percentage_fees_amount' => 'required_if:fees_type,percentage',
            'number_fees_amount' => 'required_with_all:fees_type,has_special_fees',
            'tax_type' => 'required_if:has_special_tax,on',
            'percentage_tax_amount' => 'required_if:tax_type,percentage',
            'number_tax_amount' => 'required_with_all:tax_type,has_special_tax',
            'image_url' => 'nullable|image',
            'type' => 'required',
        ]);

        $data = $request->except(['percentage_fees_amount', 'number_fees_amount', 'percentage_tax_amount', 'number_tax_amount', 'previous_image']);

        $data['active'] = request('active') ? 1 : 0;

        $data['require_end_date'] = request()->get('require_end_date') === 'on';
        $data['unit'] = $request->type == 'reservation' ? 'period-of-time' : 'quantity';

        if (request()->hasFile('image_url')) {
            $data['image_url'] = $request->file('image_url')->store('products', 'public');
        }

        $data['has_special_fees'] = request()->get('has_special_fees') === 'on';

        if ($request->fees_type == 'percentage') {
            $data['fees_amount'] = $request->percentage_fees_amount;
        } else if ($request->fees_type == 'number') {
            $data['fees_amount'] = $request->number_fees_amount;
        }

        $data['has_special_tax'] = request()->get('has_special_tax') === 'on';

        if ($request->tax_type == 'percentage') {
            $data['tax_amount'] = $request->percentage_tax_amount;
        } else if ($request->tax_type == 'number') {
            $data['tax_amount'] = $request->number_tax_amount;
        }

        try {
            $product->update($data);
            return redirect()->route('products.index')->with('success_message', __('app.alert_messages.update.success', ['item' => trans_choice('app.products.products', 1)]));
        } catch (\Exception$e) {
            logger($e->getMessage());
            return redirect()->back()->with('error_message', __('app.alert_messages.update.failed', ['item' => trans_choice('app.products.products', 1)]));
        }
    }

    public function update_status(Product $product, Request $request)
    {
        try {
            $product->update($request->all());
            return redirect()->back()->with('success_message', __('app.alert_messages.update.success', ['item' => trans_choice('app.products.products', 1)]));
        } catch (\Exception$e) {
            logger($e->getMessage());
            return redirect()->back()->with('error_message', __('app.alert_messages.update.failed', ['item' => trans_choice('app.products.products', 1)]));
        }
    }

    public function create()
    {
        $categories = [];

        $items = Category::get(['id', 'name']);

        if ($items) {
            foreach ($items as $item) {
                $categories[$item->id] = $item->name;
            }
        }

        return view('newDashboard.ecommerce.add-product', compact('categories'));
    }

    public function store(ProductRequest $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required',
            'fees_type' => 'required_if:has_special_fees,on',
            'percentage_fees_amount' => 'required_if:fees_type,percentage',
            'number_fees_amount' => 'required_with_all:fees_type,has_special_fees',
            'tax_type' => 'required_if:has_special_tax,on',
            'percentage_tax_amount' => 'required_if:tax_type,percentage',
            'number_tax_amount' => 'required_with_all:tax_type,has_special_tax',
            'image_url' => 'required|image',
            'type' => 'required',
        ]);

        $data = $request->except(['percentage_fees_amount', 'number_fees_amount', 'percentage_tax_amount', 'number_tax_amount', 'previous_image']);
        $data['active'] = request('active') ? 1 : 0;

        $data['require_end_date'] = request()->get('require_end_date') === 'on';
        $data['unit'] = $request->type == 'reservation' ? 'period-of-time' : 'quantity';

        if (request()->hasFile('image_url')) {
            $data['image_url'] = $request->file('image_url')->store('products', 'public');
        }

        $data['has_special_fees'] = request()->get('has_special_fees') === 'on';

        if ($request->fees_type == 'percentage') {
            $data['fees_amount'] = $request->percentage_fees_amount;
        } else if ($request->fees_type == 'number') {
            $data['fees_amount'] = $request->number_fees_amount;
        }

        $data['has_special_tax'] = request()->get('has_special_tax') === 'on';

        if ($request->tax_type == 'percentage') {
            $data['tax_amount'] = $request->percentage_tax_amount;
        } else if ($request->tax_type == 'number') {
            $data['tax_amount'] = $request->number_tax_amount;
        }

        $user = Auth::user();
        $data['store_id'] = $user->store->id;

        try {
            Product::create($data);
            return redirect()->route('products.index')->with('success_message', __('app.alert_messages.save.success', ['item' => trans_choice('app.products.products', 1)]));
        } catch (\Exception$e) {
            logger($e->getMessage());
            return redirect()->back()->with('error_message', __('app.alert_messages.save.failed', ['item' => trans_choice('app.products.products', 1)]));
        }
    }

    public function destroy(Product $product)
    {
        try {

            $orderItems = OrderItem::where('product_id', $product->id)->get();

            if (count($orderItems)) {
                return redirect()->back()->with('warning_message', __('app.responses_messages.warning_dalete_message', ['item' => trans_choice('app.products.products', 1)]));
            }

            CartItem::query()->where('product_id', $product->id)->delete();
            $product->delete();
            return redirect()->back()->with('success_message', __('app.alert_messages.delete.success', ['item' => trans_choice('app.products.products', 1)]));
        } catch (\Exception$e) {
            logger($e->getMessage());
            return redirect()->back()->with('error_message', __('app.alert_messages.delete.failed', ['item' => trans_choice('app.products.products', 1)]));
        }
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required',
        ]);

        Excel::import(new ProductsImport, $request->file);
        return redirect()->route('products.index');
    }

    public function export()
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }
}
