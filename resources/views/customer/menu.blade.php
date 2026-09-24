<x-app-layout>
  <x-slot name="header">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-2" dir="rtl">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center text-orange-600 text-lg font-bold">
          <i class="fa-solid fa-utensils"></i>
        </div>
        <div>
          <h2 class="font-black text-2xl text-gray-800 tracking-tight">القائمة والطلب</h2>
          <p class="text-xs font-bold text-gray-500 mt-0.5">اختر وجبتك أو مشروبك المفضل واطلبه مباشرة</p>
        </div>
      </div>
    </div>
  </x-slot>

  <div class="py-10 bg-[#f9f8f3] min-h-screen" dir="rtl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

      <div class="bg-white rounded-[24px] border border-[#eae8df] p-4 shadow-sm">
        <form action="{{ route('customer.menu') }}" method="GET" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <div class="relative md:col-span-2">
              <i class="fa-solid fa-magnifying-glass absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
              <input
                type="text"
                name="q"
                value="{{ request('q') }}"
                placeholder="ابحث عن وجبة أو مشروب..."
                class="w-full pr-12 pl-4 py-3 rounded-2xl border border-[#e0ddd0] bg-[#fafaf7] text-gray-800 font-semibold focus:outline-none focus:ring-2 focus:ring-orange-200 focus:border-orange-300">
            </div>

            <select name="category_id" class="w-full px-3 py-3 rounded-2xl border border-[#e0ddd0] bg-[#fafaf7] text-gray-800 font-semibold focus:outline-none focus:ring-2 focus:ring-orange-200 focus:border-orange-300">
              <option value="">كل الأقسام</option>
              @foreach($categories as $category)
              <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
              @endforeach
            </select>

            <input
              type="number"
              name="min_price"
              min="0"
              step="0.01"
              value="{{ request('min_price') }}"
              placeholder="السعر من"
              class="w-full px-3 py-3 rounded-2xl border border-[#e0ddd0] bg-[#fafaf7] text-gray-800 font-semibold focus:outline-none focus:ring-2 focus:ring-orange-200 focus:border-orange-300">

            <input
              type="number"
              name="max_price"
              min="0"
              step="0.01"
              value="{{ request('max_price') }}"
              placeholder="السعر إلى"
              class="w-full px-3 py-3 rounded-2xl border border-[#e0ddd0] bg-[#fafaf7] text-gray-800 font-semibold focus:outline-none focus:ring-2 focus:ring-orange-200 focus:border-orange-300">
          </div>

          <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div class="w-full md:w-56">
              <select name="spicy_level" class="w-full px-3 py-3 rounded-2xl border border-[#e0ddd0] bg-[#fafaf7] text-gray-800 font-semibold focus:outline-none focus:ring-2 focus:ring-orange-200 focus:border-orange-300">
                <option value="">كل درجات الحرارة</option>
                @for ($level = 0; $level <= 5; $level++)
                  <option value="{{ $level }}" {{ request('spicy_level') == (string) $level ? 'selected' : '' }}>
                  {{ $level === 0 ? 'بدون فلفل' : 'مستوى ' . $level }}
                  </option>
                  @endfor
              </select>
            </div>

            <div class="flex items-center gap-2">
              <button type="submit" class="bg-gradient-to-r from-[#ff8c42] to-[#e85d04] text-white font-extrabold px-6 py-3 rounded-2xl shadow-sm hover:shadow-md transition-all">
                <i class="fa-solid fa-search ml-2"></i>بحث
              </button>

              @if(request()->hasAny(['q', 'category_id', 'min_price', 'max_price', 'spicy_level']))
              <a href="{{ route('customer.menu') }}" class="inline-flex items-center justify-center px-4 py-3 rounded-2xl border border-[#e0ddd0] text-gray-700 font-bold hover:bg-gray-50">
                <i class="fa-solid fa-rotate-left ml-2"></i>مسح
              </a>
              @endif
            </div>
          </div>
        </form>
      </div>

      @if(request()->hasAny(['q', 'category_id', 'min_price', 'max_price', 'spicy_level']))
      <div class="text-sm font-bold text-gray-600">
        نتائج البحث الحالية:
        @if(request('q'))
        <span class="text-[#e85d04]">{{ request('q') }}</span>
        @endif
        @if(request('category_id'))
        <span class="text-[#e85d04]">| القسم: {{ $categories->firstWhere('id', request('category_id'))?->name ?? '' }}</span>
        @endif
        @if(request('min_price') || request('max_price'))
        <span class="text-[#e85d04]">| السعر: {{ request('min_price') ?: '0' }} - {{ request('max_price') ?: 'غير محدود' }}</span>
        @endif
        @if(request('spicy_level'))
        <span class="text-[#e85d04]">| مستوى الفلفل: {{ request('spicy_level') }}</span>
        @endif
      </div>
      @endif

      <!-- قسم الأطعمة -->
      <section>
        <div class="flex items-center gap-3 mb-6 pb-3 border-b border-[#eee2d5]">
          <span class="w-2.5 h-8 bg-gradient-to-b from-[#ff8c42] to-[#e85d04] rounded-full"></span>
          <h3 class="text-xl font-black text-gray-800">{{ request('q') ? 'نتائج البحث في الأطعمة' : 'الأطعمة و المشروبات' }}</h3>
        </div>

        @if($foods->isEmpty())
        <div class="bg-white rounded-[28px] border border-dashed border-[#e0ddd0] p-8 text-center text-gray-500 font-bold">
          <i class="fa-solid fa-utensils text-2xl mb-3 block opacity-60"></i>
          لا توجد نتائج مطابقة لبحثك.
        </div>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          @foreach($foods as $food)
          <div class="bg-white rounded-[28px] border border-[#eae8df] shadow-[0_10px_30px_rgba(0,0,0,0.02)] hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col justify-between group">

            <div>
              <!-- صورة الوجبة -->
              <div class="relative h-48 w-full bg-[#faf9f5] overflow-hidden">
                @php
                $foodImg = $food->image_url ?? $food->image;
                if ($foodImg) {
                $foodSrc = \Illuminate\Support\Str::startsWith($foodImg, ['http://', 'https://'])
                ? $foodImg
                : asset('storage/' . $foodImg);
                } else {
                $foodSrc = 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80';
                }
                @endphp

                <img src="{{ $foodSrc }}"
                  alt="{{ $food->name }}"
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                <!-- الشارة: مستوى الفلفل/الحرارة -->
                @if($food->spicy_level)
                <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-md text-amber-800 text-xs font-black px-3 py-1.5 rounded-full shadow-sm border border-amber-100">
                  🌶️ درجة الحرارة: {{ $food->spicy_level }}
                </span>
                @endif
              </div>

              <!-- تفاصيل الوجبة -->
              <div class="p-5">
                <div class="flex justify-between items-start gap-2 mb-2">
                  <h4 class="font-extrabold text-lg text-gray-800 leading-snug">{{ $food->name }}</h4>
                  <span class="text-lg font-black text-[#e85d04] whitespace-nowrap">
                    {{ $food->price }} <span class="text-xs font-bold text-gray-500">ج.م</span>
                  </span>
                </div>

                @if($food->description)
                <p class="text-xs font-bold text-gray-500 line-clamp-2 leading-relaxed mb-4">{{ $food->description }}</p>
                @endif
              </div>
            </div>

            <!-- نموذج الطلب -->
            <div class="p-5 pt-0">
              <form action="{{ route('customer.orders.store') }}" method="POST" class="flex items-center gap-3">
                @csrf
                <input type="hidden" name="item_id" value="{{ $food->id }}">
                <input type="hidden" name="item_type" value="food">

                <!-- اختيار الكمية -->
                <div class="relative flex items-center">
                  <input type="number"
                    name="quantity"
                    value="1"
                    min="1"
                    class="w-16 text-center font-extrabold text-gray-800 bg-[#faf9f5] border-2 border-[#eee2d5] rounded-2xl py-2.5 text-sm focus:bg-white focus:border-[#f97316] focus:ring-4 focus:ring-orange-500/10 outline-none transition-all duration-200">
                </div>

                <!-- زر إضافة الطلب -->
                <button type="submit"
                  class="flex-1 bg-gradient-to-r from-[#ff8c42] to-[#e85d04] hover:from-[#e85d04] hover:to-[#ff8c42] text-white text-sm font-black py-2.5 px-4 rounded-full shadow-[0_4px_14px_rgba(232,93,4,0.25)] hover:shadow-[0_6px_18px_rgba(232,93,4,0.35)] active:scale-95 transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer">
                  <i class="fa-solid fa-cart-plus"></i>
                  <span>طلب الآن</span>
                </button>
              </form>
            </div>

          </div>
          @endforeach
        </div>
        @endif
      </section>

      <!-- قسم المشروبات -->
      <section>
        <div class="flex items-center gap-3 mb-6 pb-3 border-b border-[#eee2d5]">
          <span class="w-2.5 h-8 bg-sky-500 rounded-full"></span>
          <h3 class="text-xl font-black text-gray-800">المشروبات</h3>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          @foreach($beverages as $drink)
          <div class="bg-white rounded-[28px] border border-[#eae8df] shadow-[0_10px_30px_rgba(0,0,0,0.02)] hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col justify-between group">

            <div>

              <div class="relative h-48 w-full bg-[#faf9f5] overflow-hidden">
                @php
                $drinkImg = $drink->image_url ?? $drink->image;
                if ($drinkImg) {
                $drinkSrc = \Illuminate\Support\Str::startsWith($drinkImg, ['http://', 'https://'])
                ? $drinkImg
                : asset('storage/' . $drinkImg);
                } else {
                $drinkSrc = 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?auto=format&fit=crop&w=600&q=80';
                }
                @endphp

                <img src="{{ $drinkSrc }}"
                  alt="{{ $drink->name }}"
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                @if($drink->temperature)
                <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-md text-sky-700 text-xs font-black px-3 py-1.5 rounded-full shadow-sm border border-sky-100">
                  ☕ {{ $drink->temperature }}
                </span>
                @endif
              </div>


              <div class="p-5">
                <div class="flex justify-between items-start gap-2 mb-2">
                  <h4 class="font-extrabold text-lg text-gray-800 leading-snug">{{ $drink->name }}</h4>
                  <span class="text-lg font-black text-[#e85d04] whitespace-nowrap">
                    {{ $drink->price }} <span class="text-xs font-bold text-gray-500">ج.م</span>
                  </span>
                </div>
              </div>
            </div>


            <div class="p-5 pt-0">
              <form action="{{ route('customer.orders.store') }}" method="POST" class="flex items-center gap-3">
                @csrf
                <input type="hidden" name="item_id" value="{{ $drink->id }}">
                <input type="hidden" name="item_type" value="beverage">


                <div class="relative flex items-center">
                  <input type="number"
                    name="quantity"
                    value="1"
                    min="1"
                    class="w-16 text-center font-extrabold text-gray-800 bg-[#faf9f5] border-2 border-[#eee2d5] rounded-2xl py-2.5 text-sm focus:bg-white focus:border-[#f97316] focus:ring-4 focus:ring-orange-500/10 outline-none transition-all duration-200">
                </div>

                <button type="submit"
                  class="flex-1 bg-gradient-to-r from-[#ff8c42] to-[#e85d04] hover:from-[#e85d04] hover:to-[#ff8c42] text-white text-sm font-black py-2.5 px-4 rounded-full shadow-[0_4px_14px_rgba(232,93,4,0.25)] hover:shadow-[0_6px_18px_rgba(232,93,4,0.35)] active:scale-95 transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer">
                  <i class="fa-solid fa-cart-plus"></i>
                  <span>طلب الآن</span>
                </button>
              </form>
            </div>

          </div>
          @endforeach
        </div>
      </section>

    </div>
  </div>

  <!-- تضمين الفوتر هنا في نهاية الصفحة -->
  <x-footer />

</x-app-layout>