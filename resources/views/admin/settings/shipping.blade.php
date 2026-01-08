@extends('layouts.admin')
@section('title', __('messages.shipping_settings'))
@section('content')
<div class="bg-white border-b border-gray-200 sticky top-0 z-10">
<div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
 <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 {{ session('status') ? '' : 'hidden' }}">
  <div class="flex items-center">
   <div class="flex-shrink-0">
    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
   </div>
   <div class="ml-3">
    <p class="text-sm font-medium text-green-800">{{ session('status') ?? __('messages.shipping_settings_updated') }}</p>
   </div>
  </div>
 </div>
 <form method="post" action="{{ route('admin.settings.shipping.save') }}" id="shipping-form">
  @csrf
  <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
   <div class="px-6 py-4 border-b border-gray-200">
    <h3 id="shipping-methods-title" class="text-lg font-semibold text-gray-900 leading-tight">{{ __('messages.shipping_methods') }}</h3>
    <p class="text-sm text-gray-500 mt-1">{{ __('messages.shipping_methods_desc') }}</p>
   </div>
   <div class="p-6">
    <div class="space-y-4">
     <div class="flex items-center justify-between pb-4 border-b border-gray-100">
      <div class="flex items-start">
       <div class="flex-shrink-0 w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center mr-4">
        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
       </div>
       <div>
        <h4 class="text-sm font-semibold text-gray-900 leading-tight">{{ __('messages.shipping_standard_title') }}</h4>
        <p class="text-sm text-gray-500 mt-0.5">{{ __('messages.shipping_standard_desc_text') }}</p>
       </div>
      </div>
      <div class="relative">
       <input type="checkbox" id="standard-toggle" name="standard_enabled" value="1" class="sr-only toggle-checkbox" {{ old('standard_enabled', $settings['standard_enabled'] ?? true) ? 'checked' : '' }}>
       <label for="standard-toggle" class="flex items-center cursor-pointer">
        <div class="toggle-label w-11 h-6 bg-gray-300 rounded-full relative">
         <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition" id="standard-dot"></div>
        </div>
       </label>
      </div>
     </div>
     <div class="flex items-center justify-between pb-4 border-b border-gray-100">
      <div class="flex items-start">
       <div class="flex-shrink-0 w-12 h-12 bg-orange-50 rounded-lg flex items-center justify-center mr-4">
        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
       </div>
       <div>
        <h4 class="text-sm font-semibold text-gray-900 leading-tight">{{ __('messages.shipping_express_title') }}</h4>
        <p class="text-sm text-gray-500 mt-0.5">{{ __('messages.shipping_express_desc_text') }}</p>
       </div>
      </div>
      <div class="relative">
       <input type="checkbox" id="express-toggle" name="express_enabled" value="1" class="sr-only toggle-checkbox" {{ old('express_enabled', $settings['express_enabled'] ?? true) ? 'checked' : '' }}>
       <label for="express-toggle" class="flex items-center cursor-pointer">
        <div class="toggle-label w-11 h-6 bg-gray-300 rounded-full relative">
         <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition" id="express-dot"></div>
        </div>
       </label>
      </div>
     </div>
     <div class="flex items-center justify-between pb-4 border-b border-gray-100">
      <div class="flex items-start">
       <div class="flex-shrink-0 w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center mr-4">
        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
       </div>
       <div>
        <h4 class="text-sm font-semibold text-gray-900 leading-tight">{{ __('messages.sameday_shipping') }}</h4>
        <p class="text-sm text-gray-500 mt-0.5">{{ __('messages.sameday_shipping_desc') }}</p>
       </div>
      </div>
      <div class="relative">
       <input type="checkbox" id="sameday-toggle" name="sameday_enabled" value="1" class="sr-only toggle-checkbox" {{ old('sameday_enabled', $settings['sameday_enabled'] ?? false) ? 'checked' : '' }}>
       <label for="sameday-toggle" class="flex items-center cursor-pointer">
        <div class="toggle-label w-11 h-6 bg-gray-300 rounded-full relative">
         <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition" id="sameday-dot"></div>
        </div>
       </label>
      </div>
     </div>
     <div class="flex items-center justify-between pt-1">
      <div class="flex items-start">
       <div class="flex-shrink-0 w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center mr-4">
        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
       </div>
       <div>
        <h4 class="text-sm font-semibold text-gray-900 leading-tight">{{ __('messages.shipping_pickup_title') }}</h4>
        <p class="text-sm text-gray-500 mt-0.5">{{ __('messages.shipping_pickup_desc_text') }}</p>
       </div>
      </div>
      <div class="relative">
       <input type="checkbox" id="pickup-toggle" name="pickup_enabled" value="1" class="sr-only toggle-checkbox" {{ old('pickup_enabled', $settings['pickup_enabled'] ?? true) ? 'checked' : '' }}>
       <label for="pickup-toggle" class="flex items-center cursor-pointer">
        <div class="toggle-label w-11 h-6 bg-gray-300 rounded-full relative">
         <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition" id="pickup-dot"></div>
        </div>
       </label>
      </div>
     </div>
    </div>
   </div>
  </div>
  <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
   <div class="px-6 py-4 border-b border-gray-200">
    <h3 id="shipping-zones-title" class="text-lg font-semibold text-gray-900 leading-tight">{{ __('messages.shipping_zones') }}</h3>
    <p class="text-sm text-gray-500 mt-1">{{ __('messages.shipping_zones_desc') }}</p>
   </div>
   <div class="p-6">
    <div class="space-y-5">
     <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
      <div class="flex items-center justify-between mb-3">
       <h4 class="text-sm font-semibold text-gray-900 leading-tight">{{ __('messages.inner_city') }}</h4>
       <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-1 rounded">{{ __('messages.active') }}</span>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
       <div>
        <label class="block text-xs font-medium text-gray-700 mb-1.5">{{ __('messages.standard_shipping') }}</label>
        <input type="number" name="inner_standard" class="input-primary" value="{{ old('inner_standard', $settings['inner_standard'] ?? 25000) }}">
       </div>
       <div>
        <label class="block text-xs font-medium text-gray-700 mb-1.5">{{ __('messages.express_shipping') }}</label>
        <input type="number" name="inner_express" class="input-primary" value="{{ old('inner_express', $settings['inner_express'] ?? 45000) }}">
       </div>
       <div>
        <label class="block text-xs font-medium text-gray-700 mb-1.5">{{ __('messages.sameday_delivery') }}</label>
        <input type="number" name="inner_sameday" class="input-primary" value="{{ old('inner_sameday', $settings['inner_sameday'] ?? 65000) }}">
       </div>
      </div>
     </div>
     <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
      <div class="flex items-center justify-between mb-3">
       <h4 class="text-sm font-semibold text-gray-900 leading-tight">{{ __('messages.suburban') }}</h4>
       <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-1 rounded">{{ __('messages.active') }}</span>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
       <div>
        <label class="block text-xs font-medium text-gray-700 mb-1.5">{{ __('messages.standard_shipping') }}</label>
        <input type="number" name="suburban_standard" class="input-primary" value="{{ old('suburban_standard', $settings['suburban_standard'] ?? 35000) }}">
       </div>
       <div>
        <label class="block text-xs font-medium text-gray-700 mb-1.5">{{ __('messages.express_shipping') }}</label>
        <input type="number" name="suburban_express" class="input-primary" value="{{ old('suburban_express', $settings['suburban_express'] ?? 60000) }}">
       </div>
       <div>
        <label class="block text-xs font-medium text-gray-700 mb-1.5">{{ __('messages.sameday_delivery') }}</label>
        <input type="number" name="suburban_sameday" class="input-primary" value="{{ old('suburban_sameday', $settings['suburban_sameday'] ?? 0) }}" placeholder="{{ __('messages.not_applicable') }}">
       </div>
      </div>
     </div>
     <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
      <div class="flex items-center justify-between mb-3">
       <h4 class="text-sm font-semibold text-gray-900 leading-tight">{{ __('messages.other_provinces') }}</h4>
       <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-1 rounded">{{ __('messages.active') }}</span>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
       <div>
        <label class="block text-xs font-medium text-gray-700 mb-1.5">{{ __('messages.standard_shipping') }}</label>
        <input type="number" name="province_standard" class="input-primary" value="{{ old('province_standard', $settings['province_standard'] ?? 45000) }}">
       </div>
       <div>
        <label class="block text-xs font-medium text-gray-700 mb-1.5">{{ __('messages.express_shipping') }}</label>
        <input type="number" name="province_express" class="input-primary" value="{{ old('province_express', $settings['province_express'] ?? 80000) }}">
       </div>
       <div>
        <label class="block text-xs font-medium text-gray-700 mb-1.5">{{ __('messages.sameday_delivery') }}</label>
        <input type="number" name="province_sameday" class="input-primary" value="{{ old('province_sameday', $settings['province_sameday'] ?? 0) }}" placeholder="{{ __('messages.not_applicable') }}">
       </div>
      </div>
     </div>
    </div>
   </div>
  </div>
  <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
   <div class="px-6 py-4 border-b border-gray-200">
    <h3 class="text-lg font-semibold text-gray-900 leading-tight">{{ __('messages.shipping_additional_settings') }}</h3>
    <p class="text-sm text-gray-500 mt-1">{{ __('messages.shipping_additional_settings_desc') }}</p>
   </div>
   <div class="p-6">
    <div class="space-y-5">
     <div>
      <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.free_shipping_threshold') }}</label>
      <input type="number" name="free_threshold" class="input-primary" placeholder="{{ __('messages.example_500000') }}" value="{{ old('free_threshold', $settings['free_threshold'] ?? '') }}">
      <p class="text-xs text-gray-500 mt-1.5">{{ __('messages.free_shipping_desc') }}</p>
     </div>
     <div>
      <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.max_weight') }}</label>
      <input type="number" name="max_weight" class="input-primary" placeholder="20" value="{{ old('max_weight', $settings['max_weight'] ?? 20) }}">
      <p class="text-xs text-gray-500 mt-1.5">{{ __('messages.max_weight_desc') }}</p>
     </div>
     <div>
      <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.processing_time') }}</label>
      @php $processingTime = old('processing_time', $settings['processing_time'] ?? 24); @endphp
      <select name="processing_time" class="input-primary">
       <option value="24" {{ $processingTime==24?'selected':'' }}>24 {{ __('messages.hours') }}</option>
       <option value="48" {{ $processingTime==48?'selected':'' }}>48 {{ __('messages.hours') }}</option>
       <option value="72" {{ $processingTime==72?'selected':'' }}>72 {{ __('messages.hours') }}</option>
      </select>
      <p class="text-xs text-gray-500 mt-1.5">{{ __('messages.processing_time_desc') }}</p>
     </div>
     <div class="flex items-start pt-1">
      <input type="checkbox" name="use_weight" value="1" id="weight-based" class="mt-1 h-4 w-4 text-blue-600 focus:ring-2 focus:ring-blue-500 border-gray-300 rounded" {{ old('use_weight', $settings['use_weight'] ?? false) ? 'checked' : '' }}>
      <label class="ml-2.5">
       <span class="text-sm font-medium text-gray-900 leading-tight block">{{ __('messages.weight_based_shipping') }}</span>
       <span class="text-sm text-gray-500 block mt-0.5">{{ __('messages.weight_based_desc') }}</span>
      </label>
     </div>
     <div class="flex items-start pt-1">
      <input type="checkbox" name="cod_shipping" value="1" id="cod-shipping" class="mt-1 h-4 w-4 text-blue-600 focus:ring-2 focus:ring-blue-500 border-gray-300 rounded" {{ old('cod_shipping', $settings['cod_shipping'] ?? true) ? 'checked' : '' }}>
      <label class="ml-2.5">
       <span class="text-sm font-medium text-gray-900 leading-tight block">{{ __('messages.allow_cod') }}</span>
       <span class="text-sm text-gray-500 block mt-0.5">{{ __('messages.allow_cod_desc') }}</span>
      </label>
     </div>
    </div>
   </div>
  </div>
  <div class="flex items-center justify-end space-x-3 pb-8">
   <a href="{{ route('admin.dashboard') }}" class="px-5 py-2 border border-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-50 transition-colors text-sm">{{ __('messages.cancel') }}</a>
   <button type="submit" class="px-5 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition-colors shadow-sm text-sm">{{ __('messages.save_changes') }}</button>
  </div>
 </form>
</div>
<script>
 (function(){
  function setupToggle(checkboxId, dotId){
   var checkbox=document.getElementById(checkboxId);
   if(!checkbox){return;}
   var dot=document.getElementById(dotId);
   var label=checkbox.nextElementSibling.querySelector('.toggle-label');
   function apply(){
    if(checkbox.checked){dot.style.transform='translateX(20px)';label.style.backgroundColor='#2563eb';}
    else{dot.style.transform='translateX(0)';label.style.backgroundColor='#d1d5db';}
   }
   checkbox.addEventListener('change',apply);
   apply();
  }
  setupToggle('standard-toggle','standard-dot');
  setupToggle('express-toggle','express-dot');
  setupToggle('sameday-toggle','sameday-dot');
  setupToggle('pickup-toggle','pickup-dot');
 })();
</script>
@endsection
