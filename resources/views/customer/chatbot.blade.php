<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>الشات بوت الذكي - الكافتيريا الذكية</title>
    
    <!-- Google Fonts: Cairo -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- استدعاء ملفات Tailwind و Livewire لعمل الـ navigation-menu بشكل صحيح -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        :root {
            /* ألوان المنيو المعتمدة */
            --orange-primary: #f97316;
            --orange-gradient: linear-gradient(135deg, #ff8c42 0%, #e85d04 100%);
            --green-sage: #87a878;
            --green-sage-gradient: linear-gradient(135deg, #97b888 0%, #6b8e60 100%);
            --bg-cream: #f9f8f3;
            --card-bg: #ffffff;
            --text-main: #2d312e;
            --chip-green: #e9edc9;
        }

        * {
            font-family: 'Cairo', sans-serif;
        }

        body {
            background-color: var(--bg-cream);
            color: var(--text-main);
            min-height: 100vh;
        }

        /* كروت وتنسيقات الشات بوت */
        .chat-section {
            padding: 30px 15px;
            max-width: 900px;
            margin: 0 auto;
        }

        .chat-card {
            background-color: var(--card-bg);
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            border: 1px solid #eae8df;
        }

        .chat-card-header {
            background: var(--green-sage-gradient);
            color: #ffffff;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .bot-badge {
            background-color: var(--chip-green);
            color: #2d312e;
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 700;
        }

        .status-dot {
            width: 10px;
            height: 10px;
            background-color: #55ffb2;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 8px #55ffb2;
        }

        .chat-container {
            background-color: #fcfcf9;
            padding: 24px;
            min-height: 360px;
            max-height: 460px;
            overflow-y: auto;
            border-bottom: 1px solid #f0efe9;
        }

        .chat-container::-webkit-scrollbar {
            width: 6px;
        }
        .chat-container::-webkit-scrollbar-thumb {
            background-color: #d1d5db;
            border-radius: 10px;
        }

        .chat-line {
            margin-bottom: 16px;
            padding: 14px 20px;
            border-radius: 20px;
            line-height: 1.6;
            font-size: 0.98rem;
            max-width: 80%;
            word-wrap: break-word;
            clear: both;
        }

        /* فقاعة المساعد */
        .chat-line:has(.bot-text) {
            background-color: #ffffff;
            color: var(--text-main);
            float: right;
            border: 1px solid #eae8df;
            border-bottom-right-radius: 4px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        }

        .bot-text {
            color: #5d7e52;
            font-weight: 800;
            display: block;
            margin-bottom: 4px;
            font-size: 0.85rem;
        }

        /* فقاعة المستخدم */
        .chat-line:has(.user-text) {
            background: var(--orange-gradient);
            color: #ffffff;
            float: left;
            border-bottom-left-radius: 4px;
            box-shadow: 0 4px 15px rgba(232, 93, 4, 0.2);
        }

        .user-text {
            color: #fff3db;
            font-weight: 800;
            display: block;
            margin-bottom: 4px;
            font-size: 0.85rem;
        }

        .chat-form-container {
            padding: 20px;
            background-color: #ffffff;
        }

        .chat-input {
            width: 100%;
            border: 2px solid #eee2d5;
            border-radius: 50px;
            padding: 12px 20px;
            font-size: 0.95rem;
            background-color: #faf9f5;
            outline: none;
            transition: all 0.2s ease;
        }

        .chat-input:focus {
            background-color: #ffffff;
            border-color: var(--orange-primary);
            box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.12);
        }

        .btn-send {
            background: var(--orange-gradient);
            color: #ffffff;
            border: none;
            border-radius: 50px;
            padding: 0 28px;
            font-weight: 800;
            font-size: 0.95rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(232, 93, 4, 0.25);
            transition: all 0.2s ease;
        }

        .btn-send:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(232, 93, 4, 0.35);
        }
    </style>
</head>
<body>

    <!-- 1. استدعاء الـ Navigation Menu الخاص بـ Jetstream / Breeze -->
    @include('navigation-menu')

    <!-- 2. محتوى صفحة الشات بوت -->
    <div class="chat-section">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-2xl font-black text-gray-800 flex items-center gap-2 m-0">
                <span>💬</span> الشات بوت الذكي
            </h3>
            <span class="bot-badge">
                ⚡ مدعوم بالذكاء الاصطناعي
            </span>
        </div>

        <div class="chat-card">
            <!-- رأس الكارت -->
            <div class="chat-card-header">
                <div class="flex items-center gap-3">
                    <div class="bg-white text-gray-800 rounded-full flex items-center justify-center shadow-sm" style="width: 45px; height: 45px; font-size: 1.3rem;">
                        🤖
                    </div>
                    <div>
                        <h5 class="font-extrabold text-lg m-0">مساعد الكافتيريا الذكي</h5>
                        <small class="flex items-center gap-2 opacity-90 text-sm">
                            <span class="status-dot"></span> جاهز لمساعدتك في اختيار وجبتك
                        </small>
                    </div>
                </div>
            </div>

            <!-- صندوق عرض المحادثة -->
            <div class="chat-container" id="chatBox">
                <div class="chat-line">
                    <span class="bot-text">المساعد:</span> مرحباً بك! 👋 كيف يمكنني مساعدتك اليوم في اختيار وجبتك أو المشروب المفضل؟
                </div>
            </div>

            <!-- نموذج كتابة الاستفسار -->
            <div class="chat-form-container">
                <form id="chatForm" class="flex gap-3">
                    <input type="text" id="userInput" class="chat-input" placeholder="...اكتب استفسارك (مثال: أهلاً، ما هو الطبق المفضل اليوم؟)" required autocomplete="off">
                    <button type="submit" class="btn-send">
                        <span>إرسال</span>
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    @livewireScripts

    <!-- Script منطق الشات بوت بدون أي تغيير -->
    <script>
        const chatForm = document.getElementById('chatForm');
        const userInput = document.getElementById('userInput');
        const chatBox = document.getElementById('chatBox');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const message = userInput.value.trim();
            if(!message) return;

            appendLine('أنت', message, 'user-text');
            userInput.value = '';

            fetch("{{ route('customer.chatbot.send') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    "Accept": "application/json"
                },
                body: JSON.stringify({ message: message })
            })
            .then(res => res.json())
            .then(data => {
                if(data && data.reply) {
                    appendLine('المساعد', data.reply, 'bot-text');
                } else {
                    appendLine('المساعد', 'أهلاً بك! يمكنك الاستفسار عن قائمة الوجبات والمشروبات المتاحة.', 'bot-text');
                }
            })
            .catch(err => {
                appendLine('المساعد', 'أهلاً بك! كيف يمكنني مساعدتك في المنيو اليوم؟', 'bot-text');
            });
        });

        function appendLine(sender, text, className) {
            const div = document.createElement('div');
            div.className = 'chat-line';
            div.innerHTML = `<span class="${className}">${sender}:</span> ${text}`;
            chatBox.appendChild(div);
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    </script>
    @include('components.footer')
</body>
</html>