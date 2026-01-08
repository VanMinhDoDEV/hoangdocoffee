<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard</title>
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
    .measure-line { height: 1px; background: #E5E1DD; position: relative; }
    .measure-dot { width: 8px; height: 8px; background: #D4A3A3; border-radius: 9999px; position: absolute; top: -4px; }
    </style>
    </head>
<body class="p-6 lg:p-10">
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-10">
        <aside class="w-full lg:w-64 space-y-12">
            <div class="px-4 text-center lg:text-left">
                <a href="{{ route('home') }}" class="inline-block" aria-label="Trang chủ">
                    <img src="{{ $headerLogoUrl ?? asset('assets/img/logo.svg') }}" alt="logo" class="h-10 object-contain">
                </a>
                <p class="text-[9px] uppercase tracking-[0.4em] text-gray-400 mt-2">{{ $storeTagline ?? 'Dành riêng cho phái đẹp' }}</p>
            </div>
            <nav class="space-y-8">
                <div>
                    <p class="text-[10px] uppercase tracking-widest text-gray-300 mb-6 px-4 font-bold">Không gian cá nhân</p>
                    <ul class="space-y-4">
                        <li><a href="{{ route('client.dashboard') }}" class="flex items-center gap-4 px-4 py-2 text-sm italic font-serif text-xl border-l-2 border-black">Hành trình mua sắm</a></li>
                        <li><a href="{{ route('client.dashboard.orders') }}" class="flex items-center gap-4 px-4 py-2 text-sm text-gray-400 hover:text-black transition">Đơn hàng</a></li>
                        <li><a href="{{ route('client.wishlist') }}" class="flex items-center gap-4 px-4 py-2 text-sm text-gray-400 hover:text-black transition">Tủ đồ yêu thích</a></li>
                        <li><a href="{{ route('client.measurements') }}" class="flex items-center gap-4 px-4 py-2 text-sm text-gray-400 hover:text-black transition">Sổ tay số đo</a></li>
                </ul>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-widest text-gray-300 mb-6 px-4 font-bold">Cài đặt</p>
                    <ul class="space-y-4">
                        <li><a href="{{ route('client.addresses') }}" class="flex items-center gap-4 px-4 py-2 text-sm text-gray-400 hover:text-black transition">Địa chỉ nhận quà</a></li>
                        <li><a href="#" class="flex items-center gap-4 px-4 py-2 text-sm text-gray-400 hover:text-black transition">Liên hệ tư vấn</a></li>
                    </ul>
                </div>
            </nav>
        </aside>
        <main class="flex-1 space-y-12">
            <header class="flex flex-col md:flex-row justify-between items-end gap-6">
                <div class="space-y-2">
                    <h2 class="font-serif text-5xl italic tracking-tight">{{ ($user && $user->name) ? ($user->name.' thân mến,') : 'Khách hàng thân mến,' }}</h2>
                    <p class="text-sm text-gray-400 italic font-serif text-lg">Chúng tôi đã cập nhật những thiết kế vừa vặn với số đo của bạn.</p>
                </div>
                <div class="flex items-center gap-4 border-b border-gray-100 pb-2">
                    <i class="fa-solid fa-pencil text-[10px] text-gray-300"></i>
                </div>
            </header>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div class="md:col-span-2 space-y-12">
                        <div class="soft-card p-10">
                            <div class="flex justify-between items-center mb-10">
                                <h3 class="font-serif text-2xl italic">Lịch sử đơn hàng</h3>
                                <a href="{{ route('client.dashboard.orders') }}" class="text-[10px] uppercase tracking-widest border-b border-black">Xem tất cả</a>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="border-b border-gray-100 text-xs uppercase tracking-widest text-gray-400">
                                            <th class="py-4 font-normal">Mã đơn hàng</th>
                                            <th class="py-4 font-normal">Ngày đặt</th>
                                            <th class="py-4 font-normal">Tổng tiền</th>
                                            <th class="py-4 font-normal">Trạng thái</th>
                                            <th class="py-4 font-normal text-right">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm">
                                        @php $ordersList = ($journeyOrders ?? collect())->take(5); @endphp
                                        @forelse($ordersList as $o)
                                            <tr class="border-b border-gray-50 hover:bg-[#FDFBF9] transition duration-200">
                                                <td class="py-6 font-serif italic text-lg">#{{ str_pad($o->id, 6, '0', STR_PAD_LEFT) }}</td>
                                                <td class="py-6 text-gray-500">{{ $o->created_at->format('d/m/Y') }}</td>
                                                <td class="py-6 font-bold tracking-widest">{{ number_format((float)$o->total, 0, ',', '.') }}đ</td>
                                                <td class="py-6">
                                                    <span class="text-[10px] px-3 py-1 bg-[#F9F5F2] rounded-full text-[#B08D8D] uppercase tracking-widest">{{ ucfirst($o->status) }}</span>
                                                </td>
                                                <td class="py-6 text-right">
                                                    <a href="#" onclick="event.preventDefault(); openOrderModal({{ $o->id }})" class="text-[10px] uppercase tracking-widest border-b border-gray-300 hover:border-black transition pb-0.5">Xem chi tiết</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="py-8 text-center text-gray-400 italic">Chưa có đơn hàng nào.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-6 items-center bg-[#F4F1EE] rounded-[40px] p-2 pr-10 overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1497935586351-b67a49e012bf?w=600" class="w-full h-64 object-cover rounded-[35px]" alt="">
                            <div class="pl-6">
                                <p class="text-[10px] uppercase tracking-[0.3em] mb-4">Gợi ý hôm nay</p>
                                <h3 class="font-serif text-2xl italic leading-tight mb-6">Hương vị cà phê mới</h3>
                                <button class="text-xs font-bold uppercase border-b-2 border-pink-200 pb-1">Khám phá ngay</button>
                            </div>
                        </div>
                </div>
                <div class="space-y-10">
                    <div class="soft-card p-10 text-center space-y-6">
                        <h4 class="font-serif text-xl italic">Sở thích của bạn</h4>
                        <div class="flex flex-wrap justify-center gap-2">
                            <span class="text-[10px] px-3 py-1 border border-gray-100 rounded-full text-gray-400 italic">Robusta</span>
                            <span class="text-[10px] px-3 py-1 border border-gray-100 rounded-full text-gray-400 italic">Đậm đà</span>
                        </div>
                        <div class="pt-4">
                            <button class="w-full py-3 bg-[#1a1a1a] text-white text-[10px] uppercase tracking-widest rounded-[20px] hover:bg-opacity-80 transition">Cập nhật sở thích</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    @include('client.components.order-detail-modal')
</body>
</html>
