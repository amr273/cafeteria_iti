<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التوصيات الذكية</title>
    <!-- Bootstrap 5 RTL CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card-product { border: none; border-radius: 12px; transition: transform 0.2s; }
        .card-product:hover { transform: translateY(-5px); }
    </style>
</head>
<body>

    <!-- شريط الملاحة -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">☕ الكافتيريا الذكية</a>
            <div class="d-flex gap-2">
                <a href="{{ route('customer.menu') }}" class="btn btn-outline-light btn-sm">
                    <i class="fa-solid fa-utensils me-1"></i> القائمة
                </a>
                <a href="{{ route('customer.orders') }}" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-cart-shopping me-1"></i> طلباتي والسلة
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-3">

        <!-- الترويسة -->
        <div class="card border-0 shadow-sm rounded-3 p-4 bg-white mb-4">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-circle fs-3">
                    ✨
                </div>
                <div>
                    <h3 class="fw-bold mb-1">اقتراحات الذكاء الاصطناعي بناءً على تفضيلاتك</h3>
                    <p class="text-muted mb-0">قمنا بتحليل اختياراتك لتجهيز هذه القائمة المخصصة لك اليوم!</p>
                </div>
            </div>
        </div>

        {{-- رسائل التنبيه --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- قسم وجبات الطعام المقترحة -->
        <h4 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-burger text-warning me-2"></i>الوجبات المقترحة لك</h4>
        <div class="row g-4 mb-5">
            @forelse($recommendedFoods as $food)
                <div class="col-md-4">
                    <div class="card card-product shadow-sm h-100 p-3">
                        <div class="card-body d-flex flex-column justify-content-between p-2">
                            <div>
                                <h5 class="fw-bold mb-2">{{ $food->name }}</h5>
                                <p class="text-muted small mb-3">{{ $food->description ?? 'وجبة مميزة ومجهزة خصيصاً بجودة عالية.' }}</p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                                <span class="fw-bold text-success fs-5">{{ number_format($food->price, 2) }} ج.م</span>

                                <form action="{{ route('customer.orders.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="item_id" value="{{ $food->id }}">
                                    <input type="hidden" name="item_type" value="food">
                                    <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                        <i class="fa-solid fa-plus me-1"></i> إضافة للسلة
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12"><p class="text-muted">لا توجد أطعمة مقترحة حالياً.</p></div>
            @endforelse
        </div>

        <!-- قسم المشروبات المقترحة -->
        <h4 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-mug-hot text-primary me-2"></i>المشروبات الموصى بها</h4>
        <div class="row g-4 mb-4">
            @forelse($recommendedBeverages as $beverage)
                <div class="col-md-4">
                    <div class="card card-product shadow-sm h-100 p-3">
                        <div class="card-body d-flex flex-column justify-content-between p-2">
                            <div>
                                <h5 class="fw-bold mb-2">{{ $beverage->name }}</h5>
                                <p class="text-muted small mb-3">{{ $beverage->description ?? 'مشروب منعش ومثالي مع وجبتك.' }}</p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                                <span class="fw-bold text-success fs-5">{{ number_format($beverage->price, 2) }} ج.م</span>

                                <form action="{{ route('customer.orders.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="item_id" value="{{ $beverage->id }}">
                                    <input type="hidden" name="item_type" value="beverage">
                                    <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                        <i class="fa-solid fa-plus me-1"></i> إضافة للسلة
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12"><p class="text-muted">لا توجد مشروبات مقترحة حالياً.</p></div>
            @endforelse
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
