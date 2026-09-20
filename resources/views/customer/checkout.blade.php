<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إتمام عملية الدفع</title>
    <!-- Bootstrap 5 RTL CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card-custom { border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">☕ الكافتيريا الذكية</a>
            <a href="{{ route('customer.orders') }}" class="btn btn-outline-light btn-sm">
                <i class="fa-solid fa-arrow-right me-1"></i> العودة للسلة
            </a>
        </div>
    </nav>

    <div class="container py-3">
        <div class="row g-4">

            <!-- ملخص الطلب في الجانب -->
            <div class="col-md-5 order-md-2 mb-4">
                <div class="card card-custom p-3 bg-white">
                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-primary fw-bold">ملخص الفاتورة</span>
                        <span class="badge bg-primary rounded-pill">{{ count($order->items) }}</span>
                    </h4>
                    <ul class="list-group mb-3 list-group-flush">
                        @foreach($order->items as $item)
                            <li class="list-group-item d-flex justify-content-between lh-sm px-0">
                                <div>
                                    <h6 class="my-0">{{ $item->itemable->name ?? 'منتج #' . $item->id }}</h6>
                                    <small class="text-muted">الكمية: {{ $item->quantity }}</small>
                                </div>
                                <span class="text-muted">{{ number_format($item->price * $item->quantity, 2) }} ج.م</span>
                            </li>
                        @endforeach
                        <li class="list-group-item d-flex justify-content-between px-0 fw-bold fs-5 pt-3 border-top">
                            <span>الإجمالي الكلي:</span>
                            <span class="text-success">{{ number_format($order->total_price, 2) }} ج.م</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- استمارة أدخال البيانات والدفع -->
            <div class="col-md-7 order-md-1">
                <div class="card card-custom p-4 bg-white">
                    <h4 class="mb-4 fw-bold"><i class="fa-solid fa-credit-card text-success me-2"></i>تفاصيل الدفع والتسليم</h4>

                    <form action="{{ route('customer.checkout.process', $order->id) }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            <div class="col-12">
                                <label for="name" class="form-label fw-bold">الاسم بالكامل</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ auth()->user()->name ?? '' }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-bold">رقم الهاتف</label>
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="01xxxxxxxxx" required>
                            </div>

                            <div class="col-md-6">
                                <label for="table_number" class="form-label fw-bold">رقم الطاولة / المكان</label>
                                <input type="text" class="form-control" id="table_number" name="table_number" placeholder="مثال: طاولة 5" required>
                            </div>

                            <div class="col-12">
                                <label for="notes" class="form-label fw-bold">ملاحظات إضافية (اختياري)</label>
                                <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="أي إضافات أو تعليمات خاصة بالطلب..."></textarea>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3 fw-bold">طريقة الدفع</h5>

                        <div class="my-3">
                            <div class="form-check mb-2">
                                <input id="cash" name="payment_method" type="radio" class="form-check-input" value="cash" checked onclick="toggleCardFields(false)">
                                <label class="form-check-label fw-bold" for="cash">
                                    <i class="fa-solid fa-money-bill-wave text-success me-1"></i> دفع نقدي (كاش عند الاستلام)
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input id="credit" name="payment_method" type="radio" class="form-check-input" value="card" onclick="toggleCardFields(true)">
                                <label class="form-check-label fw-bold" for="credit">
                                    <i class="fa-solid fa-credit-card text-primary me-1"></i> بطاقة ائتمان / فيزا
                                </label>
                            </div>
                        </div>

                        <!-- حقول الفيزا (تظهر عند اختيار الدفع الإلكتروني) -->
                        <div id="card-details" class="row g-3 mt-2 p-3 border rounded bg-light" style="display: none;">
                            <div class="col-md-6">
                                <label for="cc-name" class="form-label">الاسم على البطاقة</label>
                                <input type="text" class="form-control" id="cc-name" name="card_name" placeholder="Name on card">
                            </div>

                            <div class="col-md-6">
                                <label for="cc-number" class="form-label">رقم البطاقة</label>
                                <input type="text" class="form-control" id="cc-number" name="card_number" placeholder="xxxx xxxx xxxx xxxx">
                            </div>

                            <div class="col-md-6">
                                <label for="cc-expiration" class="form-label">تاريخ الانتهاء</label>
                                <input type="text" class="form-control" id="cc-expiration" name="card_expiry" placeholder="MM/YY">
                            </div>

                            <div class="col-md-6">
                                <label for="cc-cvv" class="form-label">رمز الأمان (CVV)</label>
                                <input type="text" class="form-control" id="cc-cvv" name="card_cvv" placeholder="123">
                            </div>
                        </div>

                        <hr class="my-4">

                        <button class="w-100 btn btn-success btn-lg fw-bold shadow-sm" type="submit">
                            <i class="fa-solid fa-check-circle me-2"></i>تأكيد وإتمام الطلب الآن ({{ number_format($order->total_price, 2) }} ج.م)
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        function toggleCardFields(show) {
            document.getElementById('card-details').style.display = show ? 'flex' : 'none';
        }
    </script>
</body>
</html>
