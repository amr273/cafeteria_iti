<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>طلباتي وسلة التسوق - الكافتيريا الذكية</title>
    
    <!-- Google Fonts: Cairo -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- استدعاء Tailwind CSS و Livewire لضمان عمل navigation-menu بشكل سليم -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        :root {
            --orange-primary: #f97316;
            --orange-gradient: linear-gradient(135deg, #ff8c42 0%, #e85d04 100%);
            --green-sage: #87a878;
            --green-sage-gradient: linear-gradient(135deg, #97b888 0%, #6b8e60 100%);
            --bg-cream: #f9f8f3;
            --card-bg: #ffffff;
            --text-main: #2d312e;
            --text-muted: #71776e;
            --chip-green: #e9edc9;
        }

        * {
            font-family: 'Cairo', sans-serif;
            box-sizing: border-box;
        }

        body {
            background-color: var(--bg-cream);
            color: var(--text-main);
            min-height: 100vh;
        }

        .main-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        /* كروت الجداول */
        .cart-card {
            background-color: var(--card-bg);
            border-radius: 24px;
            border: 1px solid #eae8df;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
            overflow: hidden;
            margin-bottom: 25px;
        }

        /* الجدول العصري */
        .custom-table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

        .custom-table thead {
            background: var(--green-sage-gradient);
            color: #ffffff;
        }

        .custom-table th {
            padding: 16px;
            font-size: 0.95rem;
            font-weight: 800;
        }

        .custom-table td {
            padding: 18px 16px;
            border-bottom: 1px solid #f0efe9;
            vertical-align: middle;
        }

        .custom-table tr:last-child td {
            border-bottom: none;
        }

        /* أزرار التحكم بالكمية */
        .qty-btn {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 1px solid #dcd8c8;
            background-color: #ffffff;
            color: var(--text-main);
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .qty-btn:hover {
            background-color: var(--orange-primary);
            color: #ffffff;
            border-color: var(--orange-primary);
        }

        /* أزرار الإجراءات */
        .btn-checkout {
            background: var(--orange-gradient);
            color: #ffffff;
            border: none;
            border-radius: 50px;
            padding: 12px 36px;
            font-weight: 800;
            font-size: 1.05rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 6px 20px rgba(232, 93, 4, 0.28);
            transition: all 0.25s ease;
            text-decoration: none;
        }

        .btn-checkout:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(232, 93, 4, 0.38);
            color: #ffffff;
        }

        .btn-outline-custom {
            border: 2px solid #e0ddd0;
            background: #ffffff;
            color: var(--text-main);
            border-radius: 50px;
            padding: 8px 20px;
            font-weight: 700;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-outline-custom:hover {
            border-color: var(--orange-primary);
            color: var(--orange-primary);
        }

        .btn-delete-item {
            color: #ef4444;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 50px;
            padding: 6px 14px;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-delete-item:hover {
            background: #ef4444;
            color: #ffffff;
        }

        /* التنبيهات */
        .alert-box {
            padding: 14px 20px;
            border-radius: 16px;
            margin-bottom: 25px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .alert-success-custom {
            background-color: #e6f9f0;
            border: 1px solid #b7f0d3;
            color: #15573f;
        }

        .alert-error-custom {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .empty-cart-card {
            background-color: #ffffff;
            border-radius: 24px;
            border: 1px dashed #e0ddd0;
            padding: 50px 20px;
            text-align: center;
        }
    </style>
</head>

<body>

    <!-- 1. شريط الملاحة الموحد للموقع -->
    @include('navigation-menu')

    <div class="main-container">

        <!-- الترويسة العليا -->
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <h3 class="text-2xl font-black text-gray-800 m-0 flex items-center gap-2">
                <i class="fa-solid fa-cart-shopping text-amber-500"></i>
                المنتجات المختارة
            </h3>
            <a href="{{ route('customer.menu') }}" class="btn-outline-custom">
                <i class="fa-solid fa-plus"></i> إضافة المزيد من المنتجات
            </a>
        </div>

        {{-- رسائل التنبيه والنجاح/الخطأ --}}
        @if(session('success'))
        <div class="alert-box alert-success-custom">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check fs-5"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-gray-500 text-xl cursor-pointer">&times;</button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert-box alert-error-custom">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation fs-5"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-gray-500 text-xl cursor-pointer">&times;</button>
        </div>
        @endif

        @if(isset($order) && $order->items && count($order->items) > 0)

        <!-- جدول المنتجات المختارة -->
        <div class="cart-card">
            <div class="overflow-x-auto">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th class="text-right pr-6">المنتج</th>
                            <th>السعر الفردي</th>
                            <th>الكمية</th>
                            <th>الإجمالي الفرعي</th>
                            <th>إجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr id="item-row-{{ $item->id }}">
                            <td class="font-bold text-right pr-6 text-gray-800">
                                {{ $item->itemable->name ?? $item->product_name ?? 'منتج #' . $item->id }}
                            </td>

                            <td class="font-semibold text-gray-600">{{ number_format($item->price, 2) }} ج.م</td>

                            <td>
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" class="qty-btn" onclick="changeQuantity({{ $item->id }}, 'decrease')">-</button>
                                    <span id="qty-{{ $item->id }}" class="mx-2 font-extrabold text-gray-800 text-base">{{ $item->quantity }}</span>
                                    <button type="button" class="qty-btn" onclick="changeQuantity({{ $item->id }}, 'increase')">+</button>
                                </div>
                            </td>

                            <td class="font-extrabold text-emerald-700">
                                <span id="item-total-{{ $item->id }}">{{ number_format($item->price * $item->quantity, 2) }}</span> ج.م
                            </td>

                            <td>
                                <form action="{{ route('order-items.destroy', $item->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج من السلة؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete-item">
                                        <i class="fa-solid fa-trash me-1"></i> حذف
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- أسفل الصفحة: الإجمالي الكلي وأزرار الحركة -->
        <div class="cart-card p-6">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4 border-b border-gray-100 pb-6">
                <div>
                    <h4 class="text-xl font-extrabold text-gray-800 m-0 mb-1">إجمالي الحساب الكلي:</h4>
                    <p class="text-gray-500 m-0 text-sm font-semibold">مجموع أسعار المنتجات المحددة أعلاه</p>
                </div>
                <div class="text-left">
                    <span class="text-3xl font-black text-emerald-600" id="order-total-price">{{ number_format($order->total_price, 2) }}</span>
                    <span class="text-xl text-emerald-600 font-extrabold">ج.م</span>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">

                <!-- زر تفريغ السلة -->
                <form action="{{ route('orders.clear', $order->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف جميع العناصر وإلغاء هذا الطلب؟');" class="w-full sm:w-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete-item w-full py-3 px-6 text-sm font-bold">
                        <i class="fa-solid fa-trash-can me-2"></i>حذف كل العناصر
                    </button>
                </form>

                <!-- زر الانتقال لصفحة الدفع -->
                <a href="{{ route('customer.checkout', $order->id) }}" class="btn-checkout w-full sm:w-auto justify-center">
                    <i class="fa-solid fa-credit-card"></i>
                    <span>إتمام الدفع لكافة المنتجات</span>
                </a>
            </div>
        </div>

        @else
        <!-- في حالة كانت السلة فارغة -->
        <div class="empty-cart-card">
            <i class="fa-solid fa-cart-flatbed-empty text-5xl text-gray-400 mb-4"></i>
            <h4 class="text-xl font-bold text-gray-700 mb-2">السلة فارغة، لم تقم باختيار أي منتجات بعد</h4>
            <p class="text-gray-500 mb-6 text-sm">تصفح قائمتنا اللذيذة واستكشف أفضل الوجبات والمشروبات!</p>
            <a href="{{ route('customer.menu') }}" class="btn-checkout inline-flex">
                <i class="fa-solid fa-utensils"></i>
                <span>التوجه للمنيو وإضافة منتجات</span>
            </a>
        </div>
        @endif

    </div>
@include('components.footer')
    @livewireScripts

    <!-- Script جافاسكريبت المعتمد لضبط الكميات بشكل تفاعلي -->
    <script>
        function changeQuantity(itemId, action) {
            fetch(`/order-items/${itemId}/quantity`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        action: action
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.order_deleted) {
                            location.reload();
                        } else if (data.item_deleted) {
                            const row = document.getElementById(`item-row-${itemId}`);
                            if (row) row.remove();
                            document.getElementById('order-total-price').innerText = data.order_total;
                        } else {
                            document.getElementById(`qty-${itemId}`).innerText = data.quantity;
                            document.getElementById(`item-total-${itemId}`).innerText = data.item_total;
                            document.getElementById('order-total-price').innerText = data.order_total;
                        }
                    } else {
                        alert(data.message || 'حدث خطأ أثناء تعديل الكمية.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ في الاتصال بالسيرفر.');
                });
        }
    </script>
</body>

</html>