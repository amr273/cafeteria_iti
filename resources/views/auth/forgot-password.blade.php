<x-guest-layout>
    <div class="w-full max-w-md mx-auto my-auto">
        
        <div class="bg-white rounded-[28px] border border-[#eae8df] shadow-[0_10px_30px_rgba(0,0,0,0.03)] p-8 overflow-hidden">
            
            <!-- الهيدر وشعار الصفحة -->
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-amber-100/80 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl shadow-sm">
                    🔑
                </div>
                <h3 class="text-2xl font-black text-gray-800">استعادة كلمة المرور</h3>
                <p class="text-xs font-bold text-gray-500 mt-2 leading-relaxed">
                    نسيت كلمة المرور؟ لا مشكلة. أدخل بريدك الإلكتروني وسنرسل لك رابطاً لإعادة تعيين كلمة مرور جديدة.
                </p>
            </div>

            <!-- أخطاء التحقق (Validation Errors) -->
            <x-validation-errors class="mb-4" />

            <!-- رسائل الحالة والإرسال -->
            @session('status')
                <div class="mb-4 font-bold text-sm text-emerald-700 bg-emerald-50 p-3.5 rounded-2xl border border-emerald-200 flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
                    <span>{{ $value }}</span>
                </div>
            @endsession

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
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

                <!-- زر الإرسال -->
                <div class="pt-2">
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-[#ff8c42] to-[#e85d04] hover:from-[#e85d04] hover:to-[#ff8c42] text-white font-black text-base py-3.5 px-6 rounded-full shadow-[0_6px_20px_rgba(232,93,4,0.28)] hover:shadow-[0_8px_24px_rgba(232,93,4,0.38)] hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-paper-plane"></i>
                        <span>إرسال رابط إعادة التعيين</span>
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center pt-4 border-t border-gray-100">
                <a href="{{ route('login') }}" class="text-sm font-extrabold text-gray-500 hover:text-[#f97316] transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-arrow-right"></i>
                    <span>العودة لصفحة تسجيل الدخول</span>
                </a>
            </div>

        </div>

    </div>
</x-guest-layout>