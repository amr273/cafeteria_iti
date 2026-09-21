<nav x-data="{ open: false }" dir="rtl" class="font-['Cairo'] bg-white/95 backdrop-blur-md border-b border-gray-100 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">

            <!-- الجانب الأيمن: اسم الموقع والروابط -->
            <div class="flex items-center gap-10">
                <!-- اسم الموقع (العودة لصفحة welcome) -->
                <a href="{{ route('welcome') }}" class="text-2xl font-black text-gray-900 tracking-tight hover:opacity-90 transition flex items-center gap-2">
                    <span>☕</span> كافيه
                </a>

                <!-- روابط التنقل للشاشات الكبيرة -->
                <div class="hidden md:flex items-center gap-2">
                    @auth
                        @if(auth()->user()->role === 'customer')
                            <a href="{{ route('customer.menu') }}"
                               class="px-4 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('customer.menu') ? 'bg-[#FF5200] text-white shadow-md shadow-orange-500/20' : 'text-gray-600 hover:text-[#FF5200] hover:bg-orange-50/80' }}">
                                القائمة والطلب
                            </a>

                            <a href="{{ route('customer.preferences') }}"
                               class="px-4 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('customer.preferences') ? 'bg-[#FF5200] text-white shadow-md shadow-orange-500/20' : 'text-gray-600 hover:text-[#FF5200] hover:bg-orange-50/80' }}">
                                تفضيلاتي
                            </a>

                            <a href="{{ route('customer.recommendations') }}"
                               class="px-4 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('customer.recommendations') ? 'bg-[#FF5200] text-white shadow-md shadow-orange-500/20' : 'text-gray-600 hover:text-[#FF5200] hover:bg-orange-50/80' }}">
                                التوصيات الذكية
                            </a>

                            <a href="{{ route('customer.chatbot') }}"
                               class="px-4 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('customer.chatbot') ? 'bg-[#FF5200] text-white shadow-md shadow-orange-500/20' : 'text-gray-600 hover:text-[#FF5200] hover:bg-orange-50/80' }}">
                                الشات بوت
                            </a>

                            <a href="{{ route('customer.orders') }}"
                               class="px-4 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('customer.orders') ? 'bg-[#FF5200] text-white shadow-md shadow-orange-500/20' : 'text-gray-600 hover:text-[#FF5200] hover:bg-orange-50/80' }}">
                                طلباتي
                            </a>
                        @endif

                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}"
                               class="px-4 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-[#FF5200] text-white shadow-md shadow-orange-500/20' : 'text-gray-600 hover:text-[#FF5200] hover:bg-orange-50/80' }}">
                                لوحة التحكم
                            </a>
                        @endif
                    @else
                        <!-- رابط المنيو للزائر غير المسجل -->
                        <a href="{{ route('customer.menu') }}"
                           class="px-4 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('customer.menu') ? 'bg-[#FF5200] text-white shadow-md shadow-orange-500/20' : 'text-gray-600 hover:text-[#FF5200] hover:bg-orange-50/80' }}">
                            القائمة والطلب
                        </a>
                    @endauth
                </div>
            </div>

            <!-- الجانب الأيسر: قائمة المستخدم أو أزرار الدخول -->
            <div class="hidden sm:flex sm:items-center">
                @auth
                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            <button type="button" class="inline-flex items-center gap-2.5 px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-bold text-gray-700 bg-gray-50 hover:bg-orange-50 hover:border-orange-200 hover:text-[#FF5200] transition-all duration-200">
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="p-2 bg-white rounded-2xl shadow-xl border border-gray-100 min-w-[200px]">
                                <div class="px-3 py-2 border-b border-gray-100 mb-1">
                                    <p class="text-xs text-gray-400 font-bold">حساب المستخدم</p>
                                    <p class="text-sm font-bold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                                </div>

                                <a href="{{ route('profile.show') }}" class="block px-3 py-2 rounded-lg text-sm font-bold text-gray-700 hover:bg-orange-50 hover:text-[#FF5200] transition">
                                    الملف الشخصي
                                </a>

                                <div class="border-t border-gray-100 my-1"></div>

                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf
                                    <a href="{{ route('logout') }}" @click.prevent="$root.submit();" class="block px-3 py-2 rounded-lg text-sm font-bold text-red-600 hover:bg-red-50 transition">
                                        تسجيل الخروج
                                    </a>
                                </form>
                            </div>
                        </x-slot>
                    </x-dropdown>
                @else
                    <!-- أزرار تسجيل الدخول للزوار -->
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="px-4 py-2.5 rounded-xl text-sm font-bold text-gray-700 hover:text-[#FF5200] hover:bg-orange-50 transition">
                            تسجيل الدخول
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-[#FF5200] hover:bg-orange-600 transition shadow-md shadow-orange-500/20">
                            حساب جديد
                        </a>
                    </div>
                @endauth
            </div>

            <!-- زر القائمة للموبايل -->
            <div class="flex items-center md:hidden">
                <button @click="open = ! open" class="p-2.5 rounded-xl text-gray-600 bg-gray-50 hover:bg-orange-50 hover:text-[#FF5200] focus:outline-none transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path x-show="open" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- قائمة الموبايل المنسدلة -->
    <div x-show="open" x-cloak class="md:hidden bg-white border-t border-gray-100 shadow-xl p-4 space-y-2">
        @auth
            @if(auth()->user()->role === 'customer')
                <a href="{{ route('customer.menu') }}" class="block px-4 py-3 rounded-xl font-bold text-sm transition {{ request()->routeIs('customer.menu') ? 'bg-[#FF5200] text-white shadow-md' : 'text-gray-700 hover:bg-orange-50' }}">القائمة والطلب</a>
                <a href="{{ route('customer.preferences') }}" class="block px-4 py-3 rounded-xl font-bold text-sm transition {{ request()->routeIs('customer.preferences') ? 'bg-[#FF5200] text-white shadow-md' : 'text-gray-700 hover:bg-orange-50' }}">تفضيلاتي</a>
                <a href="{{ route('customer.recommendations') }}" class="block px-4 py-3 rounded-xl font-bold text-sm transition {{ request()->routeIs('customer.recommendations') ? 'bg-[#FF5200] text-white shadow-md' : 'text-gray-700 hover:bg-orange-50' }}">التوصيات الذكية</a>
                <a href="{{ route('customer.chatbot') }}" class="block px-4 py-3 rounded-xl font-bold text-sm transition {{ request()->routeIs('customer.chatbot') ? 'bg-[#FF5200] text-white shadow-md' : 'text-gray-700 hover:bg-orange-50' }}">الشات بوت</a>
                <a href="{{ route('customer.orders') }}" class="block px-4 py-3 rounded-xl font-bold text-sm transition {{ request()->routeIs('customer.orders') ? 'bg-[#FF5200] text-white shadow-md' : 'text-gray-700 hover:bg-orange-50' }}">طلباتي</a>
            @endif

            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded-xl font-bold text-sm transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#FF5200] text-white shadow-md' : 'text-gray-700 hover:bg-orange-50' }}">لوحة التحكم</a>
            @endif

            <div class="pt-3 border-t border-gray-100 space-y-1">
                <div class="px-4 py-1 text-xs text-gray-400 font-bold">{{ Auth::user()->name }}</div>
                <a href="{{ route('profile.show') }}" class="block px-4 py-2 rounded-lg text-sm font-bold text-gray-700 hover:bg-gray-100">الملف الشخصي</a>
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <a href="{{ route('logout') }}" @click.prevent="$root.submit();" class="block px-4 py-2 rounded-lg text-sm font-bold text-red-600 hover:bg-red-50">تسجيل الخروج</a>
                </form>
            </div>
        @else
            <a href="{{ route('customer.menu') }}" class="block px-4 py-3 rounded-xl font-bold text-sm transition {{ request()->routeIs('customer.menu') ? 'bg-[#FF5200] text-white shadow-md' : 'text-gray-700 hover:bg-orange-50' }}">القائمة والطلب</a>
            <div class="pt-3 border-t border-gray-100 space-y-2">
                <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2.5 rounded-xl font-bold text-sm text-gray-700 hover:bg-orange-50">تسجيل الدخول</a>
                <a href="{{ route('register') }}" class="block w-full text-center px-4 py-2.5 rounded-xl font-bold text-sm text-white bg-[#FF5200]">حساب جديد</a>
            </div>
        @endauth
    </div>
</nav>