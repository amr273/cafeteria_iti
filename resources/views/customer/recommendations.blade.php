<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>التوصيات الذكية - الكافتيريا الذكية</title>

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
            --chip-orange: #ffe5ec;
        }

        * {
            font-family: 'Cairo', sans-serif;
            box-sizing: border-box;
        }

        body {
            background-color: var(--bg-cream);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
            flex: 1;
            width: 100%;
        }

        /* بنر الترويسة */
        .ai-header-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 28px 32px;
            border: 1px solid #eae8df;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
            margin-bottom: 35px;
            position: relative;
            overflow: hidden;
        }

        .ai-header-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 8px;
            height: 100%;
            background: var(--green-sage-gradient);
        }

        .ai-icon-box {
            width: 60px;
            height: 60px;
            background: var(--chip-green);
            color: #4a6341;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            flex-shrink: 0;
        }

        /* شبكة الكروت */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 45px;
        }

        .product-card {
            background-color: var(--card-bg);
            border-radius: 20px;
            border: 1px solid #eae8df;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.3s ease;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.02);
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.06);
            border-color: #dcd8c8;
        }

        .product-img-wrapper {
            width: 100%;
            height: 180px;
            background-color: #f3f1e7;
            overflow: hidden;
            position: relative;
        }

        .product-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .product-card:hover .product-img {
            transform: scale(1.06);
        }

        .badge-ai {
            position: absolute;
            top: 12px;
            right: 12px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(6px);
            color: #3b5233;
            font-size: 0.75rem;
            font-weight: 800;
            padding: 4px 12px;
            border-radius: 50px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .product-info {
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            justify-content: space-between;
        }

        .product-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 8px;
        }

        .product-desc {
            font-size: 0.88rem;
            color: var(--text-muted);
            line-height: 1.5;
            margin-bottom: 16px;
        }

        .product-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 14px;
            border-top: 1px solid #f0efe9;
        }

        .product-price {
            font-size: 1.2rem;
            font-weight: 900;
            color: #2b5329;
        }

        .btn-add-cart {
            background: var(--orange-gradient);
            color: #ffffff;
            border: none;
            border-radius: 50px;
            padding: 8px 18px;
            font-weight: 700;
            font-size: 0.88rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 12px rgba(232, 93, 4, 0.2);
            transition: all 0.25s ease;
        }

        .btn-add-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(232, 93, 4, 0.32);
            color: #ffffff;
        }

        /* التنبيهات */
        .custom-alert {
            background-color: #e6f9f0;
            border: 1px solid #b7f0d3;
            color: #15573f;
            border-radius: 16px;
            padding: 14px 20px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-weight: 700;
        }

        .empty-box {
            background: #ffffff;
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            color: var(--text-muted);
            border: 1px dashed #e0ddd0;
            grid-column: 1 / -1;
        }
    </style>
</head>
<body>

    <!-- 1. شريط التنقل الموحد للموقع -->
    @include('navigation-menu')

    <!-- 2. محتوى الصفحة الرئيسي -->
    <div class="main-container">

        <!-- الترويسة الرئيسية -->
        <div class="ai-header-card flex items-center gap-4">
            <div class="ai-icon-box">
                ✨
            </div>
            <div>
                <h3 class="text-2xl font-black text-gray-800 m-0 mb-1">اقتراحات الذكاء الاصطناعي بناءً على تفضيلاتك</h3>
                <p class="text-gray-500 m-0 font-semibold text-sm">قمنا بتحليل اختياراتك لتجهيز هذه القائمة المخصصة لك اليوم!</p>
            </div>
        </div>

        {{-- رسائل التنبيه عند إضافة منتج للسلة --}}
        @if(session('success'))
            <div class="custom-alert">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check fs-5 text-emerald-600"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-gray-500 hover:text-gray-800 text-lg">&times;</button>
            </div>
        @endif

        <!-- قسم وجبات الطعام المقترحة -->
        <div class="flex items-center gap-2 mb-4">
            <i class="fa-solid fa-burger text-amber-500 text-xl"></i>
            <h4 class="text-xl font-extrabold text-gray-800 m-0">الوجبات المقترحة لك</h4>
        </div>

        <div class="products-grid">
            @forelse($recommendedFoods as $food)
                <div class="product-card">
                    <div class="product-img-wrapper">
                        <span class="badge-ai"><i class="fa-solid fa-sparkles me-1"></i> موصى به</span>
                        <img src="{{ $food->image ? asset('storage/' . $food->image) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500' }}" alt="{{ $food->name }}" class="product-img">
                    </div>

                    <div class="product-info">
                        <div>
                            <h5 class="product-title">{{ $food->name }}</h5>
                            <p class="product-desc">{{ $food->description ?? 'وجبة مميزة ومجهزة خصيصاً بجودة عالية.' }}</p>
                        </div>

                        <div class="product-footer">
                            <span class="product-price">{{ number_format($food->price, 2) }} ج.م</span>

                            <form action="{{ route('customer.orders.store') }}" method="POST" class="m-0">
                                @csrf
                                <input type="hidden" name="item_id" value="{{ $food->id }}">
                                <input type="hidden" name="item_type" value="food">
                                <button type="submit" class="btn-add-cart">
                                    <i class="fa-solid fa-plus"></i>
                                    <span>إضافة للسلة</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-box">
                    <i class="fa-solid fa-utensils fs-3 mb-2 opacity-50"></i>
                    <p class="m-0 font-bold">لا توجد أطعمة مقترحة حالياً.</p>
                </div>
            @endforelse
        </div>

        <!-- قسم المشروبات المقترحة -->
        <div class="flex items-center gap-2 mb-4">
            <i class="fa-solid fa-mug-hot text-emerald-600 text-xl"></i>
            <h4 class="text-xl font-extrabold text-gray-800 m-0">المشروبات الموصى بها</h4>
        </div>

        <div class="products-grid">
            @forelse($recommendedBeverages as $beverage)
                <div class="product-card">
                    <div class="product-img-wrapper">
                        <span class="badge-ai"><i class="fa-solid fa-sparkles me-1"></i> موصى به</span>
                        <img src="{{ $beverage->image ? asset('storage/' . $beverage->image) : 'https://images.unsplash.com/photo-1517256064527-09c73fc73e38?w=500' }}" alt="{{ $beverage->name }}" class="product-img">
                    </div>

                    <div class="product-info">
                        <div>
                            <h5 class="product-title">{{ $beverage->name }}</h5>
                            <p class="product-desc">{{ $beverage->description ?? 'مشروب منعش ومثالي مع وجبتك.' }}</p>
                        </div>

                        <div class="product-footer">
                            <span class="product-price">{{ number_format($beverage->price, 2) }} ج.م</span>

                            <form action="{{ route('customer.orders.store') }}" method="POST" class="m-0">
                                @csrf
                                <input type="hidden" name="item_id" value="{{ $beverage->id }}">
                                <input type="hidden" name="item_type" value="beverage">
                                <button type="submit" class="btn-add-cart">
                                    <i class="fa-solid fa-plus"></i>
                                    <span>إضافة للسلة</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-box">
                    <i class="fa-solid fa-glass-water fs-3 mb-2 opacity-50"></i>
                    <p class="m-0 font-bold">لا توجد مشروبات مقترحة حالياً.</p>
                </div>
            @endforelse
        </div>

    </div>

    <!-- 3. استدعاء الـ Footer المخصص كـ Component -->
    @include('components.footer')

    @livewireScripts

</body>
</html>
