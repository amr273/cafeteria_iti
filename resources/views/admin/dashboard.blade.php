<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-2xl text-gray-800 leading-tight flex items-center gap-2">
                لوحة التحكم والإدارة الشاملة
            </h2>
            <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1.5 rounded-full">النسخة المتقدمة v2.0</span>
        </div>
    </x-slot>

    <!-- استدعاء مكتبة Chart.js للرسوم البيانية -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .dashboard-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .dashboard-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -10px rgba(0, 0, 0, 0.08);
        }
    </style>

    <div style="background-color: #f8fafc; padding: 32px 0; min-height: 100vh; font-family: system-ui, -apple-system, sans-serif;">
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

            <!-- 1. البطاقات الإحصائية سريعة القراءة -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- إجمالي المبيعات -->
                <div class="dashboard-card" style="background-color: #ffffff; padding: 22px; border-radius: 16px; border: 1px solid #f1f5f9; border-right: 6px solid #10b981; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="color: #64748b; font-size: 13px; font-weight: 700; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">إجمالي المبيعات</p>
                        <h3 style="color: #0f172a; font-size: 26px; font-weight: 800; margin-top: 6px; margin-bottom: 0;">
                            {{ number_format($stats['total_sales'] ?? 0, 2) }} <span style="font-size: 14px; color: #64748b; font-weight: 600;">ج.م</span>
                        </h3>
                    </div>
                    <div style="background: linear-gradient(135deg, #d1fae5, #a7f3d0); padding: 14px; border-radius: 14px; font-size: 26px; box-shadow: 0 2px 6px rgba(16, 185, 129, 0.15);">💰</div>
                </div>

                <!-- إجمالي الطلبات -->
                <div class="dashboard-card" style="background-color: #ffffff; padding: 22px; border-radius: 16px; border: 1px solid #f1f5f9; border-right: 6px solid #3b82f6; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="color: #64748b; font-size: 13px; font-weight: 700; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">إجمالي الطلبات</p>
                        <h3 style="color: #0f172a; font-size: 26px; font-weight: 800; margin-top: 6px; margin-bottom: 0;">{{ $stats['total_orders'] ?? 0 }}</h3>
                    </div>
                    <div style="background: linear-gradient(135deg, #dbeafe, #bfdbfe); padding: 14px; border-radius: 14px; font-size: 26px; box-shadow: 0 2px 6px rgba(59, 130, 246, 0.15);">🛍️</div>
                </div>

                <!-- إجمالي العملاء -->
                <div class="dashboard-card" style="background-color: #ffffff; padding: 22px; border-radius: 16px; border: 1px solid #f1f5f9; border-right: 6px solid #8b5cf6; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="color: #64748b; font-size: 13px; font-weight: 700; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">العملاء المسجلين</p>
                        <h3 style="color: #0f172a; font-size: 26px; font-weight: 800; margin-top: 6px; margin-bottom: 0;">{{ $stats['total_customers'] ?? 0 }}</h3>
                    </div>
                    <div style="background: linear-gradient(135deg, #ede9fe, #ddd6fe); padding: 14px; border-radius: 14px; font-size: 26px; box-shadow: 0 2px 6px rgba(139, 92, 246, 0.15);">👥</div>
                </div>

                <!-- نواقص المخزون -->
                <div class="dashboard-card" style="background-color: #ffffff; padding: 22px; border-radius: 16px; border: 1px solid #f1f5f9; border-right: 6px solid #f59e0b; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="color: #64748b; font-size: 13px; font-weight: 700; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">نواقص المخزون (&lt;5)</p>
                        <h3 style="color: #0f172a; font-size: 26px; font-weight: 800; margin-top: 6px; margin-bottom: 0;">{{ $stats['low_stock'] ?? 0 }}</h3>
                    </div>
                    <div style="background: linear-gradient(135deg, #fef3c7, #fde68a); padding: 14px; border-radius: 14px; font-size: 26px; box-shadow: 0 2px 6px rgba(245, 158, 11, 0.15);">⚠️</div>
                </div>

            </div>

            <!-- 2. قسم الرسوم البيانية -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- الرسم البياني للأقسام -->
                <div style="background-color: #ffffff; padding: 24px; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.02);" class="lg:col-span-2">
                    <h3 style="color: #1e293b; font-weight: 800; font-size: 18px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <span style="background: #e0e7ff; padding: 6px 10px; border-radius: 10px;">📊</span> عدد الأطعمة المضافة لكل قسم
                    </h3>
                    <div style="position: relative; height: 270px;">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>

                <!-- الرسم البياني للأنواع -->
                <div style="background-color: #ffffff; padding: 24px; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
                    <h3 style="color: #1e293b; font-weight: 800; font-size: 18px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <span style="background: #fce7f3; padding: 6px 10px; border-radius: 10px;">🍕</span> نسبة الأطعمة والمشروبات
                    </h3>
                    <div style="position: relative; height: 270px; display: flex; justify-content: center; align-items: center;">
                        <canvas id="typeChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- 3. نماذج الإضافة -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- إضافة قسم -->
                <div style="background-color: #ffffff; padding: 24px; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
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
                <div style="background-color: #ffffff; padding: 24px; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.02);" class="md:col-span-2">
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

            <!-- 4. جدول إدارة وتعديل الأطعمة الحالية -->
            <div style="background-color: #ffffff; padding: 24px; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="color: #0f172a; font-weight: 800; font-size: 18px;">📋 إدارة الأطعمة والتعديل السريع</h3>
                    <span style="background-color: #f1f5f9; color: #475569; font-size: 12px; font-weight: bold; padding: 6px 12px; border-radius: 20px;">العدد الكلي: {{ count($foods) }}</span>
                </div>
                
                @if($foods->isEmpty())
                    <p style="color: #94a3b8; text-align: center; padding: 32px 0; font-size: 15px;">لا توجد أطعمة مضافة حتى الآن في النظام.</p>
                @else
                    {{-- إنشاء نماذج التحديث الخفية مع التأكد من وجود enctype --}}
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

                                        {{-- 1. معاينة الصورة الحالية --}}
                                        <td style="padding: 12px;">
                                            @if($food->image)
                                                <img src="{{ asset('storage/' . $food->image) }}?v={{ time() }}" alt="{{ $food->name }}" style="width: 48px; height: 48px; object-fit: cover; border-radius: 10px; border: 1px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                            @else
                                                <div style="width: 48px; height: 48px; background-color: #f1f5f9; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px;">🍲</div>
                                            @endif
                                        </td>

                                        {{-- 2. الاسم --}}
                                        <td style="padding: 12px;">
                                            <input type="text" name="name" form="update-food-{{ $food->id }}" value="{{ $food->name }}" required style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px 10px; font-size: 13px; color: #0f172a; background-color: #ffffff;">
                                        </td>

                                        {{-- 3. القسم --}}
                                        <td style="padding: 12px;">
                                            <select name="category_id" form="update-food-{{ $food->id }}" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px 10px; font-size: 13px; color: #0f172a; background-color: #ffffff;">
                                                @foreach($categories as $cat)
                                                    <option value="{{ $cat->id }}" {{ $food->category_id == $cat->id ? 'selected' : '' }}>
                                                        {{ $cat->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>

                                        {{-- 4. السعر --}}
                                        <td style="padding: 12px;">
                                            <input type="number" step="0.01" name="price" form="update-food-{{ $food->id }}" value="{{ $food->price }}" required style="width: 90px; border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px 10px; font-size: 13px; color: #0f172a; background-color: #ffffff;">
                                        </td>

                                        {{-- 5. الكمية ومستوى الحرارة --}}
                                        <td style="padding: 12px;">
                                            <input type="number" name="available_quantity" form="update-food-{{ $food->id }}" value="{{ $food->available_quantity }}" required style="width: 70px; border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px 10px; font-size: 13px; color: #0f172a; background-color: #ffffff;">
                                            <input type="hidden" name="spicy_level" form="update-food-{{ $food->id }}" value="{{ $food->spicy_level }}">
                                        </td>

                                        {{-- 6. الوصف --}}
                                        <td style="padding: 12px;">
                                            <input type="text" name="description" form="update-food-{{ $food->id }}" value="{{ $food->description }}" placeholder="الوصف" style="width: 120px; border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px 10px; font-size: 13px; color: #0f172a; background-color: #ffffff;">
                                        </td>

                                        {{-- 7. رفع صورة جديدة للتعديل --}}
                                        <td style="padding: 12px;">
                                            <input type="file" name="image" form="update-food-{{ $food->id }}" accept="image/*" onchange="previewFileName(this, 'file-label-{{ $food->id }}')" style="display: none;" id="file-input-{{ $food->id }}">
                                            <label for="file-input-{{ $food->id }}" id="file-label-{{ $food->id }}" style="display: inline-block; background: #f1f5f9; border: 1px dashed #cbd5e1; border-radius: 8px; padding: 6px 10px; font-size: 12px; font-weight: 600; color: #475569; cursor: pointer; text-align: center; white-space: nowrap;">
                                                📷 اختر صورة
                                            </label>
                                        </td>

                                        {{-- 8. الأزرار الإجرائية --}}
                                        <td style="padding: 12px; text-align: center;">
                                            <div style="display: flex; justify-content: center; align-items: center; gap: 8px;">
                                                <!-- زر حفظ التعديل -->
                                                <button type="submit" 
                                                        form="update-food-{{ $food->id }}" 
                                                        style="background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #ffffff; padding: 8px 14px; border-radius: 8px; font-size: 12px; font-weight: 700; border: none; cursor: pointer; white-space: nowrap; box-shadow: 0 2px 6px rgba(37, 99, 235, 0.2);">
                                                    حفظ
                                                </button>

                                                <!-- زر الحذف -->
                                                <form action="{{ route('admin.foods.destroy', $food->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الطعام؟')" style="margin: 0;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            style="background: linear-gradient(135deg, #ef4444, #dc2626); color: #ffffff; padding: 8px 14px; border-radius: 8px; font-size: 12px; font-weight: 700; border: none; cursor: pointer; white-space: nowrap; box-shadow: 0 2px 6px rgba(239, 68, 68, 0.2);">
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

    <!-- كود JavaScript للرسوم البيانية وعرض اسم الملف عند الاختيار -->
    <script>
        // إشعار اختيار الملف في الجدول
        function previewFileName(input, labelId) {
            if (input.files && input.files[0]) {
                const label = document.getElementById(labelId);
                label.textContent = '✅ ' + input.files[0].name.substring(0, 10) + '...';
                label.style.background = '#d1fae5';
                label.style.color = '#065f46';
                label.style.borderColor = '#10b981';
            }
        }

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
                        backgroundColor: '#6366f1',
                        hoverBackgroundColor: '#4f46e5',
                        borderRadius: 8
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
                        borderWidth: 0
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