@extends('layouts.admin')
@section('title', __('messages.warehouses'))
@section('content')
     <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
      <div class="p-6 flex items-center justify-between">
       <h3 class="text-lg font-semibold text-gray-800">{{ __('messages.warehouse_list') }}</h3>
       <button id="openCreateModal" class="px-3 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">{{ __('messages.add_warehouse') }}</button>
      </div>
      <div class="overflow-x-auto">
       <table class="min-w-full text-sm">
        <thead class="bg-gray-50">
         <tr>
          <th class="text-left px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.name') }}</th>
          <th class="text-left px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.code') }}</th>
          <th class="text-left px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.address') }}</th>
          <th class="text-left px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.status') }}</th>
          <th class="text-left px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.actions') }}</th>
         </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
         @foreach($warehouses as $w)
         <tr class="hover:bg-gray-50">
          <td class="px-6 py-3 font-medium text-gray-900">{{ $w->name }}</td>
          <td class="px-6 py-3 text-gray-700">{{ $w->code }}</td>
          <td class="px-6 py-3 text-gray-700">{{ $w->address ?? __('messages.unknown') }}</td>
          <td class="px-6 py-3">
           @if($w->is_active)
            <span class="px-2 py-1 rounded bg-green-50 text-green-700">{{ __('messages.active') }}</span>
           @else
            <span class="px-2 py-1 rounded bg-gray-100 text-gray-700">{{ __('messages.inactive') }}</span>
           @endif
          </td>
          <td class="px-6 py-3">
           <form action="{{ route('admin.warehouses.update', $w->id) }}" method="post" class="flex items-center gap-2">
            @csrf
            @method('PUT')
            <input type="text" name="name" value="{{ $w->name }}" class="input px-3 py-2 border border-slate-300 rounded-lg w-40">
            <input type="text" name="code" value="{{ $w->code }}" class="input px-3 py-2 border border-slate-300 rounded-lg w-28">
            <input type="text" name="address" value="{{ $w->address }}" class="input px-3 py-2 border border-slate-300 rounded-lg w-64">
            <label class="flex items-center gap-2 text-sm text-gray-700"><input type="checkbox" name="is_active" value="1" {{ $w->is_active ? 'checked' : '' }}> {{ __('messages.active') }}</label>
            <button class="px-3 py-2 rounded-lg bg-slate-700 text-white hover:bg-slate-800">{{ __('messages.save') }}</button>
           </form>
          </td>
         </tr>
         @endforeach
       </tbody>
       </table>
      </div>
      <div class="p-6">@include('components.pagination', ['paginator' => $warehouses])</div>
     </div>
     <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
      <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('messages.create_warehouse') }}</h3>
      <form action="{{ route('admin.warehouses.store') }}" method="post" class="space-y-3">
       @csrf
       <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
         <label class="block text-sm font-medium text-slate-700 mb-1" for="w_name">{{ __('messages.warehouse_name') }}</label>
         <input id="w_name" name="name" type="text" required class="input w-full px-3 py-2 border border-slate-300 rounded-lg">
        </div>
        <div>
         <label class="block text-sm font-medium text-slate-700 mb-1" for="w_code">{{ __('messages.warehouse_code') }}</label>
         <input id="w_code" name="code" type="text" required class="input w-full px-3 py-2 border border-slate-300 rounded-lg">
        </div>
        <div class="flex items-end">
         <label class="flex items-center gap-2 text-sm text-gray-700"><input type="checkbox" name="is_active" value="1" checked> {{ __('messages.active') }}</label>
        </div>
       </div>
       <div>
        <label class="block text-sm font-medium text-slate-700 mb-1" for="w_address">{{ __('messages.address') }}</label>
        <input id="w_address" name="address" type="text" class="input w-full px-3 py-2 border border-slate-300 rounded-lg">
       </div>
       <button class="px-3 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">{{ __('messages.create') }}</button>
      </form>
     </div>
@endsection
