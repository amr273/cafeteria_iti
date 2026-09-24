<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\FoodItem;
use App\Models\Beverage;
use App\Models\Category;

class CustomerWebController extends Controller
{
    /**
     * عرض قائمة المنيو
     */
    public function menu(Request $request)
    {
        $search = trim((string) $request->input('q', ''));
        $categoryId = $request->input('category_id');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $spicyLevel = $request->input('spicy_level');

        $foodQuery = FoodItem::query()
            ->when($categoryId !== null && $categoryId !== '', function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($minPrice !== null && $minPrice !== '', function ($query) use ($minPrice) {
                $query->where('price', '>=', (float) $minPrice);
            })
            ->when($maxPrice !== null && $maxPrice !== '', function ($query) use ($maxPrice) {
                $query->where('price', '<=', (float) $maxPrice);
            })
            ->when($spicyLevel !== null && $spicyLevel !== '', function ($query) use ($spicyLevel) {
                $query->where('spicy_level', (int) $spicyLevel);
            });

        $beverageQuery = Beverage::query()
            ->when($categoryId !== null && $categoryId !== '', function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($minPrice !== null && $minPrice !== '', function ($query) use ($minPrice) {
                $query->where('price', '>=', (float) $minPrice);
            })
            ->when($maxPrice !== null && $maxPrice !== '', function ($query) use ($maxPrice) {
                $query->where('price', '<=', (float) $maxPrice);
            });

        if ($search !== '') {
            $foodQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });

            $beverageQuery->where('name', 'like', "%{$search}%");
        }

        $categories = Category::orderBy('name')->get();
        $foods = $foodQuery->get();
        $beverages = $beverageQuery->get();

        return view('customer.menu', compact('categories', 'foods', 'beverages', 'search', 'categoryId', 'minPrice', 'maxPrice', 'spicyLevel'));
    }

    /**
     * عرض صفحة طلباتي / سلة التسوق الحالية
     */
    public function myOrders()
    {
        $order = Order::with('items.itemable')
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->latest()
            ->first();

        return view('customer.orders', compact('order'));
    }

    /**
     * زيادة أو تقليل كمية عنصر في السلة (عبر AJAX)
     */
    public function updateQuantity(Request $request, $id)
    {
        $item = OrderItem::findOrFail($id);
        $action = $request->input('action');

        if ($action === 'increase') {
            $item->quantity += 1;
        } elseif ($action === 'decrease') {
            $item->quantity -= 1;
        }

        if ($item->quantity <= 0) {
            $item->delete();
            $itemDeleted = true;
        } else {
            $item->save();
            $itemDeleted = false;
        }

        $order = Order::with('items')->find($item->order_id);

        if ($order) {
            if ($order->items->count() === 0) {
                $order->delete();
                return response()->json([
                    'success' => true,
                    'order_deleted' => true
                ]);
            }

            $newTotal = $order->items->sum(function ($i) {
                return $i->price * $i->quantity;
            });
            $order->total_price = $newTotal;
            $order->save();

            return response()->json([
                'success' => true,
                'item_deleted' => $itemDeleted,
                'quantity' => $item->quantity,
                'item_total' => number_format($item->price * $item->quantity, 2),
                'order_total' => number_format($newTotal, 2)
            ]);
        }

        return response()->json(['success' => false, 'message' => 'الطلب غير موجود']);
    }

    /**
     * حذف عنصر واحد من السلة
     */
    public function removeItem($id)
    {
        $item = OrderItem::findOrFail($id);
        $order = Order::with('items')->find($item->order_id);

        $item->delete();

        if ($order) {
            if ($order->items()->count() === 0) {
                $order->delete();
            } else {
                $order->total_price = $order->items()->get()->sum(fn($i) => $i->price * $i->quantity);
                $order->save();
            }
        }

        return redirect()->back()->with('success', 'تم حذف المنتج من السلة بنجاح.');
    }

    /**
     * تفريغ السلة بالكامل
     */
    public function clearAll($id)
    {
        $order = Order::where('user_id', auth()->id())->findOrFail($id);

        $order->items()->delete();
        $order->delete();

        return redirect()->back()->with('success', 'تم تفريغ السلة وحذف جميع العناصر بنجاح.');
    }

