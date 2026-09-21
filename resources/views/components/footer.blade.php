<footer class="bg-[#111827] text-gray-300 pt-12 pb-6 border-t border-gray-800 font-['Cairo']" dir="rtl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- الجزء العلوي: الأقسام الثلاثة -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pb-10 border-b border-gray-800 text-right">
            
            <!-- العمود الأول: الشعار / اسم الموقع -->
            <div class="flex items-start">
                <a href="{{ url('/') }}" class="text-3xl font-black tracking-wider text-white hover:text-orange-500 transition-colors">
                    ☕ كافيه
                </a>
            </div>

            <!-- العمود الثاني: معلومات التواصل (Contact) -->
            <div>
                <h4 class="text-lg font-bold text-white mb-4">تواصل معنا</h4>
                <ul class="space-y-2 text-sm text-gray-400 font-medium">
                    <li class="flex items-center gap-2 justify-start">
                        <i class="fa-solid fa-phone text-xs text-orange-500"></i>
                        <span dir="ltr">+20 123 456 7890</span>
                    </li>
                    <li class="flex items-center gap-2 justify-start">
                        <i class="fa-solid fa-envelope text-xs text-orange-500"></i>
                        <span>contact@caffee.com</span>
                    </li>
                </ul>
            </div>

            <!-- العمود الثالث: التواصل الاجتماعي (Get In Touch) -->
            <div>
                <h4 class="text-lg font-bold text-white mb-4">تابعنا على</h4>
                <div class="flex items-center gap-4 justify-start">
                    
                    <!-- فيسبوك -->
                    <a href="#" aria-label="Facebook" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-300 hover:text-white hover:bg-orange-600 transition-all">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>

                    <!-- لينكد إن -->
                    <a href="#" aria-label="LinkedIn" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-300 hover:text-white hover:bg-orange-600 transition-all">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                        </svg>
                    </a>

                    <!-- جيت هاب -->
                    <a href="#" aria-label="GitHub" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-300 hover:text-white hover:bg-orange-600 transition-all">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                        </svg>
                    </a>

                </div>
            </div>

        </div>

        <!-- الجزء السفلي: حقوق النشر والسياسات -->
        <div class="pt-6 flex flex-col md:flex-row items-center justify-between gap-4 text-xs font-semibold text-gray-400">
            <div>
                &copy; {{ date('Y') }} جميع الحقوق محفوظة.
            </div>
            <div class="flex items-center gap-6">
                @if (Route::has('policy.show'))
                    <a href="{{ route('policy.show') }}" class="hover:text-orange-400 transition-colors">سياسة الخصوصية</a>
                @endif
                @if (Route::has('terms.show'))
                    <a href="{{ route('terms.show') }}" class="hover:text-orange-400 transition-colors">شروط الخدمة</a>
                @endif
                <a href="#" class="hover:text-orange-400 transition-colors">إعدادات ملفات التعريف</a>
            </div>
        </div>
    </div>
</footer>