<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>الكافتيريا الذكية - الصفحة الرئيسية</title>
    
    <!-- Google Fonts: Cairo -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS & Livewire -->
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
        }

        /* قسم الهيرو */
        .hero-section {
            padding: 70px 20px;
            text-align: center;
            background: linear-gradient(180deg, #ffffff 0%, var(--bg-cream) 100%);
            border-bottom: 1px solid #eae8df;
        }

        .welcome-badge {
            background-color: var(--chip-green);
            color: #3b5233;
            padding: 8px 22px;
            border-radius: 50px;
            font-weight: 800;
            font-size: 0.92rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        }

        .btn-primary-hero {
            background: var(--orange-gradient);
            color: #ffffff;
            border-radius: 50px;
            padding: 14px 38px;
            font-weight: 800;
            font-size: 1.1rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 8px 25px rgba(232, 93, 4, 0.28);
            transition: all 0.3s ease;
        }

        .btn-primary-hero:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(232, 93, 4, 0.38);
            color: #ffffff;
        }

        .btn-secondary-hero {
            background: #ffffff;
            color: var(--text-main);
            border: 2px solid #e0ddd0;
            border-radius: 50px;
            padding: 14px 34px;
            font-weight: 800;
            font-size: 1.1rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .btn-secondary-hero:hover {
            border-color: var(--green-sage);
            color: #4a6341;
            transform: translateY(-3px);
            background-color: #fcfdfa;
        }

        /* كروت المميزات */
        .feature-card {
            background-color: var(--card-bg);
            border-radius: 28px;
            border: 1px solid #eae8df;
            padding: 32px 26px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 18px 36px rgba(0, 0, 0, 0.06);
            border-color: #dcd8c8;
        }

        .feature-icon {
            width: 65px;
            height: 65px;
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 22px;
        }

        .icon-orange {
            background-color: #fff3eb;
            color: var(--orange-primary);
        }

        .icon-green {
            background-color: #f0f7ec;
            color: #4a6341;
        }

        .icon-purple {
            background-color: #f3e8ff;
            color: #7e22ce;
        }

        .icon-amber {
            background-color: #fefce8;
            color: #d97706;
        }
    </style>
</head>
<body>

    <!-- 1. شريط الملاحة الموحد للموقع -->
    @include('navigation-menu')

    <!-- 2. قسم الهيرو الواجهة الرئيسية -->
    <div class="hero-section">
        <div class="max-w-4xl mx-auto px-4">
            <span class="welcome-badge">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
                تجربة تناول طعام ذكية ومخصصة لك بالكامل
            </span>

            <h1 class="text-4xl md:text-5xl font-black text-gray-800 leading-tight mb-6">
                مرحباً بك في <span class="text-orange-500">كافيه</span>
            </h1>

            <p class="text-lg md:text-xl text-gray-600 font-semibold mb-8 max-w-2xl mx-auto leading-relaxed">
                اكتشف أشهى الوجبات والمشروبات الطازجة، واستمتع بتوصيات ذكية تناسب ذوقك وتفضيلاتك الشخصية باستخدام تقنيات الذكاء الاصطناعي.
            </p>

            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('customer.menu') }}" class="btn-primary-hero">
                    <i class="fa-solid fa-utensils"></i>
                    <span>استكشف قائمة الطعام</span>
                </a>

                <a href="{{ route('customer.recommendations') }}" class="btn-secondary-hero">
                    <i class="fa-solid fa-sparkles text-amber-500"></i>
                    <span>التوصيات الذكية</span>
                </a>

                <a href="{{ route('customer.chatbot') }}" class="btn-secondary-hero">
                    <i class="fa-solid fa-robot text-emerald-600"></i>
                    <span>المساعد الذكي</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 3. شبكة خدمات واقسام الموقع الرئيسية -->
    <div class="max-w-6xl mx-auto px-4 py-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-black text-gray-800 mb-2">كيف نوفر لك تجربة استثنائية؟</h2>
            <p class="text-gray-500 font-semibold text-base">كل ما تحتاجه لطلب وجبتك المفضلة بسرعة وسهولة</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- كارت المنيو -->
            <div class="feature-card">
                <div>
                    <div class="feature-icon icon-orange">
                        <i class="fa-solid fa-burger"></i>
                    </div>
                    <h3 class="text-xl font-extrabold text-gray-800 mb-3">قائمة طعام متنوعة</h3>
                    <p class="text-gray-500 font-semibold text-sm leading-relaxed mb-6">
                        تصفح الأطعمة والمشروبات المصنفة حسب الأقسام، وأضف وجباتك إلى السلة بضغطة واحدة.
                    </p>
                </div>
                <a href="{{ route('customer.menu') }}" class="text-orange-500 font-black text-sm flex items-center gap-2 hover:gap-3 transition-all">
                    <span>تصفح المنيو الآن</span>
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
            </div>

            <!-- كارت التوصيات الذكية -->
            <div class="feature-card">
                <div>
                    <div class="feature-icon icon-green">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                    </div>
                    <h3 class="text-xl font-extrabold text-gray-800 mb-3">توصيات مخصصة لك</h3>
                    <p class="text-gray-500 font-semibold text-sm leading-relaxed mb-6">
                        يقوم النظام بتحليل المذاق ومستوى الحار وميزانيتك المفضلة لتقديم ترشيحات أطباق مناسبة لك.
                    </p>
                </div>
                <a href="{{ route('customer.recommendations') }}" class="text-emerald-700 font-black text-sm flex items-center gap-2 hover:gap-3 transition-all">
                    <span>عرض التوصيات</span>
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
            </div>

            <!-- كارت الشات بوت -->
            <div class="feature-card">
                <div>
                    <div class="feature-icon icon-purple">
                        <i class="fa-solid fa-robot"></i>
                    </div>
                    <h3 class="text-xl font-extrabold text-gray-800 mb-3">مساعد الشات بوت</h3>
                    <p class="text-gray-500 font-semibold text-sm leading-relaxed mb-6">
                        مساعدك التفاعلي الفوري للإجابة عن مكونات الوجبات، المشروبات المتاحة، واستفسارات المنيو.
                    </p>
                </div>
                <a href="{{ route('customer.chatbot') }}" class="text-purple-600 font-black text-sm flex items-center gap-2 hover:gap-3 transition-all">
                    <span>تحدث مع المساعد</span>
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
            </div>

            <!-- كارت ضبط التفضيلات -->
            <div class="feature-card">
                <div>
                    <div class="feature-icon icon-amber">
                        <i class="fa-solid fa-sliders"></i>
                    </div>
                    <h3 class="text-xl font-extrabold text-gray-800 mb-3">ضبط التفضيلات</h3>
                    <p class="text-gray-500 font-semibold text-sm leading-relaxed mb-6">
                        حدد مستويات الحرارة والمذاق والميزانية المفضلة لديك لضمان تجربة طلب دقيقة وخاصة بك.
                    </p>
                </div>
                <a href="{{ route('customer.preferences') }}" class="text-amber-600 font-black text-sm flex items-center gap-2 hover:gap-3 transition-all">
                    <span>تعديل التفضيلات</span>
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
            </div>

        </div>
    </div>
@include('components.footer')
    @livewireScripts
</body>
</html>