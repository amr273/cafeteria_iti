<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلباتي وسلة التسوق</title>
    <!-- Bootstrap 5 RTL CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
</head>

<body>

    <!-- شريط الملاحة العلوي -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">☕ الكافتيريا الذكية</a>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('customer.menu') }}" class="btn btn-outline-light btn-sm">
                    <i class="fa-solid fa-utensils me-1"></i> قائمة الطعام
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-3">

        <!-- الترويسة -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold m-0"><i class="fa-solid fa-cart-shopping text-primary me-2"></i>المنتجات المختارة</h3>
            <a href="{{ route('customer.menu') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-plus me-1"></i> إضافة المزيد من المنتجات
            </a>
        </div>

        {{-- رسائل التنبيه والنجاح/الخطأ --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(isset($order) && $order->items && count($order->items) > 0)

        <!-- جدول المنتجات المختارة -->
        <div class="card shadow-sm border-0 mb-4 rounded-3 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col" class="py-3 text-start ps-4">المنتج</th>
                                <th scope="col" class="py-3">السعر الفردي</th>
                                <th scope="col" class="py-3">الكمية</th>
                                <th scope="col" class="py-3">الإجمالي الفرعي</th>
                                <th scope="col" class="py-3">إجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr id="item-row-{{ $item->id }}">
                                <td class="fw-bold text-start ps-4">
                                    {{ $item->itemable->name ?? $item->product_name ?? 'منتج #' . $item->id }}
                                </td>

                                <td>{{ number_format($item->price, 2) }} ج.م</td>

                                <td>
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <button type="button" class="btn btn-outline-secondary btn-sm px-3 fw-bold" onclick="changeQuantity({{ $item->id }}, 'decrease')">-</button>
                                        <span id="qty-{{ $item->id }}" class="mx-2 fw-bold fs-6">{{ $item->quantity }}</span>
                                        <button type="button" class="btn btn-outline-secondary btn-sm px-3 fw-bold" onclick="changeQuantity({{ $item->id }}, 'increase')">+</button>
                                    </div>
                                </td>

                                <td class="fw-bold text-primary">
                                    <span id="item-total-{{ $item->id }}">{{ number_format($item->price * $item->quantity, 2) }}</span> ج.م
                                </td>

                                <td>
                                    <form action="{{ route('order-items.destroy', $item->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج من السلة؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
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
        </div>

        <!-- أسفل الصفحة: الإجمالي الكلي وأزرار الحركة -->
        <div class="card shadow-sm border-0 p-4 rounded-3 bg-white">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                <div>
                    <h4 class="mb-1 fw-bold">إجمالي الحساب الكلي:</h4>
                    <p class="text-muted mb-0">مجموع أسعار المنتجات المحددة أعلاه</p>
                </div>
                <div class="text-md-end">
                    <span class="fs-2 fw-bold text-success" id="order-total-price">{{ number_format($order->total_price, 2) }}</span>
                    <span class="fs-4 text-success fw-bold">ج.م</span>
                </div>
            </div>

            <hr class="my-3">

            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 mt-2">

                <!-- زر حذف جميع العناصر (تفريغ السلة) -->
                <form action="{{ route('orders.clear', $order->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف جميع العناصر وإلغاء هذا الطلب؟');" class="w-100 w-sm-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-100 py-2 fw-bold">
                        <i class="fa-solid fa-trash-can me-2"></i>حذف كل العناصر
                    </button>
                </form>

                <!-- زر الانتقال لصفحة الدفع -->
                <a href="{{ route('customer.checkout', $order->id) }}" class="btn btn-success btn-lg px-5 py-2 fw-bold shadow-sm">
                    <i class="fa-solid fa-credit-card me-2"></i> إتمام الدفع لكافة المنتجات
                </a>
            </div>
        </div>

        @else
        <!-- في حالة كانت السلة فارغة -->
        <div class="text-center py-5 card shadow-sm border-0 rounded-3">
            <div class="card-body">
                <i class="fa-solid fa-cart-flatbed-empty fs-1 text-muted mb-3"></i>
                <h4 class="text-muted mb-3">السلة فارغة، لم تقم باختيار أي منتجات بعد</h4>
                <a href="{{ route('customer.menu') }}" class="btn btn-primary px-4 py-2 fw-bold">
                    <i class="fa-solid fa-utensils me-2"></i>التوجه للمنيو وإضافة منتجات
                </a>
            </div>
        </div>
        @endif

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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