    /**
     * عرض صفحة إتمام الدفع (Checkout)
     */
    public function checkout($id)
    {
        $order = Order::with('items.itemable')
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('customer.checkout', compact('order'));
    }

    /**
     * معالجة عملية الدفع وتأكيد الطلب
     */
    public function processPayment(Request $request, Order $order)
    {
        $order->status = 'completed';
        $order->save();

        return redirect()->route('customer.menu')->with('success', 'تم إتمام عملية الدفع بنجاح! شكراً لك.');
    }

    /**
     * إلغاء الطلب
     */
    public function cancel(Order $order)
    {
        $order->status = 'cancelled';
        $order->save();

        return redirect()->route('customer.orders')->with('success', 'تم إلغاء الطلب بنجاح.');
    }

    /**
     * عرض صفحة الشات بوت
     */
    public function chatbot()
    {
        return view('customer.chatbot');
    }

    /**
     * معالجة رسائل الشات بوت باستخدام Google Gemini AI وقاعدة البيانات
     */
    /**
     * معالجة رسائل الشات بوت باستخدام Google Gemini AI وقاعدة البيانات
     */
    public function chatbotSend(Request $request)
    {
        $userMessage = trim($request->input('message', ''));

        if (empty($userMessage)) {
            return response()->json(['reply' => 'يرجى كتابة سؤالك لأستطيع مساعدتك!']);
        }

        try {
            // 1. جلب المنيو من قاعدة البيانات
            $foods = FoodItem::all();
            $beverages = Beverage::all();

            $menuContext = "قائمة الطعام والوجبات المتاحة:\n";
            foreach ($foods as $food) {
                $desc = isset($food->description) && !empty($food->description) ? " (الوصف: {$food->description})" : "";
                $menuContext .= "- {$food->name}: بسعر {$food->price} ج.م{$desc}\n";
            }

            $menuContext .= "\nقائمة المشروبات المتاحة:\n";
            foreach ($beverages as $bev) {
                $desc = isset($bev->description) && !empty($bev->description) ? " (الوصف: {$bev->description})" : "";
                $menuContext .= "- {$bev->name}: بسعر {$bev->price} ج.م{$desc}\n";
            }

            // 2. جلب معلومات العميل والطلب الأخير
            $userContext = "";
            if (auth()->check()) {
                $user = auth()->user();
                $lastOrder = Order::where('user_id', $user->id)
                    ->latest()
                    ->first();

                $userContext .= "العميل الحالي: {$user->name}\n";
                if ($lastOrder) {
                    $userContext .= "الطلب الأخير (رقم #{$lastOrder->id}): الحالة ({$lastOrder->status}) - الإجمالي ({$lastOrder->total_price} ج.م)\n";
                } else {
                    $userContext .= "العميل ليس لديه طلبات سابقة.\n";
                }
            } else {
                $userContext .= "العميل غير مسجل الدخول.\n";
            }

            // 3. تعليمات النظام
            $systemInstruction = "أنت مساعد ذكي ولطيف لكافيتريا. رد على استفسارات العملاء بناءً على البيانات التالية من قاعدة البيانات فقط:\n\n" .
                $menuContext . "\n" .
                $userContext . "\n" .
                "القواعد:\n" .
                "1. أجب باللغة العربية بشكل مختصر، ودود، واستخدم إيموجي.\n" .
                "2. التزم بالأسعار والأصناف وحالات الطلب الموجودة أعلاه فقط.\n" .
                "3. إذا سأل عن شيء غير موجود بالمنيو، أخبره بلباقة أنه غير متوفر.";

            // 4. جلب المفتاح
            $apiKey = config('services.gemini.api_key') ?? env('GEMINI_API_KEY');

            if (!$apiKey) {
                return response()->json(['reply' => '⚠️ لم يتم ضبط GEMINI_API_KEY في ملف .env']);
            }

            // 5. إرسال الطلب للموديل Gemini 3.6 Flash
            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent?key={$apiKey}";

            $response = Http::withoutVerifying()
                ->retry(3, 300)
                ->post($url, [
                    'system_instruction' => [
                        'parts' => [
                            ['text' => $systemInstruction]
                        ]
                    ],
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [
                                ['text' => $userMessage]
                            ]
                        ]
                    ]
                ]);

