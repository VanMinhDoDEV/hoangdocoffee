@extends('layouts.admin')

@section('title', __('messages.customer_details'))

@section('content')
<div class="w-full min-h-screen" style="background-color: #f8fafc;">
    <main class="mx-auto">
        <!-- Header Section -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">{{ __('messages.customer') }} #{{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}</h1>
                    <p class="text-sm text-slate-500 mt-1">{{ __('messages.registered_at') }} {{ $user->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.customers') }}" class="px-4 py-2.5 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2 border border-slate-300 text-slate-700 hover:bg-slate-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        <span>{{ __('messages.back') }}</span>
                    </a>
                    <form action="{{ route('admin.customers.destroy', $user->id) }}" method="POST" onsubmit="return confirm('{{ __('messages.delete_customer_confirm') }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            <span>{{ __('messages.delete_customer') }}</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Sidebar - Customer Info -->
            <aside class="lg:w-80 flex-shrink-0">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <!-- Avatar -->
                    <div class="flex flex-col items-center mb-6">
                        @php
                            $initials = '';
                            $parts = preg_split('/\s+/', trim($user->name));
                            foreach ($parts as $i => $p) { if ($i >= 2) break; $initials .= strtoupper(mb_substr($p,0,1)); }
                        @endphp
                        <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-3xl font-bold mb-3">
                            {{ $initials ?: 'U' }}
                        </div>
                        <h2 class="text-xl font-bold text-slate-800 text-center">{{ $user->name }}</h2>
                        <p class="text-sm text-slate-500">ID: #{{ $user->id }}</p>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <div class="flex items-center justify-center mb-2">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                            </div>
                            <p class="text-2xl font-bold text-blue-600 text-center">{{ $stats['orders_count'] }}</p>
                            <p class="text-xs text-slate-600 mt-1 text-center">{{ __('messages.orders') }}</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4">
                            <div class="flex items-center justify-center mb-2">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <p class="text-2xl font-bold text-green-600 text-center" title="{{ number_format($stats['total_spent'], 0, ',', '.') }} đ">
                                {{ $stats['total_spent'] > 1000000 ? round($stats['total_spent']/1000000, 1) . 'M' : number_format($stats['total_spent'], 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-slate-600 mt-1 text-center">{{ __('messages.total_spent') }}</p>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="border-t pt-4">
                        <h3 class="text-sm font-semibold text-slate-700 mb-3">{{ __('messages.detailed_info') }}</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-start">
                                <span class="text-sm text-slate-600">{{ __('messages.email') }}:</span>
                                <span class="text-sm font-medium text-slate-800 break-all text-right ml-2">{{ $user->email }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-600">{{ __('messages.status') }}:</span>
                                @if($user->email_verified_at)
                                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">{{ __('messages.verified') }}</span>
                                @else
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-medium rounded-full">{{ __('messages.unverified') }}</span>
                                @endif
                            </div>
                            <div class="flex justify-between items-start">
                                <span class="text-sm text-slate-600">{{ __('messages.phone_number') }}:</span>
                                <span class="text-sm font-medium text-slate-800">{{ $address->phone ?? __('messages.not_updated') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-600">{{ __('messages.city') }}:</span>
                                <span class="text-sm font-medium text-slate-800">{{ $address->city ?? 'N/A' }}</span>
                            </div>
                             <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-600">{{ __('messages.role') }}:</span>
                                <span class="text-sm font-medium text-slate-800 uppercase">{{ $user->role }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Content Area -->
            <main class="flex-1">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <!-- Tabs Navigation -->
                    <nav class="border-b">
                        <div class="flex space-x-1 px-6 overflow-x-auto">
                            <button class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-blue-600 text-blue-600 whitespace-nowrap" onclick="switchTab('overview', this)">{{ __('messages.overview') }}</button>
                            <button class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-slate-600 hover:text-slate-800 hover:border-slate-300 transition-colors whitespace-nowrap" onclick="switchTab('address', this)">{{ __('messages.address_shipping') }}</button>
                            <button class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-slate-600 hover:text-slate-800 hover:border-slate-300 transition-colors whitespace-nowrap" onclick="switchTab('security', this)">{{ __('messages.security') }}</button>
                            <button class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-slate-600 hover:text-slate-800 hover:border-slate-300 transition-colors whitespace-nowrap" onclick="switchTab('notifications', this)">{{ __('messages.notifications') }}</button>
                        </div>
                    </nav>

                    <!-- Tab Contents -->
                    <div class="p-6">
                        <!-- Overview Tab -->
                        <div id="tab-overview" class="tab-content block">
                            <!-- Orders Table -->
                            <div>
                                <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4">
                                    <h3 class="text-lg font-semibold text-slate-800">{{ __('messages.order_history') }}</h3>
                                    <div class="relative w-full sm:w-auto">
                                        <input type="text" id="search-order" placeholder="{{ __('messages.search_order') }}..." class="pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">
                                        <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                    </div>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <thead class="bg-slate-50 border-b border-slate-200">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ __('messages.order_id') }}</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ __('messages.order_date') }}</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ __('messages.status') }}</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ __('messages.total_amount') }}</th>
                                                <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">{{ __('messages.actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-200" id="orders-table-body">
                                            @forelse($user->orders as $order)
                                            <tr class="hover:bg-slate-50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">#{{ $order->id }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $order->created_at->format('d/m/Y') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @php
                                                        $statusClasses = [
                                                            'pending' => 'bg-yellow-100 text-yellow-700',
                                                            'processing' => 'bg-blue-100 text-blue-700',
                                                            'completed' => 'bg-green-100 text-green-700',
                                                            'cancelled' => 'bg-red-100 text-red-700',
                                                        ];
                                                        $statusLabels = [
                                                            'pending' => __('messages.pending'),
                                                            'processing' => __('messages.processing'),
                                                            'completed' => __('messages.completed'),
                                                            'cancelled' => __('messages.cancelled'),
                                                        ];
                                                        $st = strtolower($order->status);
                                                    @endphp
                                                    <span class="px-3 py-1 text-xs font-medium rounded-full {{ $statusClasses[$st] ?? 'bg-gray-100 text-gray-700' }}">
                                                        {{ $statusLabels[$st] ?? $order->status }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900">{{ number_format($order->total, 0, ',', '.') }} đ</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                                    <a href="{{ route('orders.show', $order->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">{{ __('messages.details') }}</a>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" class="px-6 py-8 text-center text-slate-500">{{ __('messages.no_orders_yet') }}</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Address Tab -->
                        <div id="tab-address" class="tab-content hidden">
                            <div class="max-w-3xl">
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-lg font-semibold text-slate-800">{{ __('messages.address_payment') }}</h3>
                                    <button type="button" class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium" onclick="openAddAddressModal()">{{ __('messages.add_address') }}</button>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    @forelse($user->addresses as $addr)
                                        <div class="bg-slate-50 rounded-lg p-6">
                                            <div class="flex justify-between items-start mb-4">
                                                <h4 class="font-medium text-slate-800 flex items-center gap-2">
                                                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10l9-7 9 7v8a2 2 0 01-2 2h-2a2 2 0 01-2-2V12H9v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-8z"/>
                                                    </svg>
                                                    <span>{{ $addr->name }}</span>
                                                </h4>
                                                @if($addr->is_default)
                                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.62L12 2 9.19 8.62 2 9.24l5.46 4.73L5.82 21z"/>
                                                        </svg>
                                                        <span>{{ __('messages.default') }}</span>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-sm text-slate-600 space-y-2">
                                                <div class="flex items-start gap-2">
                                                    <svg class="w-4 h-4 text-slate-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A3 3 0 017 17h10a3 3 0 011.879.804M15 11a3 3 0 11-6 0 3 3 0 016 0"/>
                                                    </svg>
                                                    <span class="font-medium text-slate-800">{{ $user->name }}</span>
                                                </div>
                                                <div class="flex items-start gap-2">
                                                    <svg class="w-4 h-4 text-slate-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10l9-7 9 7M4 10h16v10H4V10z"/>
                                                    </svg>
                                                    <span>{{ $addr->address_line }}</span>
                                                </div>
                                                @if($addr->ward)
                                                <div class="flex items-start gap-2">
                                                    <svg class="w-4 h-4 text-slate-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A2 2 0 013 15.382V11a9 9 0 1118 0v4.382a2 2 0 01-1.553 1.894L14 20"/>
                                                    </svg>
                                                    <span>{{ $addr->ward }}</span>
                                                </div>
                                                @endif
                                                <div class="flex items-start gap-2">
                                                    <svg class="w-4 h-4 text-slate-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a10 10 0 100 20 10 10 0 000-20zM12 2c2.5 4 2.5 16 0 20M2 12h20"/>
                                                    </svg>
                                                    <span>{{ $addr->city }}</span>
                                                </div>
                                                @if($addr->phone)
                                                <div class="flex items-start gap-2">
                                                    <svg class="w-4 h-4 text-slate-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h2a2 2 0 012 2v3a2 2 0 01-2 2H5v2a10 10 0 0010 10h2a2 2 0 002-2v-2a2 2 0 00-2-2h-3a2 2 0 01-2-2"/>
                                                    </svg>
                                                    <span>{{ $addr->phone }}</span>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="mt-4 flex gap-3">
                                                <button type="button" class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center gap-2" onclick="openEditAddress({{ $addr->id }}, '{{ e($addr->name) }}', '{{ e($addr->phone) }}', '{{ e($addr->address_line) }}', '{{ e($addr->ward) }}', '{{ e($addr->city) }}', {{ $addr->is_default ? 'true' : 'false' }})">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    <span>Sửa</span>
                                                </button>
                                                @if(!$addr->is_default)
                                                <form method="POST" action="{{ route('admin.customers.addresses.default', [$user->id, $addr->id]) }}">
                                                    @csrf
                                                    <button type="submit" class="text-slate-600 hover:text-slate-800 text-sm font-medium flex items-center gap-2">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.62L12 2 9.19 8.62 2 9.24l5.46 4.73L5.82 21z"/>
                                                        </svg>
                                                        <span>Đặt mặc định</span>
                                                    </button>
                                                </form>
                                                @endif
                                                <form method="POST" action="{{ route('admin.customers.addresses.destroy', [$user->id, $addr->id]) }}" onsubmit="return confirm('Xóa địa chỉ này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-700 text-sm font-medium flex items-center gap-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        <span>Xóa</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-span-2">
                                            <p class="text-slate-500">Chưa có địa chỉ nào được lưu.</p>
                                        </div>
                                    @endforelse
                                </div>
                                <div class="mt-8">
                                    <h4 class="font-medium text-slate-800 mb-4">Phương thức thanh toán</h4>
                                    <div class="space-y-4">
                                        <div class="bg-slate-50 rounded-lg p-6 flex justify-between items-center">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-8 bg-gradient-to-r from-blue-600 to-blue-400 rounded flex items-center justify-center text-white text-xs font-bold">VISA</div>
                                                <div>
                                                    <p class="font-medium text-slate-800">•••• •••• •••• 4532</p>
                                                    <p class="text-sm text-slate-500">Hết hạn 12/25</p>
                                                </div>
                                            </div>
                                            <button type="button" class="text-blue-600 hover:text-blue-700 text-sm font-medium">Sửa</button>
                                        </div>
                                        <div class="bg-slate-50 rounded-lg p-6 flex justify-between items-center">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-8 bg-gradient-to-r from-orange-600 to-red-600 rounded flex items-center justify-center text-white text-xs font-bold">MC</div>
                                                <div>
                                                    <p class="font-medium text-slate-800">•••• •••• •••• 8791</p>
                                                    <p class="text-sm text-slate-500">Hết hạn 08/24</p>
                                                </div>
                                            </div>
                                            <button type="button" class="text-blue-600 hover:text-blue-700 text-sm font-medium">Sửa</button>
                                        </div>
                                    </div>
                                    <button type="button" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">Thêm phương thức thanh toán</button>
                                </div>
                            </div>
                        </div>

                        <div id="tab-security" class="tab-content hidden">
                            <div class="max-w-2xl">
                                <h3 class="text-lg font-semibold text-slate-800 mb-6">Cài đặt bảo mật</h3>
                                <div class="space-y-6">
                                    <div class="bg-slate-50 rounded-lg p-6">
                                        <h4 class="font-medium text-slate-800 mb-2">Mật khẩu</h4>
                                        <form method="POST" action="{{ route('admin.customers.update', $user->id) }}" class="space-y-4">
                                            @csrf
                                            @method('PUT')
                                            <div>
                                                <label class="block text-sm font-medium text-slate-700 mb-1">Mật khẩu mới</label>
                                                <input type="password" name="password" class="w-full px-4 py-2 border border-slate-300 rounded-lg" minlength="6" required>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-slate-700 mb-1">Xác nhận mật khẩu</label>
                                                <input type="password" name="password_confirmation" class="w-full px-4 py-2 border border-slate-300 rounded-lg" minlength="6" required>
                                            </div>
                                            <div>
                                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">Đổi mật khẩu</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="bg-slate-50 rounded-lg p-6">
                                        <h4 class="font-medium text-slate-800 mb-2">Xác thực hai lớp</h4>
                                        <div class="flex items-center gap-3">
                                            <span class="px-3 py-1 bg-slate-100 text-slate-700 text-xs font-medium rounded-full">Tạm thời chưa cấu hình</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="tab-notifications" class="tab-content hidden">
                            <div class="max-w-2xl">
                                <h3 class="text-lg font-semibold text-slate-800 mb-6">Tùy chọn thông báo</h3>
                                <div class="space-y-6">
                                    <div class="bg-slate-50 rounded-lg p-6">
                                        <h4 class="font-medium text-slate-800 mb-4">Email</h4>
                                        <div class="space-y-4">
                                            <label class="flex items-center justify-between cursor-pointer">
                                                <div>
                                                    <p class="font-medium text-slate-800">Cập nhật đơn hàng</p>
                                                    <p class="text-sm text-slate-600">Thông báo thay đổi trạng thái đơn hàng</p>
                                                </div>
                                                <input type="checkbox" class="w-5 h-5 text-blue-600 rounded">
                                            </label>
                                            <label class="flex items-center justify-between cursor-pointer">
                                                <div>
                                                    <p class="font-medium text-slate-800">Khuyến mãi</p>
                                                    <p class="text-sm text-slate-600">Nhận email ưu đãi đặc biệt</p>
                                                </div>
                                                <input type="checkbox" class="w-5 h-5 text-blue-600 rounded">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="bg-slate-50 rounded-lg p-6">
                                        <h4 class="font-medium text-slate-800 mb-4">SMS</h4>
                                        <div class="space-y-4">
                                            <label class="flex items-center justify-between cursor-pointer">
                                                <div>
                                                    <p class="font-medium text-slate-800">Thông báo giao hàng</p>
                                                    <p class="text-sm text-slate-600">Khi đơn hàng sắp giao</p>
                                                </div>
                                                <input type="checkbox" class="w-5 h-5 text-blue-600 rounded" checked>
                                            </label>
                                            <label class="flex items-center justify-between cursor-pointer">
                                                <div>
                                                    <p class="font-medium text-slate-800">Cảnh báo bảo mật</p>
                                                    <p class="text-sm text-slate-600">Thông báo quan trọng về bảo mật</p>
                                                </div>
                                                <input type="checkbox" class="w-5 h-5 text-blue-600 rounded" checked>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </main>
</div>

<script>
    function switchTab(tabId, btn) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(el => {
            el.classList.add('hidden');
            el.classList.remove('block');
        });
        
        // Show selected tab
        document.getElementById('tab-' + tabId).classList.remove('hidden');
        document.getElementById('tab-' + tabId).classList.add('block');
        
        // Reset buttons
        document.querySelectorAll('.tab-button').forEach(b => {
            b.classList.remove('border-blue-600', 'text-blue-600');
            b.classList.add('border-transparent', 'text-slate-600');
        });
        
        // Active button
        btn.classList.remove('border-transparent', 'text-slate-600');
        btn.classList.add('border-blue-600', 'text-blue-600');
    }

    // Simple search for orders
    document.getElementById('search-order').addEventListener('input', function(e) {
        const val = e.target.value.toLowerCase();
        document.querySelectorAll('#orders-table-body tr').forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(val) ? '' : 'none';
        });
    });

    function openEditAddress(id, name, phone, address_line, ward, city, is_default) {
        const modal = document.getElementById('edit-address-modal');
        modal.classList.remove('hidden');
        document.getElementById('edit_name').value = name || '';
        document.getElementById('edit_phone').value = phone || '';
        document.getElementById('edit_address_line').value = address_line || '';
        document.getElementById('edit_ward').value = ward || '';
        document.getElementById('edit_city').value = city || '';
        document.getElementById('edit_is_default').checked = !!is_default;
        const form = document.getElementById('edit-address-form');
        form.action = "{{ url('admin/customers/'.$user->id.'/addresses') }}/" + id;
    }
    function closeEditAddress() {
        const modal = document.getElementById('edit-address-modal');
        modal.classList.add('hidden');
    }
</script>
<div id="add-address-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/36" onclick="closeAddAddressModal()"></div>
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-xl rounded-2xl shadow-2xl bg-white relative">
        <button type="button" onclick="closeAddAddressModal()" class="absolute top-3 right-3 p-2 rounded-lg hover:bg-slate-100">
            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <form id="add-address-form" method="POST" action="{{ route('admin.customers.addresses.store', $user->id) }}" class="p-6">
            @csrf
            <h3 class="text-xl font-bold mb-4 text-slate-800">Thêm địa chỉ</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Người liên hệ</label>
                    <input id="add_name" type="text" name="name" class="w-full px-4 py-2 border border-slate-300 rounded-lg" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Số điện thoại</label>
                    <input id="add_phone" type="text" name="phone" class="w-full px-4 py-2 border border-slate-300 rounded-lg">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Địa chỉ (số nhà, đường)</label>
                    <input id="add_address_line" type="text" name="address_line" class="w-full px-4 py-2 border border-slate-300 rounded-lg" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tỉnh/Thành phố</label>
                    <select id="add_city_select" class="w-full px-4 py-2 border border-slate-300 rounded-lg"></select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Phường/Xã</label>
                    <select id="add_ward_select" class="w-full px-4 py-2 border border-slate-300 rounded-lg" disabled></select>
                </div>
                <input type="hidden" id="add_city" name="city">
                <input type="hidden" id="add_ward" name="ward">
                <div class="md:col-span-2 flex items-center gap-2">
                    <input id="add_is_default" type="checkbox" name="is_default" value="1" class="w-4 h-4 text-blue-600 rounded">
                    <label class="text-sm text-slate-700">Đặt làm địa chỉ mặc định</label>
                </div>
                <div class="md:col-span-2 flex justify-end gap-3">
                    <button type="button" onclick="closeAddAddressModal()" class="px-4 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50">Hủy</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Thêm</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    let provincesData = [];
    function openAddAddressModal() {
        const m = document.getElementById('add-address-modal');
        m.classList.remove('hidden');
        if (provincesData.length === 0) {
            loadProvinces();
        }
    }
    function closeAddAddressModal() {
        const m = document.getElementById('add-address-modal');
        m.classList.add('hidden');
    }
    async function loadProvinces() {
        const citySel = document.getElementById('add_city_select');
        citySel.innerHTML = '<option value="">Đang tải...</option>';
        try {
            // Use internal proxy route
            const res = await fetch('{{ route('admin.api.provinces') }}');
            const json = await res.json();
            const data = json.data || json;
            provincesData = Array.isArray(data) ? data : [];
            citySel.innerHTML = '<option value="">Chọn tỉnh/thành</option>';
            provincesData.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.name;
                opt.textContent = p.name;
                opt.dataset.code = p.code;
                citySel.appendChild(opt);
            });
            citySel.disabled = false;
        } catch (e) {
            citySel.innerHTML = '<option value="">Không tải được dữ liệu</option>';
        }
    }
    document.getElementById('add_city_select').addEventListener('change', async function() {
        const wardSel = document.getElementById('add_ward_select');
        const cityHidden = document.getElementById('add_city');
        const wardHidden = document.getElementById('add_ward');
        
        cityHidden.value = this.value || '';
        wardHidden.value = '';
        
        wardSel.innerHTML = '<option value="">Đang tải phường/xã...</option>';
        wardSel.disabled = true;
        
        const code = this.selectedOptions[0]?.dataset.code;
        if (!code) {
            wardSel.innerHTML = '<option value="">Chọn phường/xã</option>';
            return;
        }
        
        try {
            // Use internal proxy route
            const res = await fetch(`/admin/api/provinces/${code}/communes`);
            const json = await res.json();
            const data = json.data || json;
            const wards = Array.isArray(data) ? data : [];
            
            wardSel.innerHTML = '<option value="">Chọn phường/xã</option>';
            wards.forEach(w => {
                const opt = document.createElement('option');
                opt.value = w.name;
                opt.textContent = w.name;
                opt.dataset.code = w.code;
                wardSel.appendChild(opt);
            });
            wardSel.disabled = false;
        } catch (e) {
            wardSel.innerHTML = '<option value="">Không tải được dữ liệu</option>';
        }
    });
    // Removed district listener as API does not support it
    document.getElementById('add_ward_select').addEventListener('change', function() {
        const wardHidden = document.getElementById('add_ward');
        wardHidden.value = this.value || '';
    });
