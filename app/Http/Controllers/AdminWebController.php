<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Category;
use App\Models\FoodItem;
use App\Models\Beverage;
use Illuminate\Http\Request;

class AdminWebController extends Controller {

    // public function dashboard() {
    //     $stats = [
    //         'total_customers' => User::where('role', 'customer')->count(),
    //         'total_orders' => Order::count(),
    //         'total_sales' => Order::sum('total_price'),
    //         'low_stock' => FoodItem::where('available_quantity', '<', 5)->get(),
    //     ];
    //     $categories = Category::all();
    //     $foods = FoodItem::with('category')->latest()->get();
    //     $beverages = Beverage::with('category')->latest()->get();

    //     return view('admin.dashboard', compact('stats', 'categories', 'foods', 'beverages'));
    // }

    // --- إدارة الأطعمة ---
    public function storeFood(Request $request) {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'spicy_level' => 'required|integer|min:0|max:5',
            'available_quantity' => 'required|integer|min:0',
        ]);

        FoodItem::create($data);
        return redirect()->back()->with('success', 'تمت إضافة الطعام بنجاح!');
    }

    public function updateFood(Request $request, FoodItem $food) {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'spicy_level' => 'required|integer|min:0|max:5',
            'available_quantity' => 'required|integer|min:0',
        ]);

        $food->update($data);
        return redirect()->back()->with('success', 'تم تعديل الصنف بنجاح!');
    }

    public function destroyFood(FoodItem $food) {
        $food->delete();
        return redirect()->back()->with('success', 'تم حذف الصنف بنجاح!');
    }

    // --- إدارة المشروبات ---
    public function storeBeverage(Request $request) {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'temperature' => 'required|in:hot,cold,both',
        ]);

        Beverage::create($data);
        return redirect()->back()->with('success', 'تمت إضافة المشروب بنجاح!');
    }

    public function destroyBeverage(Beverage $beverage) {
        $beverage->delete();
        return redirect()->back()->with('success', 'تم حذف المشروب بنجاح!');
    }

    // --- إدارة الأقسام ---
    public function storeCategory(Request $request) {
        $data = $request->validate([
            'name' => 'required|string|unique:categories,name',
        ]);
        $data['slug'] = \Illuminate\Support\Str::slug($data['name']);

        Category::create($data);
        return redirect()->back()->with('success', 'تم إضافة القسم بنجاح!');
    }

    public function dashboard() {
    $stats = [
        'total_customers' => User::where('role', 'customer')->count(),
        'total_orders'    => Order::count(),
        'total_sales'     => Order::sum('total_price'),
        'low_stock'       => FoodItem::where('available_quantity', '<', 5)->count(),
        'total_foods'     => FoodItem::count(),
        'total_beverages' => Beverage::count(),
        'total_categories'=> Category::count(),
    ];

    $categories = Category::all();
    $foods = FoodItem::with('category')->latest()->get();
    $beverages = Beverage::with('category')->latest()->get();

    // بيانات الرسوم البيانية
    $chartCategoryNames = $categories->pluck('name');
    $chartFoodCounts = $categories->map(fn($cat) => $foods->where('category_id', $cat->id)->count());

    return view('admin.dashboard', compact(
        'stats', 
        'categories', 
        'foods', 
        'beverages', 
        'chartCategoryNames', 
        'chartFoodCounts'
    ));
}
}