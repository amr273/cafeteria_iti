<?php

namespace App\Services;

use App\Models\Beverage;
use App\Models\FoodItem;
use App\Models\Order;
use App\Models\User;
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
          ['role' => 'user', 'content' => $prompt],
        ],
      ]);

    if ($response->successful()) {
      return $response->json()['choices'][0]['message']['content'] ?? 'لا تتوفر توصيات حالياً.';
    }

    return 'حدث خطأ أثناء جلب التوصيات، يرجى المحاولة لاحقاً.';
  }

  /**
   * الإجابة على استفسارات الشات بوت باستخدام Gemini بشكل حقيقي
   */
  public function askChatbot(?User $user, string $message): string
  {
    $cleanMessage = trim($message);

    if ($cleanMessage === '') {
      return 'يرجى كتابة سؤالك أولاً.';
    }

    $userContext = $user ? "المستخدم: {$user->name} | الدور: {$user->role}\n" : "المستخدم: ضيف\n";

    $apiKey = config('services.gemini.api_key') ?? env('GEMINI_API_KEY');
    $model = config('services.gemini.model') ?? env('GEMINI_MODEL', 'gemini-2.0-flash');

    if (! $apiKey) {
      return '⚠️ لم يتم ضبط GEMINI_API_KEY في ملف .env';
    }

    $systemInstruction = "أنت مساعد ذكي لكافيتريا متكامل. أجب باللغة العربية فقط، وكن مختصراً ودوداً، وركز على البيانات الحقيقية للنظام.\n\n" .
      "البيانات الحالية:\n" .
      "- عدد الطلبات: " . Order::count() . "\n" .
      "- الطلبات المعلقة: " . Order::where('status', 'pending')->count() . "\n" .
      "- الطلبات قيد التنفيذ: " . Order::whereIn('status', ['preparing', 'ready'])->count() . "\n" .
      "- عدد العملاء: " . User::where('role', 'customer')->count() . "\n" .
      "- عدد الأطعمة: " . FoodItem::count() . "\n" .
      "- عدد المشروبات: " . Beverage::count() . "\n\n" .
      $userContext .
      "القواعد:\n1. استخدم البيانات الموجودة فقط.\n2. إذا لم تتوفر بيانات، أخبر المستخدم بصراحة.\n3. لا تقل إلا ما هو مثبت في النظام.\n4. أجب بطريقة احترافية ومباشرة.";

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
              ['text' => $cleanMessage],
            ],
          ],
        ],
      ]);

    if ($response->successful()) {
      $payload = $response->json();

      return $payload['candidates'][0]['content']['parts'][0]['text'] ?? 'عذراً، لم أستطع معالجة الإجابة.';
    }

    $errorMessage = $response->json()['error']['message'] ?? 'خطأ في الاتصال بالخدمة.';

    return 'حدث خطأ من الذكاء الاصطناعي: ' . $errorMessage;
  }
}
