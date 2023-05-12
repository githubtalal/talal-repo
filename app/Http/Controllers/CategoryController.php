<?php

namespace App\Http\Controllers;

use App\Exports\CategoriesExport;
use App\Imports\CategoriesImport;
use App\Models\Category;
use App\Models\OrderItem;
use App\Requests\CategoryRequest;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::query();

        if (request('search')) {
            $categories->where('name', 'like', '%' . request('search') . '%');
        }

        $categories = $categories->paginate(10)->withQueryString();

        return view('newDashboard.categories', compact('categories'));
    }

    public function edit(Category $category)
    {
        return view('newDashboard.ecommerce.edit-category', compact('category'));
    }

    public function update(Category $category, CategoryRequest $request)
    {
        $data = $request->all();

        if (!request('active')) {
            $data['active'] = 0;
        }

        if (!array_key_exists('status', $data['image_settings'])) {
            $data['image_settings']['status'] = 0;
        }

        if (!request('active')) {
            $data['active'] = 0;
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories/', 'public');
        } else {
            $data['image'] = $category->image;
        }

        try {
            $category->update($data);
            return redirect()->route('category.index')->with('success_message', __('app.alert_messages.update.success', ['item' => trans_choice('app.categories.categories', 1)]));
        } catch (\Exception$e) {
            logger($e->getMessage());
            return redirect()->back()->with('error_message', __('app.alert_messages.update.failed', ['item' => trans_choice('app.categories.categories', 1)]));
        }
    }

    public function create()
    {
        return view('newDashboard.ecommerce.add-category');
    }

    public function store(CategoryRequest $request)
    {
        $data = $request->all();

        if (!request('active')) {
            $data['active'] = 0;
        }

        if (!array_key_exists('status', $data['image_settings'])) {
            $data['image_settings']['status'] = 0;
        }

        if (!request('active')) {
            $data['active'] = 0;
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories/', 'public');
        }

        $user = Auth::user();
        $data['store_id'] = $user->store->id;

        try {
            Category::create($data);
            return redirect()->route('category.index')->with('success_message', __('app.alert_messages.save.success', ['item' => trans_choice('app.categories.categories', 1)]));
        } catch (\Exception$e) {
            logger($e->getMessage());
            return redirect()->back()->with('error_message', __('app.alert_messages.save.failed', ['item' => trans_choice('app.categories.categories', 1)]));
        }
    }

    public function destroy(Category $category)
    {
        try {
            $products = $category->products;
            if ($products) {
                foreach ($products as $product) {
                    $orderItems = OrderItem::where('product_id', $product->id)->get();
                    if (count($orderItems)) {
                        return redirect()->back()->with('warning_message', __('app.responses_messages.warning_dalete_message', ['item' => trans_choice('app.categories.categories', 1)]));
                    }
                }
                $products->each->delete();
            }

            $category->delete();
            return redirect()->back()->with('success_message', __('app.alert_messages.delete.success', ['item' => trans_choice('app.categories.categories', 1)]));
        } catch (\Exception$e) {
            logger($e->getMessage());
            return redirect()->back()->with('error_message', __('app.alert_messages.delete.failed', ['item' => trans_choice('app.categories.categories', 1)]));
        }
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required',
        ]);

        Excel::import(new CategoriesImport, $request->file);
        return redirect()->route('category.index');
    }

    public function export()
    {
        return Excel::download(new CategoriesExport, 'categories.xlsx');
    }
}
