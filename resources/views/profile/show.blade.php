<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center text-orange-600 text-lg font-bold">
                <i class="fa-solid fa-user-gear"></i>
            </div>
            <div>
                <h2 class="font-black text-xl text-gray-800 leading-tight">
                    إعدادات الحساب والشخصية
                </h2>
                <p class="text-xs font-bold text-gray-500 mt-0.5">إدارة بيانات حسابك، كلمة المرور والإعدادات الأمنية</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-[#f9f8f3] min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- 1. تعديل البيانات الشخصية -->
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                <div class="bg-white rounded-[28px] border border-[#eae8df] shadow-[0_10px_30px_rgba(0,0,0,0.02)] p-6 sm:p-8">
                    @livewire('profile.update-profile-information-form')
                </div>
            @endif

            <!-- 2. تغيير كلمة المرور -->
            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="bg-white rounded-[28px] border border-[#eae8df] shadow-[0_10px_30px_rgba(0,0,0,0.02)] p-6 sm:p-8">
                    @livewire('profile.update-password-form')
                </div>
            @endif

            <!-- 3. المصادقة الثنائية (Two Factor Authentication) -->
            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="bg-white rounded-[28px] border border-[#eae8df] shadow-[0_10px_30px_rgba(0,0,0,0.02)] p-6 sm:p-8">
                    @livewire('profile.two-factor-authentication-form')
                </div>
            @endif

            <!-- 4. الجلسات والمتصفحات النشطة -->
            <div class="bg-white rounded-[28px] border border-[#eae8df] shadow-[0_10px_30px_rgba(0,0,0,0.02)] p-6 sm:p-8">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

            <!-- 5. حذف الحساب -->
            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <div class="bg-rose-50/50 rounded-[28px] border border-rose-100 shadow-[0_10px_30px_rgba(0,0,0,0.02)] p-6 sm:p-8">
                    @livewire('profile.delete-user-form')
                </div>
            @endif

        </div>
    </div>
</x-app-layout>