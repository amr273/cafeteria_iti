<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">🍔 القائمة والطلب Direct</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                <!-- الأطعمة -->
                <h3 class="text-lg font-bold mb-4 text-indigo-600 border-b pb-2">الأطعمة</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    @foreach($foods as $food)
                        <div class="border rounded-lg p-4 shadow-sm bg-gray-50 flex flex-col justify-between">
                            <div>
                                <h4 class="font-bold text-md">{{ $food->name }}</h4>
                                <p class="text-sm text-gray-600 mb-2">{{ $food->description }}</p>
                                <div class="flex justify-between items-center text-sm mb-4">
                                    <span class="font-bold text-green-600">{{ $food->price }} ج.م</span>
                                    <span class="bg-yellow-200 text-yellow-800 text-xs px-2 py-1 rounded">🌶️ {{ $food->spicy_level }}</span>
                                </div>
                            </div>
                            
                            <!-- نموذج الطلب -->
                            <form action="{{ route('customer.orders.store') }}" method="POST" class="flex gap-2">
                                @csrf
                                <input type="hidden" name="item_id" value="{{ $food->id }}">
                                <input type="hidden" name="item_type" value="food">
                                <input type="number" name="quantity" value="1" min="1" class="w-16 text-center border-gray-300 rounded-lg text-sm">
                                <button type="submit" class="w-full bg-indigo-600 text-white text-sm font-bold py-2 px-4 rounded-lg hover:bg-indigo-700">طلب الآن</button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <!-- المشروبات -->
                <h3 class="text-lg font-bold mb-4 text-indigo-600 border-b pb-2">المشروبات</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($beverages as $drink)
                        <div class="border rounded-lg p-4 shadow-sm bg-gray-50 flex flex-col justify-between">
                            <div>
                                <h4 class="font-bold text-md">{{ $drink->name }}</h4>
                                <div class="flex justify-between items-center text-sm mt-2 mb-4">
                                    <span class="font-bold text-green-600">{{ $drink->price }} ج.م</span>
                                    <span class="bg-blue-200 text-blue-800 text-xs px-2 py-1 rounded">{{ $drink->temperature }}</span>
                                </div>
                            </div>

                            <!-- نموذج الطلب -->
                            <form action="{{ route('customer.orders.store') }}" method="POST" class="flex gap-2">
                                @csrf
                                <input type="hidden" name="item_id" value="{{ $drink->id }}">
                                <input type="hidden" name="item_type" value="beverage">
                                <input type="number" name="quantity" value="1" min="1" class="w-16 text-center border-gray-300 rounded-lg text-sm">
                                <button type="submit" class="w-full bg-indigo-600 text-white text-sm font-bold py-2 px-4 rounded-lg hover:bg-indigo-700">طلب الآن</button>
                            </form>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</x-app-layout>