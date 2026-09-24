<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Category;
use App\Models\FoodItem;
use App\Models\Beverage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminWebController extends Controller
{

    // --- إدارة الأطعمة ---
    public function storeFood(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'spicy_level' => 'required|integer|min:0|max:5',
            'available_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('foods', 'public');
        }

        FoodItem::create($data);
        return redirect()->back()->with('success', 'تمت إضافة الطعام بنجاح!');
    }

    public function updateFood(Request $request, FoodItem $food)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'spicy_level' => 'required|integer|min:0|max:5',
            'available_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($food->image && Storage::disk('public')->exists($food->image)) {
                Storage::disk('public')->delete($food->image);
            }
            $data['image'] = $request->file('image')->store('foods', 'public');
        }

        $food->update($data);
        return redirect()->back()->with('success', 'تم تعديل الصنف بنجاح!');
    }

    public function destroyFood(FoodItem $food)
    {
        if ($food->image && Storage::disk('public')->exists($food->image)) {
            Storage::disk('public')->delete($food->image);
        }

        $food->delete();
        return redirect()->back()->with('success', 'تم حذف الصنف بنجاح!');
    }

    // --- إدارة المشروبات ---
    public function storeBeverage(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'temperature' => 'required|in:hot,cold,both',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('beverages', 'public');
        }

        Beverage::create($data);
        return redirect()->back()->with('success', 'تمت إضافة المشروب بنجاح!');
    }

    public function updateBeverage(Request $request, Beverage $beverage)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'temperature' => 'required|in:hot,cold,both',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($beverage->image && Storage::disk('public')->exists($beverage->image)) {
                Storage::disk('public')->delete($beverage->image);
            }
            $data['image'] = $request->file('image')->store('beverages', 'public');
        }

        $beverage->update($data);
        return redirect()->back()->with('success', 'تم تعديل المشروب بنجاح!');
    }

    public function destroyBeverage(Beverage $beverage)
    {
        if ($beverage->image && Storage::disk('public')->exists($beverage->image)) {
            Storage::disk('public')->delete($beverage->image);
        }

        $beverage->delete();
        return redirect()->back()->with('success', 'تم حذف المشروب بنجاح!');
    }

    // --- إدارة الأقسام ---
    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:categories,name',
        ]);
        $data['slug'] = \Illuminate\Support\Str::slug($data['name']);

        Category::create($data);
        return redirect()->back()->with('success', 'تم إضافة القسم بنجاح!');
    }

    public function updateUserRole(Request $request, User $user)
    {
        $data = $request->validate([
            'role' => 'required|in:admin,customer',
        ]);

        $user->update(['role' => $data['role']]);

        return redirect()->back()->with('success', 'تم تحديث دور المستخدم بنجاح!');
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $statusMap = [
            'processing' => 'preparing',
            'cancelled' => 'pending',
        ];

        $status = $request->input('status', $order->status);
        $normalizedStatus = $statusMap[$status] ?? $status;
        $request->merge(['status' => $normalizedStatus]);

        $data = $request->validate([
            'status' => 'required|in:pending,preparing,ready,completed',
        ]);

        $order->update(['status' => $data['status']]);

        return redirect()->back()->with('success', 'تم تحديث حالة الطلب بنجاح!');
    }

    public function destroyOrder(Order $order)
    {
        $order->delete();

        return redirect()->back()->with('success', 'تم حذف الطلب بنجاح!');
    }

    public function dashboard(Request $request)
    {
        $stats = [
            'total_customers'  => User::where('role', 'customer')->count(),
            'total_orders'     => Order::count(),
            'total_sales'      => Order::sum('total_price') ?? 0,
            'low_stock'        => FoodItem::where('available_quantity', '<', 5)->count(),
            'total_foods'      => FoodItem::count(),
            'total_beverages'  => Beverage::count(),
            'total_categories' => Category::count(),
        ];

        $categories = Category::all();
        $foods = FoodItem::with('category')->latest()->get();
        $beverages = Beverage::with('category')->latest()->get();
        $users = User::latest()->get();

        $customerName = trim((string) $request->query('customer_name', ''));
        $status = $request->query('status');

        $ordersQuery = Order::with(['user', 'items.itemable'])->latest();

        if ($customerName !== '') {
            $ordersQuery->whereHas('user', function ($query) use ($customerName) {
                $query->where('name', 'like', "%{$customerName}%");
            });
        }

        if ($status !== null && $status !== '') {
            if ($status === 'processing') {
                $status = 'preparing';
            }

            if (in_array($status, ['pending', 'preparing', 'ready', 'completed'], true)) {
                $ordersQuery->where('status', $status);
            }
        }

        $orders = $ordersQuery->get();

        $chartCategoryNames = $categories->pluck('name');
        $chartFoodCounts = $categories->map(fn($cat) => $foods->where('category_id', $cat->id)->count());

        return view('admin.dashboard', compact(
            'stats',
            'categories',
            'foods',
            'beverages',
            'users',
            'orders',
            'chartCategoryNames',
            'chartFoodCounts',
            'customerName',
            'status'
        ));
    }
}
