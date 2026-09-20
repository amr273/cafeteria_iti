<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📊 لوحة التحكم والإدارة الشاملة
        </h2>
    </x-slot>

    <!-- استدعاء مكتبة Chart.js للرسوم البيانية -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div style="background-color: #f3f4f6; padding: 32px 0; min-height: 100vh; font-family: sans-serif;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="display: flex; flex-direction: column; gap: 24px;">
            
            {{-- رسالة النجاح --}}
            @if(session('success'))
                <div style="background-color: #10b981; color: #ffffff; padding: 16px; border-radius: 12px; font-weight: bold; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    ✅ {{ session('success') }}
                </div>
            @endif

            {{-- رسائل الأخطاء --}}
            @if ($errors->any())
                <div style="background-color: #ef4444; color: #ffffff; padding: 16px; border-radius: 12px; font-weight: bold; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <ul style="list-style-type: disc; margin-right: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- 1. البطاقات الإحصائية سريعة القراءة -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- إجمالي المبيعات -->
                <div style="background-color: #ffffff; padding: 20px; border-radius: 16px; border-right: 6px solid #10b981; border: 1px solid #e5e7eb; border-right-width: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="color: #6b7280; font-size: 13px; font-weight: bold; margin: 0;">إجمالي المبيعات</p>
                        <h3 style="color: #111827; font-size: 24px; font-weight: 800; margin-top: 4px; margin-bottom: 0;">
                            {{ number_format($stats['total_sales'] ?? 0, 2) }} <span style="font-size: 14px; color: #4b5563; font-weight: normal;">ج.م</span>
                        </h3>
                    </div>
                    <div style="background-color: #d1fae5; padding: 12px; border-radius: 12px; font-size: 24px;">💰</div>
                </div>

                <!-- إجمالي الطلبات -->
                <div style="background-color: #ffffff; padding: 20px; border-radius: 16px; border-right: 6px solid #3b82f6; border: 1px solid #e5e7eb; border-right-width: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="color: #6b7280; font-size: 13px; font-weight: bold; margin: 0;">إجمالي الطلبات</p>
                        <h3 style="color: #111827; font-size: 24px; font-weight: 800; margin-top: 4px; margin-bottom: 0;">{{ $stats['total_orders'] ?? 0 }}</h3>
                    </div>
                    <div style="background-color: #dbeafe; padding: 12px; border-radius: 12px; font-size: 24px;">🛍️</div>
                </div>

                <!-- إجمالي العملاء -->
                <div style="background-color: #ffffff; padding: 20px; border-radius: 16px; border-right: 6px solid #8b5cf6; border: 1px solid #e5e7eb; border-right-width: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="color: #6b7280; font-size: 13px; font-weight: bold; margin: 0;">العملاء المسجلين</p>
                        <h3 style="color: #111827; font-size: 24px; font-weight: 800; margin-top: 4px; margin-bottom: 0;">{{ $stats['total_customers'] ?? 0 }}</h3>
                    </div>
                    <div style="background-color: #ede9fe; padding: 12px; border-radius: 12px; font-size: 24px;">👥</div>
                </div>

                <!-- نواقص المخزون -->
                <div style="background-color: #ffffff; padding: 20px; border-radius: 16px; border-right: 6px solid #f59e0b; border: 1px solid #e5e7eb; border-right-width: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="color: #6b7280; font-size: 13px; font-weight: bold; margin: 0;">نواقص المخزون (&lt;5)</p>
                        <h3 style="color: #111827; font-size: 24px; font-weight: 800; margin-top: 4px; margin-bottom: 0;">{{ $stats['low_stock'] ?? 0 }}</h3>
                    </div>
                    <div style="background-color: #fef3c7; padding: 12px; border-radius: 12px; font-size: 24px;">⚠️</div>
                </div>

            </div>

            <!-- 2. قسم الرسوم البيانية -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- الرسم البياني للأقسام -->
                <div style="background-color: #ffffff; padding: 20px; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 2px 8px rgba(0,0,0,0.05);" class="lg:col-span-2">
                    <h3 style="color: #1f2937; font-weight: bold; font-size: 18px; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                        <span>📊</span> عدد الأطعمة المضافة لكل قسم
                    </h3>
                    <div style="position: relative; height: 260px;">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>

                <!-- الرسم البياني للأنواع -->
                <div style="background-color: #ffffff; padding: 20px; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                    <h3 style="color: #1f2937; font-weight: bold; font-size: 18px; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                        <span>🍕</span> نسبة الأطعمة والمشروبات
                    </h3>
                    <div style="position: relative; height: 260px; display: flex; justify-content: center; align-items: center;">
                        <canvas id="typeChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- 3. نماذج الإضافة -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- إضافة قسم -->
                <div style="background-color: #ffffff; padding: 24px; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                    <h3 style="color: #4f46e5; font-weight: bold; font-size: 18px; margin-bottom: 16px;">➕ إضافة قسم جديد</h3>
                    <form action="{{ route('admin.categories.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 16px;">
                        @csrf
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: bold; color: #374151; margin-bottom: 6px;">اسم القسم</label>
                            <input type="text" name="name" style="width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 10px; font-size: 14px; color: #111827; background-color: #ffffff;" required placeholder="مثل: المشويات">
                        </div>
                        <button type="submit" style="background-color: #4f46e5; color: #ffffff; font-weight: bold; padding: 10px; border-radius: 8px; border: none; cursor: pointer; width: 100%;">
                            إضافة القسم
                        </button>
                    </form>
                </div>

                <!-- إضافة طعام -->
                <div style="background-color: #ffffff; padding: 24px; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 2px 8px rgba(0,0,0,0.05);" class="md:col-span-2">
                    <h3 style="color: #059669; font-weight: bold; font-size: 18px; margin-bottom: 16px;">🍔 إضافة طعام جديد</h3>
                    <form action="{{ route('admin.foods.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @csrf
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: bold; color: #374151; margin-bottom: 6px;">اسم الصنف</label>
                            <input type="text" name="name" style="width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 10px; font-size: 14px; color: #111827; background-color: #ffffff;" required>
                        </div>
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: bold; color: #374151; margin-bottom: 6px;">القسم</label>
                            <select name="category_id" style="width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 10px; font-size: 14px; color: #111827; background-color: #ffffff;" required>
                                <option value="" disabled selected>اختر القسم</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: bold; color: #374151; margin-bottom: 6px;">السعر (ج.م)</label>
                            <input type="number" step="0.01" name="price" style="width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 10px; font-size: 14px; color: #111827; background-color: #ffffff;" required>
                        </div>
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: bold; color: #374151; margin-bottom: 6px;">مستوى الحار (0-5)</label>
                            <input type="number" name="spicy_level" value="0" min="0" max="5" style="width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 10px; font-size: 14px; color: #111827; background-color: #ffffff;" required>
                        </div>
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: bold; color: #374151; margin-bottom: 6px;">الكمية المتاحة</label>
                            <input type="number" name="available_quantity" value="10" min="0" style="width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 10px; font-size: 14px; color: #111827; background-color: #ffffff;" required>
                        </div>
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: bold; color: #374151; margin-bottom: 6px;">الوصف (اختياري)</label>
                            <input type="text" name="description" style="width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 10px; font-size: 14px; color: #111827; background-color: #ffffff;">
                        </div>
                        <div class="sm:col-span-2">
                            <button type="submit" style="background-color: #059669; color: #ffffff; font-weight: bold; padding: 12px; border-radius: 8px; border: none; cursor: pointer; width: 100%;">
                                إضافة الطعام
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 4. جدول إدارة وتعديل الأطعمة الحالية -->
            <div style="background-color: #ffffff; padding: 24px; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                <h3 style="color: #111827; font-weight: bold; font-size: 18px; margin-bottom: 16px;">📋 إدارة الأطعمة والتعديل السريع</h3>
                
                @if($foods->isEmpty())
                    <p style="color: #6b7280; text-align: center; padding: 24px 0;">لا توجد أطعمة مضافة حتى الآن.</p>
                @else
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; text-align: right; border-collapse: collapse; font-size: 14px;">
                            <thead>
                                <tr style="background-color: #f3f4f6; color: #1f2937; border-bottom: 2px solid #e5e7eb;">
                                    <th style="padding: 12px;">اسم الطعام</th>
                                    <th style="padding: 12px;">القسم</th>
                                    <th style="padding: 12px;">السعر (ج.م)</th>
                                    <th style="padding: 12px;">الكمية</th>
                                    <th style="padding: 12px; text-align: center;">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($foods as $food)
                                    <tr style="border-bottom: 1px solid #e5e7eb;">
                                        {{-- نموذج الحفظ المترابط بنفس المكونات --}}
                                        <form id="update-food-{{ $food->id }}" action="{{ route('admin.foods.update', $food->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                        </form>

                                        {{-- الاسم --}}
                                        <td style="padding: 10px;">
                                            <input type="text" name="name" form="update-food-{{ $food->id }}" value="{{ $food->name }}" style="width: 100%; border: 1px solid #d1d5db; border-radius: 6px; padding: 6px 10px; font-size: 14px; color: #111827; background-color: #ffffff;">
                                        </td>

                                        {{-- القسم --}}
                                        <td style="padding: 10px;">
                                            <select name="category_id" form="update-food-{{ $food->id }}" style="width: 100%; border: 1px solid #d1d5db; border-radius: 6px; padding: 6px 10px; font-size: 14px; color: #111827; background-color: #ffffff;">
                                                @foreach($categories as $cat)
                                                    <option value="{{ $cat->id }}" {{ $food->category_id == $cat->id ? 'selected' : '' }}>
                                                        {{ $cat->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>

                                        {{-- السعر --}}
                                        <td style="padding: 10px;">
                                            <input type="number" step="0.01" name="price" form="update-food-{{ $food->id }}" value="{{ $food->price }}" style="width: 110px; border: 1px solid #d1d5db; border-radius: 6px; padding: 6px 10px; font-size: 14px; color: #111827; background-color: #ffffff;">
                                        </td>

                                        {{-- الكمية --}}
                                        <td style="padding: 10px;">
                                            <input type="number" name="available_quantity" form="update-food-{{ $food->id }}" value="{{ $food->available_quantity }}" style="width: 80px; border: 1px solid #d1d5db; border-radius: 6px; padding: 6px 10px; font-size: 14px; color: #111827; background-color: #ffffff;">
                                            <input type="hidden" name="spicy_level" form="update-food-{{ $food->id }}" value="{{ $food->spicy_level }}">
                                        </td>

                                        {{-- الأزرار الواضحة بـ CSS صريح --}}
                                        <td style="padding: 10px; text-align: center;">
                                            <div style="display: flex; justify-content: center; align-items: center; gap: 8px;">
                                                <!-- زر حفظ التعديل -->
                                                <button type="submit" 
                                                        form="update-food-{{ $food->id }}" 
                                                        style="background-color: #2563eb; color: #ffffff; padding: 8px 14px; border-radius: 6px; font-size: 12px; font-weight: bold; border: none; cursor: pointer; white-space: nowrap; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                                    حفظ التعديل
                                                </button>

                                                <!-- زر الحذف -->
                                                <form action="{{ route('admin.foods.destroy', $food->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')" style="margin: 0;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            style="background-color: #dc2626; color: #ffffff; padding: 8px 14px; border-radius: 6px; font-size: 12px; font-weight: bold; border: none; cursor: pointer; white-space: nowrap; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
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

        </div>
    </div>

    <!-- كود تشغيل الرسوم البيانية -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // رسم الأقسام
            const categoryCtx = document.getElementById('categoryChart').getContext('2d');
            new Chart(categoryCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartCategoryNames ?? []) !!},
                    datasets: [{
                        label: 'عدد الأطعمة',
                        data: {!! json_encode($chartFoodCounts ?? []) !!},
                        backgroundColor: '#4f46e5',
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
                }
            });

            // رسم الأنواع
            const typeCtx = document.getElementById('typeChart').getContext('2d');
            new Chart(typeCtx, {
                type: 'doughnut',
                data: {
                    labels: ['أطعمة', 'مشروبات'],
                    datasets: [{
                        data: [{{ $stats['total_foods'] ?? 0 }}, {{ $stats['total_beverages'] ?? 0 }}],
                        backgroundColor: ['#10b981', '#3b82f6'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        });
    </script>
</x-app-layout>