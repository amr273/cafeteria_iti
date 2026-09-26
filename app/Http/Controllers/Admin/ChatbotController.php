<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Beverage;
use App\Models\FoodItem;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function handle(Request $request)
    {
        $message = trim((string) $request->input('message', ''));

        if ($message === '') {
            return response()->json(['reply' => 'يرجى كتابة سؤالك أولاً.']);
        }

        try {
            $stats = [
                'total_orders' => Order::count(),
                'pending_orders' => Order::where('status', 'pending')->count(),
                'preparing_orders' => Order::whereIn('status', ['preparing', 'ready'])->count(),
                'total_customers' => User::where('role', 'customer')->count(),
                'low_stock' => FoodItem::where('available_quantity', '<', 5)->count(),
                'total_foods' => FoodItem::count(),
                'total_beverages' => Beverage::count(),
            ];

            $systemInstruction = "أنت مساعد إدارة ذكي لكافتيريا. أجب باللغة العربية فقط، وكن مختصراً واحترافياً.\n\n" .
                "إحصائيات النظام الحالية:\n" .
                "- إجمالي الطلبات: {$stats['total_orders']}\n" .
                "- الطلبات المعلقة: {$stats['pending_orders']}\n" .
                "- الطلبات قيد التنفيذ: {$stats['preparing_orders']}\n" .
                "- إجمالي العملاء: {$stats['total_customers']}\n" .
                "- عدد الأطعمة: {$stats['total_foods']}\n" .
                "- عدد المشروبات: {$stats['total_beverages']}\n" .
                "- الأصناف منخفضة المخزون: {$stats['low_stock']}\n\n" .
                "القاعدة: استخدم هذه البيانات فقط، ولا تكتشف أرقاماً غير مدرجة. وإذا كان السؤال خارج نطاق الإدارة، أجب بلباقة مع توجيه المستخدم إلى صلاحيات الإدارة.";

            $apiKey = config('services.gemini.api_key') ?? env('GEMINI_API_KEY');
            $model = config('services.gemini.model') ?? env('GEMINI_MODEL', 'gemini-3.6-flash');

            if (! $apiKey) {
                return response()->json(['reply' => '⚠️ لم يتم ضبط GEMINI_API_KEY في ملف .env']);
            }

            $response = Http::withoutVerifying()
                ->retry(3, 300)
                ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
                    'system_instruction' => [
                        'parts' => [
                            ['text' => $systemInstruction],
                        ],
                    ],
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [
                                ['text' => $message],
                            ],
                        ],
                    ],
                ]);

            if ($response->successful()) {
                $payload = $response->json();

                return response()->json([
                    'reply' => $payload['candidates'][0]['content']['parts'][0]['text'] ?? 'عذراً، لم أستطع معالجة الإجابة.',
                ]);
            }

            Log::error('Admin Gemini API Error: ' . $response->body());

            return response()->json([
                'reply' => 'حدث خطأ من الذكاء الاصطناعي أثناء معالجة طلب الإدارة.',
            ]);
        } catch (\Throwable $e) {
            Log::error('Admin Chatbot Exception: ' . $e->getMessage());

            return response()->json([
                'reply' => 'حدث خطأ: ' . $e->getMessage(),
            ]);
        }
    }
}
