<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn hàng</title>
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
                <h1 class="font-serif text-4xl italic tracking-tight">Đơn hàng của bạn</h1>
                <p class="text-sm text-gray-400 italic">Theo dõi trạng thái và chi tiết từng đơn.</p>
            </div>
            <a href="{{ route('client.dashboard') }}" class="text-[10px] uppercase tracking-widest border-b border-black">Về dashboard</a>
        </header>
        <div class="soft-card p-8">
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
                        @forelse($orders as $o)
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
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
    @include('client.components.order-detail-modal')
</body>
</html>
