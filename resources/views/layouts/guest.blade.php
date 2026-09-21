<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'كافيه') }}</title>

    <!-- Google Fonts: Cairo -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-['Cairo'] bg-[#f9f8f3] text-[#2d312e] min-h-screen flex flex-col justify-between antialiased">

    <!-- 1. الناف بار الموحد -->
    @include('navigation-menu')

    <!-- 2. محتوى صفحة تسجيل الدخول / التسجيل -->
    <main class="flex-grow flex items-center justify-center py-12 px-4">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>