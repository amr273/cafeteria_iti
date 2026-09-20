<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>الشات بوت الذكي</title>
    <!-- Bootstrap 5 RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: sans-serif; }
        .chat-container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            min-height: 250px;
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #e0e0e0;
        }
        .chat-line { margin-bottom: 12px; line-height: 1.6; }
        .bot-text { color: #0d6efd; font-weight: bold; }
        .user-text { color: #212529; font-weight: bold; }
    </style>
</head>
<body>

    <!-- شريط العناوين -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">☕ الكافتيريا الذكية</a>
            <div class="navbar-nav">
                <a class="nav-link" href="{{ route('customer.menu') }}">القائمة</a>
                <a class="nav-link" href="{{ route('customer.recommendations') }}">التوصيات الذكية</a>
                <a class="nav-link active fw-bold text-primary" href="#">الشات بوت</a>
                <a class="nav-link" href="{{ route('customer.orders') }}">طلباتي</a>
            </div>
        </div>
    </nav>

    <div class="container py-3">
        <h3 class="mb-4 fw-bold">💬 الشات بوت الذكي</h3>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">

                <!-- صندوق عرض المحادثة -->
                <div class="chat-container mb-3" id="chatBox">
                    <div class="chat-line">
                        <span class="bot-text">المساعد:</span> مرحباً! كيف يمكنني مساعدتك اليوم؟
                    </div>
                </div>

                <!-- نموذج كتابة الاستفسار -->
                <form id="chatForm" class="d-flex gap-2">
                    <input type="text" id="userInput" class="form-control" placeholder="...اكتب استفسارك (مثال: أهلاً، أو ما الطبق المفضل؟)" required autocomplete="off">
                    <button type="submit" class="btn btn-primary px-4 fw-bold">إرسال</button>
                </form>

            </div>
        </div>
    </div>

    <script>
        const chatForm = document.getElementById('chatForm');
        const userInput = document.getElementById('userInput');
        const chatBox = document.getElementById('chatBox');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const message = userInput.value.trim();
            if(!message) return;

            // عرض رسالة المستخدم
            appendLine('أنت', message, 'user-text');
            userInput.value = '';

            // إرسال الطلب للسيرفر
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
</body>
</html>
