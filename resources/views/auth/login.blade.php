<x-guest-layout>
    <div class="w-full max-w-md mx-auto my-auto">
        
        <div class="bg-white rounded-[28px] border border-[#eae8df] shadow-[0_10px_30px_rgba(0,0,0,0.03)] p-8 overflow-hidden">
            
            <!-- الهيدر وشعار الصفحة -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-orange-100/80 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl shadow-sm">
                    ☕
                </div>
                <h3 class="text-2xl font-black text-gray-800">تسجيل الدخول</h3>
                <p class="text-sm font-semibold text-gray-500 mt-1">مرحباً بك مجدداً في الكافتيريا الذكية</p>
            </div>

            <!-- أخطاء التحقق (Validation Errors) -->
            <x-validation-errors class="mb-4" />

            <!-- رسائل الحالة والجلسة -->
            @session('status')
                <div class="mb-4 font-bold text-sm text-emerald-700 bg-emerald-50 p-3.5 rounded-2xl border border-emerald-200 flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-600 fs-5"></i>
                    <span>{{ $value }}</span>
                </div>
            @endsession

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- البريد الإلكتروني -->
                <div>
                    <label for="email" class="block text-sm font-extrabold text-gray-800 mb-1.5">البريد الإلكتروني</label>
                    <input id="email" 
                           class="w-full border-2 border-[#eee2d5] rounded-2xl px-4 py-3 text-sm bg-[#faf9f5] focus:bg-white focus:border-[#f97316] focus:ring-4 focus:ring-orange-500/10 outline-none transition-all duration-200" 
                           type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus 
                           autocomplete="username" 
                           placeholder="example@domain.com" />
                </div>

                <!-- كلمة المرور -->
                <div>
                    <label for="password" class="block text-sm font-extrabold text-gray-800 mb-1.5">كلمة المرور</label>
                    <input id="password" 
                           class="w-full border-2 border-[#eee2d5] rounded-2xl px-4 py-3 text-sm bg-[#faf9f5] focus:bg-white focus:border-[#f97316] focus:ring-4 focus:ring-orange-500/10 outline-none transition-all duration-200" 
                           type="password" 
                           name="password" 
                           required 
                           autocomplete="current-password" 
                           placeholder="••••••••" />
                </div>

                <!-- تذكرني ورابط نسيت كلمة المرور -->
                <div class="flex items-center justify-between text-sm pt-1">
                    <label for="remember_me" class="flex items-center cursor-pointer">
                        <input id="remember_me" 
                               type="checkbox" 
                               name="remember" 
                               class="w-4 h-4 rounded border-gray-300 text-[#f97316] focus:ring-[#f97316] accent-[#f97316] cursor-pointer" />
                        <span class="ms-2 font-bold text-gray-600">تذكرني</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="font-bold text-sm text-[#f97316] hover:text-orange-700 underline transition" href="{{ route('password.request') }}">
                            نسيت كلمة المرور؟
                        </a>
                    @endif
                </div>

                <!-- زر تسجيل الدخول -->
                <div class="pt-2">
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-[#ff8c42] to-[#e85d04] hover:from-[#e85d04] hover:to-[#ff8c42] text-white font-black text-base py-3.5 px-6 rounded-full shadow-[0_6px_20px_rgba(232,93,4,0.28)] hover:shadow-[0_8px_24px_rgba(232,93,4,0.38)] hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <span>تسجيل الدخول</span>
                    </button>
                </div>
            </form>

            @if (Route::has('register'))
                <div class="mt-6 text-center pt-4 border-t border-gray-100">
                    <p class="text-sm font-bold text-gray-500">
                        ليس لديك حساب؟ 
                        <a href="{{ route('register') }}" class="text-[#f97316] hover:text-orange-700 font-extrabold underline ms-1">أنشئ حساباً جديداً</a>
                    </p>
                </div>
            @endif

        </div>

    </div>
</x-guest-layout>