<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ضبط تفضيلاتي - الكافتيريا الذكية</title>
    
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
            max-width: 800px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .pref-card {
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
            padding: 22px 28px;
        }

        .form-label-custom {
            display: block;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 8px;
            font-size: 0.98rem;
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

        .btn-submit-custom {
            background: var(--orange-gradient);
            color: #ffffff;
            border: none;
            border-radius: 50px;
            padding: 14px 36px;
            font-weight: 800;
            font-size: 1.05rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            box-shadow: 0 6px 20px rgba(232, 93, 4, 0.28);
            transition: all 0.25s ease;
        }

        .btn-submit-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(232, 93, 4, 0.38);
            color: #ffffff;
        }

        .alert-success-custom {
            background-color: #e6f9f0;
            border: 1px solid #b7f0d3;
            color: #15573f;
            padding: 14px 20px;
            border-radius: 16px;
            margin-bottom: 25px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
    </style>
</head>
<body>

    <!-- 1. شريط الملاحة الموحد للموقع -->
    @include('navigation-menu')

    <div class="main-container">

        <!-- الترويسة العليا -->
        <div class="mb-6">
            <h3 class="text-2xl font-black text-gray-800 m-0 flex items-center gap-2">
                <i class="fa-solid fa-sliders text-amber-500"></i>
                ضبط تفضيلاتي
            </h3>
            <p class="text-gray-500 text-sm font-semibold mt-1 m-0">ساعدنا في تخصيص اقتراحات الطعام والمشروبات لتناسب ذوقك تماماً</p>
        </div>

        {{-- رسائل التنبيه والنجاح --}}
        @if(session('success'))
        <div class="alert-success-custom">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check fs-5"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-gray-500 text-xl cursor-pointer">&times;</button>
        </div>
        @endif

        <div class="pref-card">
            <div class="card-header-sage flex items-center gap-3">
                <div class="bg-white/20 backdrop-blur-sm p-3 rounded-full flex items-center justify-center text-xl">
                    ⚙️
                </div>
                <div>
                    <h4 class="text-xl font-extrabold m-0">تفضيلات الطعام والمشروبات</h4>
                    <small class="opacity-90 font-semibold text-xs">سيستخدم الذكاء الاصطناعي هذه التفضيلات لترشيح أفضل الأطباق لك</small>
                </div>
            </div>

            <div class="p-8">
                <form action="{{ route('customer.preferences.update') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- مستوى الحار -->
                    <div>
                        <label for="spicy_level" class="form-label-custom flex items-center justify-between">
                            <span><i class="fa-solid fa-pepper-hot text-red-500 me-2"></i>مستوى الحار المفضل (0 - 5):</span>
                            <span class="text-xs text-gray-400 font-normal">0 = غير حار | 5 = حار جداً</span>
                        </label>
                        <input type="number" id="spicy_level" name="spicy_level" min="0" max="5" 
                               value="{{ old('spicy_level', $preference->spicy_level ?? 0) }}" 
                               class="form-input-custom" required>
                    </div>

                    <!-- السعر المفضل -->
                    <div>
                        <label for="price_preference" class="form-label-custom">
                            <i class="fa-solid fa-coins text-amber-500 me-2"></i>متوسط السعر المفضل (ج.م):
                        </label>
                        <input type="number" step="0.01" id="price_preference" name="price_preference" 
                               value="{{ old('price_preference', $preference->price_preference ?? 100) }}" 
                               class="form-input-custom" required>
                    </div>

                    <!-- المذاق المفضل -->
                    <div>
                        <label for="preferred_taste" class="form-label-custom">
                            <i class="fa-solid fa-utensils text-emerald-600 me-2"></i>المذاق المفضل (اختياري):
                        </label>
                        <input type="text" id="preferred_taste" name="preferred_taste" placeholder="مثال: حار وجبن، حلو، مشويات" 
                               value="{{ old('preferred_taste', $preference->preferred_taste ?? '') }}" 
                               class="form-input-custom">
                    </div>

                    <button type="submit" class="btn-submit-custom mt-4">
                        <i class="fa-solid fa-floppy-disk text-lg"></i>
                        <span>حفظ التفضيلات</span>
                    </button>
                </form>
            </div>
        </div>

    </div>
    @include('components.footer')
    @livewireScripts
</body>
</html>