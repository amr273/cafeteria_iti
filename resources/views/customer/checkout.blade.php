<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>إتمام عملية الدفع - الكافتيريا الذكية</title>

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

    /* كروت التصميم */
    .checkout-card {
      background-color: var(--card-bg);
      border-radius: 24px;
      border: 1px solid #eae8df;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
      overflow: hidden;
      margin-bottom: 25px;
    }

    .card-header-sage {
      background: var(--green-sage-gradient);
      color: #ffffff;
      padding: 20px 24px;
    }

    /* مدخلات الاستمارة */
    .form-label-custom {
      display: block;
      font-weight: 800;
      color: var(--text-main);
      margin-bottom: 6px;
      font-size: 0.95rem;
    }

    .form-input-custom {
      width: 100%;
      border: 2px solid #eee2d5;
      border-radius: 14px;
      padding: 12px 18px;
      font-size: 0.95rem;
      background-color: #faf9f5;
      outline: none;
      transition: all 0.2s ease;
    }

    .form-input-custom:focus {
      background-color: #ffffff;
      border-color: var(--orange-primary);
      box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.12);
    }

    /* بطاقات طريقة الدفع */
    .payment-option-card {
      border: 2px solid #eee2d5;
      border-radius: 16px;
      padding: 14px 18px;
      background-color: #faf9f5;
      cursor: pointer;
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .payment-option-card:hover {
      border-color: var(--orange-primary);
      background-color: #ffffff;
    }

    .payment-option-card input[type="radio"]:checked+label {
      color: var(--orange-primary);
      font-weight: 800;
    }

    /* زر التأكيد الرئيسي */
    .btn-submit-order {
      background: var(--orange-gradient);
      color: #ffffff;
      border: none;
      border-radius: 50px;
      padding: 14px 36px;
      font-weight: 800;
      font-size: 1.1rem;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      width: 100%;
      box-shadow: 0 6px 20px rgba(232, 93, 4, 0.28);
      transition: all 0.25s ease;
    }

    .btn-submit-order:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(232, 93, 4, 0.38);
      color: #ffffff;
    }

    .btn-back-custom {
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

    .btn-back-custom:hover {
      border-color: var(--orange-primary);
      color: var(--orange-primary);
    }

    .summary-item-row {
      padding: 12px 0;
      border-bottom: 1px solid #f0efe9;
    }

    .summary-item-row:last-child {
      border-bottom: none;
    }
  </style>
</head>

<body>

  <!-- 1. شريط الملاحة الموحد للموقع -->
  @include('navigation-menu')

  <div class="main-container">

    <!-- الترويسة العليا -->
    <div class="flex justify-between items-center mb-6">
      <h3 class="text-2xl font-black text-gray-800 m-0 flex items-center gap-2">
        <i class="fa-solid fa-credit-card text-emerald-600"></i>
        إتمام عملية الدفع
      </h3>
      <a href="{{ route('customer.orders') }}" class="btn-back-custom">
        <i class="fa-solid fa-arrow-right"></i> العودة للسلة
      </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

      <!-- استمارة أدخال البيانات والدفع (العمود الرئيسي) -->
      <div class="lg:col-span-7">
        <div class="checkout-card p-6">
          <h4 class="text-xl font-extrabold text-gray-800 mb-6 flex items-center gap-2 border-b border-gray-100 pb-4">
            <i class="fa-solid fa-user-check text-amber-500"></i>
            تفاصيل الدفع والتسليم
          </h4>

          <form action="{{ route('customer.checkout.process', $order->id) }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
              <div class="sm:col-span-2">
                <label for="name" class="form-label-custom">الاسم بالكامل</label>
                <input type="text" class="form-input-custom" id="name" name="name" value="{{ auth()->user()->name ?? '' }}" required>
              </div>

              <div>
                <label for="phone" class="form-label-custom">رقم الهاتف</label>
                <input type="text" class="form-input-custom" id="phone" name="phone" placeholder="01xxxxxxxxx" required>
              </div>

              <div>
                <label for="table_number" class="form-label-custom">رقم الطاولة / المكان</label>
                <input type="text" class="form-input-custom" id="table_number" name="table_number" placeholder="مثال: طاولة 5" required>
              </div>

              <div class="sm:col-span-2">
                <label for="delivery_address" class="form-label-custom">عنوان التوصيل (اختياري)</label>
                <textarea class="form-input-custom" id="delivery_address" name="delivery_address" rows="2" placeholder="أضف عنوان التوصيل أو تفاصيل الوصول إن وجد..."></textarea>
              </div>

              <div class="sm:col-span-2">
                <label for="notes" class="form-label-custom">ملاحظات إضافية (اختياري)</label>
                <textarea class="form-input-custom" id="notes" name="notes" rows="2" placeholder="أي إضافات أو تعليمات خاصة بالطلب..."></textarea>
              </div>
            </div>

            <hr class="my-6 border-gray-100">

            <h5 class="text-lg font-extrabold text-gray-800 mb-4">طريقة الدفع</h5>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
              <div class="payment-option-card">
                <input id="cash" name="payment_method" type="radio" value="cash" checked onclick="toggleCardFields(false)" class="accent-orange-500 w-4 h-4">
                <label class="cursor-pointer font-bold text-gray-800 flex items-center gap-2 m-0" for="cash">
                  <i class="fa-solid fa-money-bill-wave text-emerald-600 text-lg"></i>
                  <span>دفع نقدي (كاش)</span>
                </label>
              </div>

              <div class="payment-option-card">
                <input id="credit" name="payment_method" type="radio" value="card" onclick="toggleCardFields(true)" class="accent-orange-500 w-4 h-4">
                <label class="cursor-pointer font-bold text-gray-800 flex items-center gap-2 m-0" for="credit">
                  <i class="fa-solid fa-credit-card text-amber-500 text-lg"></i>
                  <span>بطاقة ائتمان / فيزا</span>
                </label>
              </div>
            </div>

            <!-- حقول الفيزا (تظهر عند اختيار الدفع الإلكتروني) -->
            <div id="card-details" class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4 p-4 border border-gray-200 rounded-2xl bg-white" style="display: none;">
              <div>
                <label for="cc-name" class="form-label-custom">الاسم على البطاقة</label>
                <input type="text" class="form-input-custom" id="cc-name" name="card_name" placeholder="Name on card">
              </div>

              <div>
                <label for="cc-number" class="form-label-custom">رقم البطاقة</label>
                <input type="text" class="form-input-custom" id="cc-number" name="card_number" placeholder="xxxx xxxx xxxx xxxx">
              </div>

              <div>
                <label for="cc-expiration" class="form-label-custom">تاريخ الانتهاء</label>
                <input type="text" class="form-input-custom" id="cc-expiration" name="card_expiry" placeholder="MM/YY">
              </div>

              <div>
                <label for="cc-cvv" class="form-label-custom">رمز الأمان (CVV)</label>
                <input type="text" class="form-input-custom" id="cc-cvv" name="card_cvv" placeholder="123">
              </div>
            </div>

            <hr class="my-6 border-gray-100">

            <button class="btn-submit-order" type="submit">
              <i class="fa-solid fa-circle-check text-xl"></i>
              <span>تأكيد وإتمام الطلب الآن ({{ number_format($order->total_price, 2) }} ج.م)</span>
            </button>
          </form>
        </div>
      </div>

      <!-- ملخص الفاتورة (العمود الجانبي) -->
      <div class="lg:col-span-5">
        <div class="checkout-card">
          <div class="card-header-sage flex justify-between items-center">
            <span class="text-lg font-extrabold m-0">ملخص الفاتورة</span>
            <span class="bg-white/20 backdrop-blur-sm text-white px-3 py-1 rounded-full text-xs font-black">
              {{ count($order->items) }} منتجات
            </span>
          </div>

          <div class="p-6">
            <div class="mb-4">
              @foreach($order->items as $item)
              <div class="summary-item-row flex justify-between items-center">
                <div>
                  <h6 class="font-bold text-gray-800 m-0 text-sm">{{ $item->itemable->name ?? 'منتج #' . $item->id }}</h6>
                  <small class="text-gray-500 font-semibold text-xs">الكمية: {{ $item->quantity }}</small>
                </div>
                <span class="font-extrabold text-gray-700 text-sm">{{ number_format($item->price * $item->quantity, 2) }} ج.م</span>
              </div>
              @endforeach
            </div>

            <div class="border-t border-gray-100 pt-4 flex justify-between items-center">
              <span class="text-lg font-extrabold text-gray-800">الإجمالي الكلي:</span>
              <span class="text-2xl font-black text-emerald-600">{{ number_format($order->total_price, 2) }} ج.م</span>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  @livewireScripts

  <script>
    function toggleCardFields(show) {
      document.getElementById('card-details').style.display = show ? 'grid' : 'none';
    }
  </script>
</body>

</html>