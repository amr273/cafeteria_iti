<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">⚙️ ضبط تفضيلاتي</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                @if(session('success'))
                    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('customer.preferences.update') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- مستوى الحار -->
                    <div>
                        <label for="spicy_level" class="block text-sm font-medium text-gray-700">مستوى الحار المفضل (0 - 5):</label>
                        <input type="number" id="spicy_level" name="spicy_level" min="0" max="5" 
                               value="{{ old('spicy_level', $preference->spicy_level ?? 0) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>

                    <!-- السعر المفضل -->
                    <div>
                        <label for="price_preference" class="block text-sm font-medium text-gray-700">متوسط السعر المفضل (ج.م):</label>
                        <input type="number" step="0.01" id="price_preference" name="price_preference" 
                               value="{{ old('price_preference', $preference->price_preference ?? 100) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>

                    <!-- المذاق المفضل -->
                    <div>
                        <label for="preferred_taste" class="block text-sm font-medium text-gray-700">المذاق المفضل (اختياري):</label>
                        <input type="text" id="preferred_taste" name="preferred_taste" placeholder="مثال: حار وجبن، حلو، مشويات" 
                               value="{{ old('preferred_taste', $preference->preferred_taste ?? '') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow">
                        حفظ التفضيلات
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>