</script>
<div id="edit-address-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/36" onclick="closeEditAddress()"></div>
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-xl rounded-2xl shadow-2xl bg-white relative">
        <button type="button" onclick="closeEditAddress()" class="absolute top-3 right-3 p-2 rounded-lg hover:bg-slate-100">
            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <form id="edit-address-form" method="POST" class="p-6">
            @csrf
            @method('PUT')
            <h3 class="text-xl font-bold mb-4 text-slate-800">Sửa địa chỉ</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Người liên hệ</label>
                    <input id="edit_name" type="text" name="name" class="w-full px-4 py-2 border border-slate-300 rounded-lg" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Số điện thoại</label>
                    <input id="edit_phone" type="text" name="phone" class="w-full px-4 py-2 border border-slate-300 rounded-lg">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Địa chỉ</label>
                    <input id="edit_address_line" type="text" name="address_line" class="w-full px-4 py-2 border border-slate-300 rounded-lg" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Phường/Xã</label>
                    <input id="edit_ward" type="text" name="ward" class="w-full px-4 py-2 border border-slate-300 rounded-lg">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tỉnh/Thành phố</label>
                    <input id="edit_city" type="text" name="city" class="w-full px-4 py-2 border border-slate-300 rounded-lg">
                </div>
                <div class="md:col-span-2 flex items-center gap-2">
                    <input id="edit_is_default" type="checkbox" name="is_default" value="1" class="w-4 h-4 text-blue-600 rounded">
                    <label class="text-sm text-slate-700">Đặt làm địa chỉ mặc định</label>
                </div>
                <div class="md:col-span-2 flex justify-end gap-3">
                    <button type="button" onclick="closeEditAddress()" class="px-4 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50">Hủy</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Lưu</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