            if ($response->successful()) {
                $result = $response->json();
                $reply = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'عذراً، لم أستطع معالجة الإجابة.';
                return response()->json(['reply' => $reply]);
            }

            Log::error('Gemini API Error: ' . $response->body());
            $errMessage = $response->json()['error']['message'] ?? 'خطأ في المفتاح أو الاتصال';
            return response()->json(['reply' => 'حدث خطأ من الذكاء الاصطناعي: ' . $errMessage]);
        } catch (\Exception $e) {
            Log::error('Chatbot Exception: ' . $e->getMessage());
            return response()->json(['reply' => 'حدث خطأ: ' . $e->getMessage()]);
        }
    }

    /**
     * عرض التوصيات
     */
    public function recommendations()
    {
        $recommendedFoods = FoodItem::inRandomOrder()->take(3)->get();
        $recommendedBeverages = Beverage::inRandomOrder()->take(3)->get();

        return view('customer.recommendations', compact('recommendedFoods', 'recommendedBeverages'));
    }

    /**
     * عرض صفحة تفضيلات العميل
     */
    public function preferences()
    {
        return view('customer.preferences');
    }

    /**
     * حفظ/تحديث التفضيلات
     */
    public function updatePreferences(Request $request)
    {
        return redirect()->back()->with('success', 'تم حفظ التفضيلات بنجاح.');
    }

    /**
     * إضافة منتج إلى السلة/الطلب
     */
    public function storeOrder(Request $request)
    {
        $request->validate([
            'item_id'   => 'required',
            'item_type' => 'nullable|string',
            'quantity'  => 'nullable|integer|min:1'
        ]);

        $quantity = (int) $request->input('quantity', 1);

        $order = Order::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'status'  => 'pending',
            ],
            [
                'total_price' => 0
            ]
        );

        if ($request->input('item_type') === 'beverage' || $request->has('beverage_id')) {
            $itemId = $request->input('item_id', $request->beverage_id);
            $itemModel = Beverage::findOrFail($itemId);
            $itemableType = Beverage::class;
        } else {
            $itemId = $request->input('item_id', $request->food_id);
            $itemModel = FoodItem::findOrFail($itemId);
            $itemableType = FoodItem::class;
        }

        $price = $itemModel->price;

        $orderItem = OrderItem::where('order_id', $order->id)
            ->where('itemable_id', $itemModel->id)
            ->where('itemable_type', $itemableType)
            ->first();

        if ($orderItem) {
            $newQuantity = $orderItem->quantity + $quantity;
            $orderItem->update([
                'quantity' => $newQuantity,
                'subtotal' => $price * $newQuantity,
            ]);
        } else {
            OrderItem::create([
                'order_id'      => $order->id,
                'itemable_id'   => $itemModel->id,
                'itemable_type' => $itemableType,
                'price'         => $price,
                'quantity'      => $quantity,
                'subtotal'      => $price * $quantity,
            ]);
        }

        $newTotal = OrderItem::where('order_id', $order->id)->sum('subtotal');
        $order->update(['total_price' => $newTotal]);

        return redirect()->back()->with('success', 'تمت إضافة المنتج إلى السلة بنجاح.');
    }

    /**
     * معالجة وتأكيد إتمام الدفع
     */
    public function processCheckout(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'phone'            => 'required|string|max:20',
            'delivery_address' => 'nullable|string|max:500',
            'table_number'    => 'required|string|max:100',
            'payment_method'  => 'required|string|in:cash,card',
        ]);

        $order->update([
            'status'           => 'completed',
            'phone'            => $request->phone,
            'delivery_address' => $request->delivery_address ?: $request->table_number,
            'payment_method'   => $request->payment_method,
            'notes'            => $request->notes,
        ]);

        return redirect()->route('customer.orders')->with('success', 'تم تأكيد طلبك وإتمام عملية الدفع بنجاح!');
    }
}
