@extends('layouts.app')

@section('content')
<style>
  body { box-sizing: border-box; }
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
  * { font-family: 'Inter', sans-serif; }
  .stat-card { transition: all 0.3s ease; }
  .stat-card:hover { transform: translateY(-4px); box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1); }
  .table-row { transition: background-color 0.2s ease; }
  .table-row:hover { background-color: #f9fafb; }
  .status-badge { display: inline-flex; align-items: center; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 500; }
  .status-paid { background-color: #d1fae5; color: #065f46; }
  .status-pending { background-color: #fef3c7; color: #92400e; }
  .status-failed { background-color: #fee2e2; color: #991b1b; }
  .status-cancelled { background-color: #f3f4f6; color: #374151; }
  .delivery-badge { display: inline-flex; align-items: center; padding: 4px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; background-color: #eff6ff; color: #1e40af; }
  .action-btn { transition: all 0.2s ease; }
  .action-btn:hover { transform: scale(1.05); }
  .pagination-btn { transition: all 0.2s ease; }
  .pagination-btn:hover:not(.active):not(:disabled) { background-color: #f3f4f6; }
  .pagination-btn.active { background-color: #3b82f6; color: white; }
  .avatar-circle { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 14px; color: white; }
  .action-menu { animation: slideIn 0.15s ease-out; }
  @keyframes slideIn { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
  .action-menu-item { transition: all 0.15s ease; }
  .action-menu-item:active { transform: scale(0.98); }
</style>
@php
  if (!function_exists('getPaymentStatusClass')) {
    function getPaymentStatusClass($status) {
      $s = strtolower((string)$status);
      if ($s === 'paid') return 'status-paid';
      if ($s === 'pending') return 'status-pending';
      if ($s === 'failed') return 'status-failed';
      if ($s === 'cancelled') return 'status-cancelled';
      return 'status-pending';
    }
  }
  $pendingOrders = $stats['pending'] ?? 0;
  $completedOrders = $stats['completed'] ?? 0;
  $refundedOrders = $stats['refunded'] ?? 0;
  $failedOrders = $stats['failed'] ?? 0;
@endphp
<div class="w-full min-h-full p-8">
  <div class="mb-8">
    <h1 id="page-title" class="text-3xl font-bold text-gray-900">Order List</h1>
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-100 p-6">
      <div class="flex items-center justify-between">
        <div>
          <p id="pending-label" class="text-sm text-gray-600 mb-1">Pending Payment</p>
          <p class="text-3xl font-bold text-gray-900">{{ $pendingOrders }}</p>
        </div>
        <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
          <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
      </div>
    </div>
    <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-100 p-6">
      <div class="flex items-center justify-between">
        <div>
          <p id="completed-label" class="text-sm text-gray-600 mb-1">Completed</p>
          <p class="text-3xl font-bold text-gray-900">{{ $completedOrders }}</p>
        </div>
        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
          <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
      </div>
    </div>
    <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-100 p-6">
      <div class="flex items-center justify-between">
        <div>
          <p id="refunded-label" class="text-sm text-gray-600 mb-1">Refunded</p>
          <p class="text-3xl font-bold text-gray-900">{{ $refundedOrders }}</p>
        </div>
        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
          <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" /></svg>
        </div>
      </div>
    </div>
    <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-100 p-6">
      <div class="flex items-center justify-between">
        <div>
          <p id="failed-label" class="text-sm text-gray-600 mb-1">Failed</p>
          <p class="text-3xl font-bold text-gray-900">{{ $failedOrders }}</p>
        </div>
        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
          <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
      </div>
    </div>
  </div>
  <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100">
      <div class="flex items-center justify-between">
        <div class="relative flex-1 max-w-md">
          <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg><input id="search-input" type="text" placeholder="Search Order" class="input-primary pl-10 pr-4">
        </div>
        <div class="ml-4 flex items-center gap-2"><select class="input-primary"> <option>10</option> <option>25</option> <option>50</option> <option>100</option> </select></div>
      </div>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="px-6 py-4 text-left"><input type="checkbox" id="select-all-checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 cursor-pointer"></th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Order</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Payment</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Method</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody id="orders-table" class="divide-y divide-gray-100"></tbody>
      </table>
    </div>
  </div>
</div>
@endsection
