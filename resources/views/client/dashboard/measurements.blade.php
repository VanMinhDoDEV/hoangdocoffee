<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sổ tay số đo</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png') }}">
    <link rel="icon" type="image/png" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png') }}">
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
    :root { --soft-bg: #FDFBF9; --accent-pink: #E8D5D5; --text-main: #4A4444; }
    body { background-color: var(--soft-bg); font-family: 'Montserrat', sans-serif; color: var(--text-main); }
    .font-serif { font-family: 'Cormorant Garamond', serif; }
    .soft-card { background: #ffffff; border-radius: 50px 5px 50px 5px; border: 1px solid #F1EDE9; transition: all 0.5s ease; }
    .soft-card:hover { border-color: var(--accent-pink); box-shadow: 0 15px 30px rgba(232, 213, 213, 0.3); }
    </style>
</head>
<body class="p-6 lg:p-10">
    <div class="max-w-7xl mx-auto space-y-10">
        <header class="flex items-end justify-between">
            <div>
                <h1 class="font-serif text-4xl italic tracking-tight">Sổ tay số đo</h1>
                <p class="text-sm text-gray-400 italic">Lưu giữ thông số cơ thể để chọn size chính xác nhất.</p>
            </div>
            <a href="{{ route('client.dashboard') }}" class="text-[10px] uppercase tracking-widest border-b border-black">Về dashboard</a>
        </header>
        <div class="soft-card p-10 text-center space-y-6">
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 rounded-full bg-pink-50 flex items-center justify-center text-pink-300">
                    <i class="fa-solid fa-ruler-combined text-2xl"></i>
                </div>
            </div>
            <h3 class="font-serif text-2xl italic">Tính năng đang được phát triển</h3>
            <p class="text-sm text-gray-500 max-w-md mx-auto">Chúng tôi đang xây dựng tính năng này để giúp bạn lưu trữ số đo và nhận gợi ý size tự động. Vui lòng quay lại sau.</p>
            <div class="pt-4">
                <a href="{{ route('client.dashboard') }}" class="inline-block px-8 py-3 bg-[#1a1a1a] text-white text-[10px] uppercase tracking-widest rounded-[20px] hover:bg-opacity-80 transition">Quay lại trang chủ</a>
            </div>
        </div>
    </div>
</body>
</html>
