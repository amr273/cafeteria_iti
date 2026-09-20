<?php

namespace App\Services;

use App\Models\User;
use App\Models\FoodItem;
use App\Models\Beverage;
use Illuminate\Support\Facades\Http;

class AiService
{
    /**
     * جلب التوصيات الذكية للعميل بناءً على تفضيلاته
     */
    public function getRecommendations(User $user)
    {
        $preferences = $user->preference;
        $foods = FoodItem::where('status', true)->get(['id', 'name', 'price', 'spicy_level', 'description']);
        $beverages = Beverage::where('status', true)->get(['id', 'name', 'price', 'temperature']);

        $prompt = "تفضيلات العميل: " . json_encode($preferences, JSON_UNESCAPED_UNICODE) . " " .
                  "الأطعمة: " . json_encode($foods, JSON_UNESCAPED_UNICODE) . " " .
                  "المشروبات: " . json_encode($beverages, JSON_UNESCAPED_UNICODE);

        $response = Http::withoutVerifying()
            ->withToken(env('OPENAI_API_KEY'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'أنت مساعد كافتيريا ذكي يوصي بأفضل الأطباق والمشروبات للعميل بناءً على تفضيلاته بأسلوب منسق ولطيف.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
            ]);

        if ($response->successful()) {
            return $response->json()['choices'][0]['message']['content'] ?? 'لا تتوفر توصيات حالياً.';
        }

        return 'حدث خطأ أثناء جلب التوصيات، يرجى المحاولة لاحقاً.';
    }

    /**
     * الإجابة على استفسارات الشات بوت
     */
    public function askChatbot(string $message)
    {
        $response = Http::withoutVerifying()
            ->withToken(env('OPENAI_API_KEY'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'أنت مساعد آلي لكافتيريا تجيب على أسئلة العملاء وتساعدهم في اختيار الوجبات.'],
                    ['role' => 'user', 'content' => $message]
                ],
            ]);

        if ($response->successful()) {
            return $response->json()['choices'][0]['message']['content'] ?? 'عذراً، لم أستطع فهم طلبك.';
        }

        return 'حدث خطأ في الاتصال بالخدمة.';
    }
}