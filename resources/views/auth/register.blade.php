<x-guest-layout>
    <div class="w-full max-w-md mx-auto my-auto">
        
        <div class="bg-white rounded-[28px] border border-[#eae8df] shadow-[0_10px_30px_rgba(0,0,0,0.03)] p-8 overflow-hidden">
            
            <!-- الهيدر وشعار الصفحة -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-emerald-100/80 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl shadow-sm">
                    ✨
                </div>
                <h3 class="text-2xl font-black text-gray-800">إنشاء حساب جديد</h3>
                <p class="text-sm font-semibold text-gray-500 mt-1">انضم إلينا واستمتع بتجربة طلب طعام مخصصة</p>
            </div>

            <!-- أخطاء التحقق (Validation Errors) -->
            <x-validation-errors class="mb-4" />

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <!-- الاسم الكامل -->
                <div>
                    <label for="name" class="block text-sm font-extrabold text-gray-800 mb-1.5">الاسم بالكامل</label>
                    <input id="name" 
                           class="w-full border-2 border-[#eee2d5] rounded-2xl px-4 py-3 text-sm bg-[#faf9f5] focus:bg-white focus:border-[#f97316] focus:ring-4 focus:ring-orange-500/10 outline-none transition-all duration-200" 
                           type="text" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required 
                           autofocus 
                           autocomplete="name" 
                           placeholder="مثال: أحمد محمد" />
                </div>

                <!-- البريد الإلكتروني -->
                <div>
                    <label for="email" class="block text-sm font-extrabold text-gray-800 mb-1.5">البريد الإلكتروني</label>
                    <input id="email" 
                           class="w-full border-2 border-[#eee2d5] rounded-2xl px-4 py-3 text-sm bg-[#faf9f5] focus:bg-white focus:border-[#f97316] focus:ring-4 focus:ring-orange-500/10 outline-none transition-all duration-200" 
                           type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
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
                           autocomplete="new-password" 
                           placeholder="••••••••" />
                </div>

                <!-- تأكيد كلمة المرور -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-extrabold text-gray-800 mb-1.5">تأكيد كلمة المرور</label>
                    <input id="password_confirmation" 
                           class="w-full border-2 border-[#eee2d5] rounded-2xl px-4 py-3 text-sm bg-[#faf9f5] focus:bg-white focus:border-[#f97316] focus:ring-4 focus:ring-orange-500/10 outline-none transition-all duration-200" 
                           type="password" 
                           name="password_confirmation" 
                           required 
                           autocomplete="new-password" 
                           placeholder="••••••••" />
                </div>

                <!-- شروط الاستخدام وسياسة الخصوصية (إن وجدت في إعدادات Jetstream) -->
                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div class="pt-2">
                        <label for="terms" class="flex items-center cursor-pointer">
                            <input id="terms" 
                                   type="checkbox" 
                                   name="terms" 
                                   required 
                                   class="w-4 h-4 rounded border-gray-300 text-[#f97316] focus:ring-[#f97316] accent-[#f97316] cursor-pointer" />
                            <span class="ms-2 text-xs font-bold text-gray-600 leading-relaxed">
                                {!! __('أوافق على :terms_of_service و :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-[#f97316] hover:text-orange-700">'.__('شروط الخدمة').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-[#f97316] hover:text-orange-700">'.__('سياسة الخصوصية').'</a>',
                                ]) !!}
                            </span>
                        </label>
                    </div>
                @endif

                <!-- زر إنشاء الحساب -->
                <div class="pt-3">
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-[#ff8c42] to-[#e85d04] hover:from-[#e85d04] hover:to-[#ff8c42] text-white font-black text-base py-3.5 px-6 rounded-full shadow-[0_6px_20px_rgba(232,93,4,0.28)] hover:shadow-[0_8px_24px_rgba(232,93,4,0.38)] hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-user-plus"></i>
                        <span>إنشاء الحساب الآن</span>
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center pt-4 border-t border-gray-100">
                <p class="text-sm font-bold text-gray-500">
                    لديك حساب بالفعل؟ 
                    <a href="{{ route('login') }}" class="text-[#f97316] hover:text-orange-700 font-extrabold underline ms-1">تسجيل الدخول</a>
                </p>
            </div>

        </div>

    </div>
</x-guest-layout>