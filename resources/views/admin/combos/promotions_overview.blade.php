@extends('layouts.admin')
@section('title', 'Tổng quan Khuyến mãi')
@section('content')
<div class="max-w-7xl mx-auto space-y-8">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Hiệu quả Khuyến mãi</h2>
      <p class="text-slate-500 text-sm mt-1">Báo cáo tổng hợp từ Combo, Mix & Match và Giá sỉ.</p>
    </div>
    <div class="flex gap-2">
      <div class="relative group">
        <button class="inline-flex items-center justify-center px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-xl transition-all shadow-lg shadow-slate-200">
          <i class="fa-solid fa-plus mr-2"></i> Tạo chương trình
        </button>
        <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-slate-100 hidden group-hover:block z-50 overflow-hidden">
          <a href="{{ route('admin.combos.index') }}" class="block px-4 py-3 hover:bg-slate-50 text-sm text-slate-700 border-b border-slate-50">
            <i class="fa-solid fa-layer-group text-indigo-500 mr-2 w-4"></i> Tạo Combo
          </a>
          <a href="{{ route('admin.promotion_rules.index') }}" class="block px-4 py-3 hover:bg-slate-50 text-sm text-slate-700 border-b border-slate-50">
            <i class="fa-solid fa-ticket text-rose-500 mr-2 w-4"></i> Tạo Mix & Match
          </a>
          <a href="{{ route('admin.volume_pricing.index') }}" class="block px-4 py-3 hover:bg-slate-50 text-sm text-slate-700">
            <i class="fa-solid fa-boxes-stacked text-emerald-500 mr-2 w-4"></i> Tạo Giá sỉ
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-start justify-between">
      <div>
        <p class="text-sm font-medium text-slate-500 mb-1">Tổng doanh thu KM</p>
        <h3 class="text-2xl font-bold text-slate-900">{{ number_format($stats['revenue'], 0, ',', '.') }}đ</h3>
        <div class="flex items-center mt-2 text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded w-fit">
          <i class="fa-solid fa-arrow-trend-up mr-1"></i> Theo kỳ: {{ $stats['period'] }}
        </div>
      </div>
      <div class="p-3 bg-indigo-50 rounded-xl text-indigo-600">
        <i class="fa-solid fa-sack-dollar text-xl"></i>
      </div>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-start justify-between">
      <div>
        <p class="text-sm font-medium text-slate-500 mb-1">Ngân sách đã chi (Discount)</p>
        <h3 class="text-2xl font-bold text-slate-900">{{ number_format($stats['discount_spent'], 0, ',', '.') }}đ</h3>
        <p class="text-xs text-slate-400 mt-2">Tỷ lệ: {{ $stats['revenue'] > 0 ? round($stats['discount_spent'] / $stats['revenue'] * 100, 1) : 0 }}%</p>
      </div>
      <div class="p-3 bg-rose-50 rounded-xl text-rose-600">
        <i class="fa-solid fa-hand-holding-dollar text-xl"></i>
      </div>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-start justify-between">
      <div>
        <p class="text-sm font-medium text-slate-500 mb-1">Chương trình hoạt động</p>
        <h3 class="text-2xl font-bold text-slate-900">{{ ($stats['active_programs']['combos'] + $stats['active_programs']['rules']) }}</h3>
        <div class="flex items-center gap-1 mt-2 text-xs">
          <span class="w-2 h-2 rounded-full bg-emerald-500"></span> {{ $stats['active_programs']['combos'] }} Combos
          <span class="w-2 h-2 rounded-full bg-blue-500 ml-1"></span> {{ $stats['active_programs']['rules'] }} Rules
        </div>
      </div>
      <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600">
        <i class="fa-solid fa-bolt text-xl"></i>
      </div>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-start justify-between">
      <div>
        <p class="text-sm font-medium text-slate-500 mb-1">Tỉ lệ chuyển đổi</p>
        <h3 class="text-2xl font-bold text-slate-900">{{ number_format($stats['conversion_rate'], 1) }}%</h3>
        <p class="text-xs text-slate-400 mt-2">Trên tổng session truy cập</p>
      </div>
      <div class="p-3 bg-amber-50 rounded-xl text-amber-600">
        <i class="fa-solid fa-chart-pie text-xl"></i>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm flex flex-col">
      <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">Chương trình hiệu quả nhất</h3>
        <div class="text-xs text-slate-500">Kỳ: {{ $stats['period'] }}</div>
      </div>
      <div class="overflow-x-auto flex-1">
        <table class="w-full text-left border-collapse">
          <thead class="bg-slate-50 text-xs uppercase text-slate-500 font-semibold">
            <tr>
              <th class="px-6 py-4">Tên chương trình</th>
              <th class="px-6 py-4">Loại hình</th>
              <th class="px-6 py-4 text-center">Đã bán</th>
              <th class="px-6 py-4 text-right">Doanh thu</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 text-sm">
            @forelse($topPrograms as $prog)
            <tr class="hover:bg-slate-50/50 transition-colors">
              <td class="px-6 py-4">
                <div class="font-medium text-slate-900">{{ $prog['name'] }}</div>
              </td>
              <td class="px-6 py-4">
                @php $label = $prog['type'] === 'combo' ? 'Combo' : 'Mix Match'; @endphp
                <span class="inline-flex items-center px-2 py-1 rounded {{ $prog['type'] === 'combo' ? 'bg-indigo-50 text-indigo-700 border border-indigo-100' : 'bg-rose-50 text-rose-700 border border-rose-100' }} text-xs font-semibold">
                  {{ $label }}
                </span>
              </td>
              <td class="px-6 py-4 text-center font-medium">{{ (int)($prog['sold'] ?? 0) }}</td>
              <td class="px-6 py-4 text-right font-bold text-slate-800">
                {{ is_numeric($prog['revenue'] ?? null) ? number_format($prog['revenue'], 0, ',', '.') . 'đ' : '-' }}
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="4" class="px-6 py-8 text-center text-slate-500 text-sm">Chưa có dữ liệu khuyến mãi trong kỳ</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 text-center">
        <a href="{{ route('admin.reports.revenue') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors">Xem báo cáo doanh thu <i class="fa-solid fa-arrow-right ml-1"></i></a>
      </div>
    </div>

    <div class="space-y-6">
      <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <h3 class="font-bold text-slate-800 mb-4">Phân bổ loại hình</h3>
        <div class="space-y-4">
          <div>
            <div class="flex justify-between text-sm mb-1">
              <span class="text-slate-600">Product Combos</span>
              @php
                $combosActive = (int)($stats['active_programs']['combos'] ?? 0);
                $rulesActive = (int)($stats['active_programs']['rules'] ?? 0);
                $vpCount = (int)($counts['volume_pricing'] ?? 0);
                $totalPrograms = $combosActive + $rulesActive + $vpCount;
                $pc = $totalPrograms > 0 ? round(($combosActive / $totalPrograms) * 100) : 0;
              @endphp
              <span class="font-medium text-slate-900">{{ $pc }}%</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2">
              <div class="bg-indigo-500 h-2 rounded-full" style="width: {{ $pc }}%"></div>
            </div>
          </div>
          <div>
            <div class="flex justify-between text-sm mb-1">
              <span class="text-slate-600">Mix & Match Rules</span>
              @php
                $pr = $totalPrograms > 0 ? round(($rulesActive / $totalPrograms) * 100) : 0;
              @endphp
              <span class="font-medium text-slate-900">{{ $pr }}%</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2">
              <div class="bg-rose-500 h-2 rounded-full" style="width: {{ $pr }}%"></div>
            </div>
          </div>
          <div>
            <div class="flex justify-between text-sm mb-1">
              <span class="text-slate-600">Volume Pricing</span>
              @php
                $vp = $totalPrograms > 0 ? max(0, 100 - $pc - $pr) : 0;
              @endphp
              <span class="font-medium text-slate-900">{{ $vp }}%</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2">
              <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $vp }}%"></div>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <h3 class="font-bold text-slate-800 mb-4">Cần chú ý</h3>
        <div class="space-y-4">
          @forelse($soonRules as $r)
          <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center flex-shrink-0 border border-blue-100">
              <i class="fa-regular fa-calendar-check"></i>
            </div>
            <div>
              <p class="text-sm font-medium text-slate-900">{{ $r->name }}</p>
              <p class="text-xs text-slate-500">Bắt đầu: {{ optional($r->starts_at)->format('d/m/Y H:i') }}</p>
            </div>
          </div>
          @empty
          <p class="text-xs text-slate-500">Chưa có chương trình sắp diễn ra</p>
          @endforelse
        </div>
      </div>
    </div>
  </div>

  <div>
    <h3 class="font-bold text-slate-800 mb-4 text-lg">Quản lý nhanh</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <a href="{{ route('admin.combos.index') }}" class="group block p-6 bg-indigo-50/50 hover:bg-indigo-50 border border-indigo-100 hover:border-indigo-200 rounded-2xl transition-all">
        <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-indigo-600 mb-4 group-hover:scale-110 transition-transform">
          <i class="fa-solid fa-layer-group text-xl"></i>
        </div>
        <h4 class="font-bold text-slate-900 mb-1">Product Combos</h4>
        <p class="text-sm text-slate-500">Tạo gói sản phẩm bán kèm (Bundle) với giá cố định.</p>
      </a>

      <a href="{{ route('admin.promotion_rules.index') }}" class="group block p-6 bg-rose-50/50 hover:bg-rose-50 border border-rose-100 hover:border-rose-200 rounded-2xl transition-all">
        <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-rose-600 mb-4 group-hover:scale-110 transition-transform">
          <i class="fa-solid fa-ticket text-xl"></i>
        </div>
        <h4 class="font-bold text-slate-900 mb-1">Mix & Match Rules</h4>
        <p class="text-sm text-slate-500">Thiết lập quy tắc giảm giá (VD: Mua X tặng Y, Giảm %).</p>
      </a>

      <a href="{{ route('admin.volume_pricing.index') }}" class="group block p-6 bg-emerald-50/50 hover:bg-emerald-50 border border-emerald-100 hover:border-emerald-200 rounded-2xl transition-all">
        <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-emerald-600 mb-4 group-hover:scale-110 transition-transform">
          <i class="fa-solid fa-boxes-stacked text-xl"></i>
        </div>
        <h4 class="font-bold text-slate-900 mb-1">Volume Pricing</h4>
        <p class="text-sm text-slate-500">Cấu hình bảng giá sỉ tự động theo số lượng sản phẩm.</p>
      </a>
    </div>
  </div>
</div>
@endsection
