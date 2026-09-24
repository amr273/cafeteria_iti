<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-2xl text-gray-800 leading-tight flex items-center gap-2">
                لوحة التحكم والإدارة الشاملة
            </h2>
            <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1.5 rounded-full">النسخة المتقدمة v2.0</span>
        </div>
    </x-slot>

    <style>
        .dashboard-shell {
            background: linear-gradient(180deg, #f8fafc 0%, #eef4ff 100%);
            padding: 32px 0;
            min-height: 100vh;
            font-family: system-ui, -apple-system, sans-serif;
        }

        .dashboard-panel {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(148, 163, 184, 0.12);
            border-radius: 22px;
            box-shadow: 0 18px 45px -28px rgba(15, 23, 42, 0.25);
            backdrop-filter: blur(6px);
        }

        .dashboard-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(135deg, rgba(255, 255, 255, 1), rgba(248, 250, 252, 0.96));
            border: 1px solid rgba(148, 163, 184, 0.12);
            border-radius: 18px;
            box-shadow: 0 18px 38px -30px rgba(15, 23, 42, 0.38);
        }

        .dashboard-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 45px -25px rgba(15, 23, 42, 0.32);
        }

        .stat-card {
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.3), transparent 50%);
            pointer-events: none;
        }

        .stat-card .stat-icon {
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            box-shadow: inset 0 1px 2px rgba(255, 255, 255, 0.6);
        }

        .dashboard-table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            font-size: 14px;
            overflow: hidden;
        }

        .dashboard-table thead th {
            background: linear-gradient(180deg, #f8fafc 0%, #eef2ff 100%);
            color: #475569;
            font-weight: 800;
            letter-spacing: 0.02em;
        }

        .dashboard-table tbody tr {
            transition: background-color 0.2s ease;
        }

        .dashboard-table tbody tr:hover {
            background: rgba(99, 102, 241, 0.03);
        }

        .dashboard-btn {
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 700;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .dashboard-btn:hover {
            transform: translateY(-1px);
        }

        .dashboard-btn-primary {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #ffffff;
            box-shadow: 0 8px 18px -10px rgba(37, 99, 235, 0.7);
        }

        .dashboard-btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #ffffff;
            box-shadow: 0 8px 18px -10px rgba(239, 68, 68, 0.7);
        }

        .dashboard-btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #ffffff;
            box-shadow: 0 8px 18px -10px rgba(16, 185, 129, 0.7);
        }

        .dashboard-input,
        .dashboard-select {
            border: 1.5px solid #dbe3f0;
            border-radius: 10px;
            background: #ffffff;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .dashboard-input:focus,
        .dashboard-select:focus {
            outline: none;
            border-color: rgba(99, 102, 241, 0.75);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.12);
        }
    </style>

    <div class="dashboard-shell" style="padding: 32px 0; min-height: 100vh; font-family: system-ui, -apple-system, sans-serif;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="display: flex; flex-direction: column; gap: 28px;">

            {{-- رسالة النجاح --}}
            @if(session('success'))
            <div style="background: linear-gradient(135deg, #10b981, #059669); color: #ffffff; padding: 16px 20px; border-radius: 14px; font-weight: bold; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25); display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 20px;">✅</span> {{ session('success') }}
            </div>
            @endif

            {{-- رسائل الأخطاء --}}
            @if ($errors->any())
            <div style="background: linear-gradient(135deg, #ef4444, #dc2626); color: #ffffff; padding: 16px 20px; border-radius: 14px; font-weight: bold; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                    <span style="font-size: 20px;">⚠️</span> يرجى تصحيح الأخطاء التالية:
                </div>
                <ul style="list-style-type: disc; margin-right: 24px; font-weight: 500; font-size: 14px;">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @php
            $pendingOrders = $orders->where('status', 'pending')->count();
            $preparingOrders = $orders->where('status', 'preparing')->count();
            $readyOrders = $orders->where('status', 'ready')->count();
            $completedOrders = $orders->where('status', 'completed')->count();
            $processingOrders = $preparingOrders + $readyOrders;
            $totalFoods = $foods->count();
            $totalBeverages = $beverages->count();
            $totalCategories = $categories->count();
            $todayOrders = $orders->filter(function ($order) {
            return $order->created_at && $order->created_at->isToday();
            })->count();
            $todaySales = $orders->filter(function ($order) {
            return $order->created_at && $order->created_at->isToday();
            })->sum('total_price');
            @endphp

            <!-- 1. البطاقات الإحصائية سريعة القراءة -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

                <div class="dashboard-card stat-card stat-card-green" style="padding: 22px; border-right: 6px solid #10b981; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="color: #64748b; font-size: 13px; font-weight: 700; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">الإيرادات</p>
                        <h3 style="color: #0f172a; font-size: 26px; font-weight: 800; margin-top: 6px; margin-bottom: 0;">
                            {{ number_format($stats['total_sales'] ?? 0, 2) }} <span style="font-size: 14px; color: #64748b; font-weight: 600;">ج.م</span>
                        </h3>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #d1fae5, #a7f3d0); font-size: 26px;">💰</div>
                </div>

                <div class="dashboard-card stat-card stat-card-blue" style="padding: 22px; border-right: 6px solid #3b82f6; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="color: #64748b; font-size: 13px; font-weight: 700; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">إجمالي الطلبات</p>
                        <h3 style="color: #0f172a; font-size: 26px; font-weight: 800; margin-top: 6px; margin-bottom: 0;">{{ $stats['total_orders'] ?? 0 }}</h3>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #dbeafe, #bfdbfe); font-size: 26px;">🛍️</div>
                </div>

                <div class="dashboard-card stat-card stat-card-yellow" style="padding: 22px; border-right: 6px solid #f59e0b; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="color: #64748b; font-size: 13px; font-weight: 700; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">قيد التنفيذ</p>
                        <h3 style="color: #0f172a; font-size: 26px; font-weight: 800; margin-top: 6px; margin-bottom: 0;">{{ $processingOrders }}</h3>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #fef3c7, #fde68a); font-size: 26px;">⏳</div>
                </div>

                <div class="dashboard-card stat-card stat-card-purple" style="padding: 22px; border-right: 6px solid #8b5cf6; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="color: #64748b; font-size: 13px; font-weight: 700; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">العملاء</p>
                        <h3 style="color: #0f172a; font-size: 26px; font-weight: 800; margin-top: 6px; margin-bottom: 0;">{{ $stats['total_customers'] ?? 0 }}</h3>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #ede9fe, #ddd6fe); font-size: 26px;">👥</div>
                </div>

                <div class="dashboard-card stat-card stat-card-rose" style="padding: 22px; border-right: 6px solid #f43f5e; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="color: #64748b; font-size: 13px; font-weight: 700; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">الأطعمة</p>
                        <h3 style="color: #0f172a; font-size: 26px; font-weight: 800; margin-top: 6px; margin-bottom: 0;">{{ $totalFoods }}</h3>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #ffe4e6, #fecdd3); font-size: 26px;">🍔</div>
                </div>

                <div class="dashboard-card stat-card stat-card-cyan" style="padding: 22px; border-right: 6px solid #06b6d4; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="color: #64748b; font-size: 13px; font-weight: 700; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">المشروبات</p>
                        <h3 style="color: #0f172a; font-size: 26px; font-weight: 800; margin-top: 6px; margin-bottom: 0;">{{ $totalBeverages }}</h3>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #cffafe, #a5f3fc); font-size: 26px;">🥤</div>
                </div>

                <div class="dashboard-card stat-card stat-card-slate" style="padding: 22px; border-right: 6px solid #475569; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="color: #64748b; font-size: 13px; font-weight: 700; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">الأقسام</p>
                        <h3 style="color: #0f172a; font-size: 26px; font-weight: 800; margin-top: 6px; margin-bottom: 0;">{{ $totalCategories }}</h3>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #e2e8f0, #cbd5e1); font-size: 26px;">📂</div>
                </div>

                <div class="dashboard-card stat-card stat-card-indigo" style="padding: 22px; border-right: 6px solid #6366f1; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="color: #64748b; font-size: 13px; font-weight: 700; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">طلبات اليوم</p>
                        <h3 style="color: #0f172a; font-size: 26px; font-weight: 800; margin-top: 6px; margin-bottom: 0;">{{ $todayOrders }}</h3>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #e0e7ff, #c7d2fe); font-size: 26px;">📅</div>
                </div>

            </div>

            <!-- 2. نماذج الإضافة -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- إضافة قسم -->
                <div class="dashboard-panel" style="padding: 24px;">
                    <h3 style="color: #4f46e5; font-weight: 800; font-size: 18px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                        ➕ إضافة قسم جديد
                    </h3>
                    <form action="{{ route('admin.categories.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 18px;">
                        @csrf
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px;">اسم القسم</label>
                            <input type="text" name="name" style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 11px 14px; font-size: 14px; color: #0f172a; background-color: #f8fafc;" required placeholder="مثال: المشويات، الحلويات...">
                        </div>
                        <button type="submit" style="background: linear-gradient(135deg, #6366f1, #4f46e5); color: #ffffff; font-weight: 700; padding: 12px; border-radius: 10px; border: none; cursor: pointer; width: 100%; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);">
                            حفظ القسم الجديد
                        </button>
                    </form>
                </div>

                <!-- إضافة طعام -->
                <div class="dashboard-panel md:col-span-2" style="padding: 24px;">
                    <h3 style="color: #059669; font-weight: 800; font-size: 18px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                        🍔 إضافة طعام جديد مع الصورة
                    </h3>

                    <form action="{{ route('admin.foods.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @csrf
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px;">اسم الصنف</label>
                            <input type="text" name="name" style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 11px 14px; font-size: 14px; color: #0f172a; background-color: #f8fafc;" required placeholder="برجر كلاسيك">
                        </div>
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px;">القسم</label>
                            <select name="category_id" style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 11px 14px; font-size: 14px; color: #0f172a; background-color: #f8fafc;" required>
                                <option value="" disabled selected>اختر القسم</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px;">السعر (ج.م)</label>
                            <input type="number" step="0.01" name="price" style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 11px 14px; font-size: 14px; color: #0f172a; background-color: #f8fafc;" required placeholder="00.00">
                        </div>
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px;">مستوى الحار (0-5)</label>
                            <input type="number" name="spicy_level" value="0" min="0" max="5" style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 11px 14px; font-size: 14px; color: #0f172a; background-color: #f8fafc;" required>
                        </div>
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px;">الكمية المتاحة</label>
                            <input type="number" name="available_quantity" value="10" min="0" style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 11px 14px; font-size: 14px; color: #0f172a; background-color: #f8fafc;" required>
                        </div>

                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px;">صورة الصنف</label>
                            <input type="file" name="image" accept="image/*" style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 8px 12px; font-size: 13px; color: #0f172a; background-color: #f8fafc;">
                        </div>

                        <div class="sm:col-span-2">
                            <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px;">الوصف (اختياري)</label>
                            <input type="text" name="description" style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 11px 14px; font-size: 14px; color: #0f172a; background-color: #f8fafc;" placeholder="أضف وصفاً كاملاً للوجبة ومكوناتها...">
                        </div>

                        <div class="sm:col-span-2 mt-2">
                            <button type="submit" style="background: linear-gradient(135deg, #10b981, #059669); color: #ffffff; font-weight: 700; padding: 13px; border-radius: 10px; border: none; cursor: pointer; width: 100%; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);">
                                إضافة الطعام للقائمة
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 4. إضافة ومراجعة المشروبات -->
            <div class="dashboard-panel" style="padding: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="color: #0f172a; font-weight: 800; font-size: 18px;">🧃 إدارة المشروبات</h3>
                    <span style="background-color: #f1f5f9; color: #475569; font-size: 12px; font-weight: bold; padding: 6px 12px; border-radius: 20px;">العدد الكلي: {{ count($beverages) }}</span>
                </div>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 18px; margin-bottom: 24px;">
                    <h4 style="margin: 0 0 16px; font-size: 16px; font-weight: 800; color: #0f172a;">إضافة مشروب جديد</h4>
                    <form action="{{ route('admin.beverages.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                        @csrf
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 8px;">اسم المشروب</label>
                            <input type="text" name="name" required placeholder="مثل: عصير برتقال" style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 11px 14px; font-size: 14px; background-color: #ffffff;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 8px;">القسم</label>
                            <select name="category_id" required style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 11px 14px; font-size: 14px; background-color: #ffffff;">
                                <option value="">اختر القسم</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 8px;">السعر</label>
                            <input type="number" name="price" step="0.01" min="0" required placeholder="00.00" style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 11px 14px; font-size: 14px; background-color: #ffffff;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 8px;">درجة الحرارة</label>
                            <select name="temperature" required style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 11px 14px; font-size: 14px; background-color: #ffffff;">
                                <option value="cold">بارد</option>
                                <option value="hot">ساخن</option>
                                <option value="both">كلاهما</option>
                            </select>
                        </div>
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 8px;">الصورة</label>
                            <input type="file" name="image" accept="image/*" style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 8px 10px; font-size: 13px; background-color: #ffffff;">
                        </div>
                        <div class="lg:col-span-5">
                            <button type="submit" style="background: linear-gradient(135deg, #0ea5e9, #0284c7); color: #ffffff; font-weight: 700; padding: 12px 18px; border-radius: 10px; border: none; cursor: pointer; width: 100%; box-shadow: 0 4px 12px rgba(14, 165, 233, 0.25);">
                                إضافة المشروب
                            </button>
                        </div>
                    </form>
                </div>

                @if($beverages->isEmpty())
                <p style="color: #94a3b8; text-align: center; padding: 32px 0; font-size: 15px;">لا توجد مشروبات مضافة حتى الآن.</p>
                @else
                @foreach($beverages as $beverage)
                <form id="update-beverage-{{ $beverage->id }}" action="{{ route('admin.beverages.update', $beverage->id) }}" method="POST" enctype="multipart/form-data" style="display: none;">
                    @csrf
                    @method('PUT')
                </form>
                @endforeach

                <div style="overflow-x: auto;">
                    <table style="width: 100%; text-align: right; border-collapse: separate; border-spacing: 0; font-size: 14px;">
                        <thead>
                            <tr style="background-color: #f8fafc; color: #475569;">
                                <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">الصورة</th>
                                <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">الاسم</th>
                                <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">القسم</th>
                                <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">السعر</th>
                                <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">درجة الحرارة</th>
                                <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">تغيير الصورة</th>
                                <th style="padding: 12px; border-bottom: 2px solid #e2e8f0; text-align: center;">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($beverages as $beverage)
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 10px;">
                                    @if($beverage->image)
                                    <img src="{{ asset('storage/' . $beverage->image) }}" alt="{{ $beverage->name }}" style="width: 48px; height: 48px; object-fit: cover; border-radius: 10px; border: 1px solid #e2e8f0;">
                                    @else
                                    <div style="width: 48px; height: 48px; background: #f1f5f9; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px;">🥤</div>
                                    @endif
                                </td>
                                <td style="padding: 10px;">
                                    <input type="text" name="name" form="update-beverage-{{ $beverage->id }}" value="{{ $beverage->name }}" required style="width: 150px; border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px 10px; font-size: 13px; background-color: #ffffff;">
                                </td>
                                <td style="padding: 10px;">
                                    <select name="category_id" form="update-beverage-{{ $beverage->id }}" style="width: 140px; border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px 10px; font-size: 13px; background-color: #ffffff;">
                                        @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $beverage->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td style="padding: 10px;">
                                    <input type="number" name="price" form="update-beverage-{{ $beverage->id }}" value="{{ $beverage->price }}" step="0.01" min="0" required style="width: 90px; border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px 10px; font-size: 13px; background-color: #ffffff;">
                                </td>
                                <td style="padding: 10px;">
                                    <select name="temperature" form="update-beverage-{{ $beverage->id }}" style="width: 110px; border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px 10px; font-size: 13px; background-color: #ffffff;">
                                        <option value="cold" {{ $beverage->temperature == 'cold' ? 'selected' : '' }}>بارد</option>
                                        <option value="hot" {{ $beverage->temperature == 'hot' ? 'selected' : '' }}>ساخن</option>
                                        <option value="both" {{ $beverage->temperature == 'both' ? 'selected' : '' }}>كلاهما</option>
                                    </select>
                                </td>
                                <td style="padding: 10px;">
                                    <input type="file" name="image" form="update-beverage-{{ $beverage->id }}" accept="image/*" style="display: none;" id="beverage-file-{{ $beverage->id }}">
                                    <label for="beverage-file-{{ $beverage->id }}" style="display: inline-block; background: #f1f5f9; border: 1px dashed #cbd5e1; border-radius: 8px; padding: 6px 10px; font-size: 12px; font-weight: 600; color: #475569; cursor: pointer;">
                                        📷 اختر صورة
                                    </label>
                                </td>
                                <td style="padding: 10px; text-align: center;">
                                    <div style="display: flex; justify-content: center; align-items: center; gap: 8px;">
                                        <button type="submit" form="update-beverage-{{ $beverage->id }}" style="background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #ffffff; padding: 8px 14px; border-radius: 8px; font-size: 12px; font-weight: 700; border: none; cursor: pointer;">
                                            حفظ
                                        </button>
                                        <form action="{{ route('admin.beverages.destroy', $beverage->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المشروب؟')" style="margin: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background: linear-gradient(135deg, #ef4444, #dc2626); color: #ffffff; padding: 8px 14px; border-radius: 8px; font-size: 12px; font-weight: 700; border: none; cursor: pointer;">
                                                حذف
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

            <!-- 5. جدول إدارة الأطعمة الحالية -->
            <div class="dashboard-panel" style="padding: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="color: #0f172a; font-weight: 800; font-size: 18px;">📋 إدارة الأطعمة والتعديل السريع</h3>
                    <span style="background-color: #f1f5f9; color: #475569; font-size: 12px; font-weight: bold; padding: 6px 12px; border-radius: 20px;">العدد الكلي: {{ count($foods) }}</span>
                </div>

                @if($foods->isEmpty())
                <p style="color: #94a3b8; text-align: center; padding: 32px 0; font-size: 15px;">لا توجد أطعمة مضافة حتى الآن في النظام.</p>
                @else
                @foreach($foods as $food)
                <form id="update-food-{{ $food->id }}" action="{{ route('admin.foods.update', $food->id) }}" method="POST" enctype="multipart/form-data" style="display: none;">
                    @csrf
                    @method('PUT')
                </form>
                @endforeach

                <div style="overflow-x: auto;">
                    <table style="width: 100%; text-align: right; border-collapse: separate; border-spacing: 0; font-size: 14px;">
                        <thead>
                            <tr style="background-color: #f8fafc; color: #475569;">
                                <th style="padding: 14px; border-bottom: 2px solid #e2e8f0; border-radius: 0 10px 10px 0;">الصورة</th>
                                <th style="padding: 14px; border-bottom: 2px solid #e2e8f0;">اسم الطعام</th>
                                <th style="padding: 14px; border-bottom: 2px solid #e2e8f0;">القسم</th>
                                <th style="padding: 14px; border-bottom: 2px solid #e2e8f0;">السعر (ج.م)</th>
                                <th style="padding: 14px; border-bottom: 2px solid #e2e8f0;">الكمية</th>
                                <th style="padding: 14px; border-bottom: 2px solid #e2e8f0;">الوصف</th>
                                <th style="padding: 14px; border-bottom: 2px solid #e2e8f0;">تغيير الصورة</th>
                                <th style="padding: 14px; border-bottom: 2px solid #e2e8f0; text-align: center; border-radius: 10px 0 0 10px;">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($foods as $food)
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 12px;">
                                    @if($food->image)
                                    <img src="{{ asset('storage/' . $food->image) }}?v={{ time() }}" alt="{{ $food->name }}" style="width: 48px; height: 48px; object-fit: cover; border-radius: 10px; border: 1px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                    @else
                                    <div style="width: 48px; height: 48px; background-color: #f1f5f9; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px;">🍲</div>
                                    @endif
                                </td>
                                <td style="padding: 12px;">
                                    <input type="text" name="name" form="update-food-{{ $food->id }}" value="{{ $food->name }}" required style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px 10px; font-size: 13px; color: #0f172a; background-color: #ffffff;">
                                </td>
                                <td style="padding: 12px;">
                                    <select name="category_id" form="update-food-{{ $food->id }}" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px 10px; font-size: 13px; color: #0f172a; background-color: #ffffff;">
                                        @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $food->category_id == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td style="padding: 12px;">
                                    <input type="number" step="0.01" name="price" form="update-food-{{ $food->id }}" value="{{ $food->price }}" required style="width: 90px; border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px 10px; font-size: 13px; color: #0f172a; background-color: #ffffff;">
                                </td>
                                <td style="padding: 12px;">
                                    <input type="number" name="available_quantity" form="update-food-{{ $food->id }}" value="{{ $food->available_quantity }}" required style="width: 70px; border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px 10px; font-size: 13px; color: #0f172a; background-color: #ffffff;">
                                    <input type="hidden" name="spicy_level" form="update-food-{{ $food->id }}" value="{{ $food->spicy_level }}">
                                </td>
                                <td style="padding: 12px;">
                                    <input type="text" name="description" form="update-food-{{ $food->id }}" value="{{ $food->description }}" placeholder="الوصف" style="width: 120px; border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px 10px; font-size: 13px; color: #0f172a; background-color: #ffffff;">
                                </td>
                                <td style="padding: 12px;">
                                    <input type="file" name="image" form="update-food-{{ $food->id }}" accept="image/*" onchange="previewFileName(this, 'file-label-{{ $food->id }}')" style="display: none;" id="file-input-{{ $food->id }}">
                                    <label for="file-input-{{ $food->id }}" id="file-label-{{ $food->id }}" style="display: inline-block; background: #f1f5f9; border: 1px dashed #cbd5e1; border-radius: 8px; padding: 6px 10px; font-size: 12px; font-weight: 600; color: #475569; cursor: pointer; text-align: center; white-space: nowrap;">
                                        📷 اختر صورة
                                    </label>
                                </td>
                                <td style="padding: 12px; text-align: center;">
                                    <div style="display: flex; justify-content: center; align-items: center; gap: 8px;">
                                        <button type="submit" form="update-food-{{ $food->id }}" style="background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #ffffff; padding: 8px 14px; border-radius: 8px; font-size: 12px; font-weight: 700; border: none; cursor: pointer; white-space: nowrap; box-shadow: 0 2px 6px rgba(37, 99, 235, 0.2);">
                                            حفظ
                                        </button>
                                        <form action="{{ route('admin.foods.destroy', $food->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الطعام؟')" style="margin: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background: linear-gradient(135deg, #ef4444, #dc2626); color: #ffffff; padding: 8px 14px; border-radius: 8px; font-size: 12px; font-weight: 700; border: none; cursor: pointer; white-space: nowrap; box-shadow: 0 2px 6px rgba(239, 68, 68, 0.2);">
                                                حذف
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

            <!-- 6. إدارة المستخدمين -->
            <div class="dashboard-panel" style="padding: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="color: #0f172a; font-weight: 800; font-size: 18px;">👥 إدارة المستخدمين</h3>
                    <span style="background-color: #f1f5f9; color: #475569; font-size: 12px; font-weight: bold; padding: 6px 12px; border-radius: 20px;">{{ count($users) }} مستخدم</span>
                </div>

                <div style="overflow-x: auto;">
                    <table style="width: 100%; text-align: right; border-collapse: separate; border-spacing: 0; font-size: 14px;">
                        <thead>
                            <tr style="background-color: #f8fafc; color: #475569;">
                                <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">الاسم</th>
                                <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">البريد الإلكتروني</th>
                                <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">الدور</th>
                                <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">تاريخ التسجيل</th>
                                <th style="padding: 12px; border-bottom: 2px solid #e2e8f0; text-align: center;">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 12px; font-weight: 700;">{{ $user->name }}</td>
                                <td style="padding: 12px;">{{ $user->email }}</td>
                                <td style="padding: 12px;">
                                    <form action="{{ route('admin.users.update-role', $user->id) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        @method('PATCH')
                                        <select name="role" onchange="this.form.submit()" style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px 10px; font-size: 13px; background-color: #ffffff;">
                                            <option value="customer" {{ $user->role == 'customer' ? 'selected' : '' }}>Customer</option>
                                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                    </form>
                                </td>
                                <td style="padding: 12px;">{{ $user->created_at ? $user->created_at->format('Y-m-d') : '-' }}</td>
                                <td style="padding: 12px; text-align: center;">
                                    @if($user->id !== auth()->id())
                                    <span style="color: #64748b; font-size: 12px;">-</span>
                                    @else
                                    <span style="color: #10b981; font-weight: 700; font-size: 12px;">أنت</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 7. إدارة الطلبات -->
            <div class="dashboard-panel" style="padding: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; gap: 16px; flex-wrap: wrap;">
                    <h3 style="color: #0f172a; font-weight: 800; font-size: 18px; margin: 0;">🛒 إدارة الطلبات</h3>
                    <span style="background-color: #f1f5f9; color: #475569; font-size: 12px; font-weight: bold; padding: 6px 12px; border-radius: 20px;">{{ count($orders) }} طلب</span>
                </div>

                <form method="GET" action="{{ route('admin.dashboard') }}" style="display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 20px; align-items: end;">
                    <div style="min-width: 180px;">
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">اسم العميل</label>
                        <input type="text" name="customer_name" value="{{ request('customer_name') }}" placeholder="ابحث باسم العميل" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px 10px; font-size: 13px;">
                    </div>

                    <div style="min-width: 170px;">
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">الحالة</label>
                        <select name="status" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px 10px; font-size: 13px; background-color: #ffffff;">
                            <option value="">كل الحالات</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                            <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>قيد التجهيز</option>
                            <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>جاهز</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                        </select>
                    </div>

                    <button type="submit" style="background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #ffffff; padding: 10px 18px; border-radius: 8px; font-size: 13px; font-weight: 700; border: none; cursor: pointer;">
                        تصفية
                    </button>

                    @if(request('customer_name') || request('status'))
                    <a href="{{ route('admin.dashboard') }}" style="background: #f1f5f9; color: #475569; padding: 10px 14px; border-radius: 8px; font-size: 13px; font-weight: 700; text-decoration: none;">
                        مسح
                    </a>
                    @endif
                </form>

                <div style="overflow-x: auto;">
                    <table style="width: 100%; text-align: right; border-collapse: separate; border-spacing: 0; font-size: 14px;">
                        <thead>
                            <tr style="background-color: #f8fafc; color: #475569;">
                                <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">رقم الطلب</th>
                                <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">اسم العميل</th>
                                <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">البريد الإلكتروني</th>
                                <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">رقم الهاتف</th>
                                <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">العنوان</th>
                                <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">العناصر</th>
                                <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">الإجمالي</th>
                                <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">تاريخ الطلب</th>
                                <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">الحالة</th>
                                <th style="padding: 12px; border-bottom: 2px solid #e2e8f0; text-align: center;">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr style="border-bottom: 1px solid #f1f5f9; vertical-align: top;">
                                <td style="padding: 12px; font-weight: 700;">#{{ $order->id }}</td>
                                <td style="padding: 12px;">
                                    <div style="font-weight: 700; color: #0f172a;">{{ $order->user?->name ?? 'مستخدم محذوف' }}</div>
                                    <div style="font-size: 11px; color: #64748b; margin-top: 4px;">User ID: {{ $order->user_id ?? '-' }}</div>
                                </td>
                                <td style="padding: 12px;">{{ $order->user?->email ?? '-' }}</td>
                                <td style="padding: 12px;">{{ $order->phone ?: '-' }}</td>
                                <td style="padding: 12px; min-width: 180px;">{{ $order->delivery_address ?: '-' }}</td>
                                <td style="padding: 12px; min-width: 210px;">
                                    @foreach($order->items as $item)
                                    <div style="font-size: 12px; margin-bottom: 6px; padding: 4px 8px; background: #f8fafc; border-radius: 8px;">
                                        {{ $item->itemable?->name ?? 'منتج غير موجود' }} × {{ $item->quantity }}
                                    </div>
                                    @endforeach
                                </td>
                                <td style="padding: 12px; font-weight: 800; color: #0f172a;">{{ number_format($order->total_price, 2) }} ج.م</td>
                                <td style="padding: 12px;">{{ $order->created_at ? $order->created_at->format('Y-m-d H:i') : '-' }}</td>
                                <td style="padding: 12px;">
                                    <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px 10px; font-size: 13px; background-color: #ffffff; min-width: 120px;">
                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                                            <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>قيد التجهيز</option>
                                            <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>جاهز</option>
                                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>مكتمل</option>
                                        </select>
                                    </form>
                                </td>
                                <td style="padding: 12px; text-align: center;">
                                    <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الطلب؟')" style="margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: linear-gradient(135deg, #ef4444, #dc2626); color: #ffffff; padding: 8px 14px; border-radius: 8px; font-size: 12px; font-weight: 700; border: none; cursor: pointer;">
                                            حذف
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
    </div>

    <script>
        function previewFileName(input, labelId) {
            if (input.files && input.files[0]) {
                const label = document.getElementById(labelId);
                label.textContent = '✅ ' + input.files[0].name.substring(0, 10) + '...';
                label.style.background = '#d1fae5';
                label.style.color = '#065f46';
                label.style.borderColor = '#10b981';
            }
        }
    </script>
</x-app-layout>