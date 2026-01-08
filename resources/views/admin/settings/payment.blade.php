@extends('layouts.admin')
@section('title', __('messages.payment_settings'))
@section('content')
<div class="bg-white border-b border-gray-200 sticky top-0 z-10">
</div>
<div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
 <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 {{ session('status') ? '' : 'hidden' }}">
  <div class="flex items-center">
   <div class="flex-shrink-0">
    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
   </div>
   <div class="ml-3">
    <p class="text-sm font-medium text-green-800">{{ session('status') ?? __('messages.payment_update_success') }}</p>
   </div>
  </div>
 </div>
 <form method="post" action="{{ route('admin.settings.payment.save') }}" id="payment-form">
  @csrf
  <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
   <div class="px-6 py-4 border-b border-gray-200">
    <h3 id="payment-methods-title" class="text-lg font-semibold text-gray-900 leading-tight">{{ __('messages.payment_methods') }}</h3>
    <p class="text-sm text-gray-500 mt-1">{{ __('messages.payment_methods_desc') }}</p>
   </div>
   <div class="p-6">
    <div class="space-y-4">
     <div class="flex items-center justify-between pb-4 border-b border-gray-100">
      <div class="flex items-start">
       <div class="flex-shrink-0 w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mr-4">
        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
       </div>
       <div>
        <h4 class="text-sm font-semibold text-gray-900 leading-tight">{{ __('messages.cod') }}</h4>
        <p class="text-sm text-gray-500 mt-0.5">{{ __('messages.cod_desc_customer') }}</p>
       </div>
      </div>
      <div class="relative">
       <input type="checkbox" id="cod-toggle" name="cod_enabled" value="1" class="sr-only toggle-checkbox" {{ old('cod_enabled', $settings['cod_enabled'] ?? false) ? 'checked' : '' }}>
       <label for="cod-toggle" class="flex items-center cursor-pointer">
        <div class="toggle-label w-11 h-6 bg-gray-300 rounded-full relative">
         <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition" id="cod-dot"></div>
        </div>
       </label>
      </div>
     </div>
     <div class="flex items-center justify-between pb-4 border-b border-gray-100">
      <div class="flex items-start">
       <div class="flex-shrink-0 w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center mr-4">
        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
       </div>
       <div>
        <h4 class="text-sm font-semibold text-gray-900 leading-tight">{{ __('messages.bank_transfer') }}</h4>
        <p class="text-sm text-gray-500 mt-0.5">{{ __('messages.bank_transfer_desc') }}</p>
       </div>
      </div>
      <div class="relative">
       <input type="checkbox" id="bank-toggle" name="bank_transfer_enabled" value="1" class="sr-only toggle-checkbox" {{ old('bank_transfer_enabled', $settings['bank_transfer_enabled'] ?? false) ? 'checked' : '' }}>
       <label for="bank-toggle" class="flex items-center cursor-pointer">
        <div class="toggle-label w-11 h-6 bg-gray-300 rounded-full relative">
         <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition" id="bank-dot"></div>
        </div>
       </label>
      </div>
     </div>
     <div class="flex items-center justify-between pb-4 border-b border-gray-100">
      <div class="flex items-start">
       <div class="flex-shrink-0 w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center mr-4">
        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
       </div>
       <div>
        <h4 class="text-sm font-semibold text-gray-900 leading-tight">{{ __('messages.e_wallet') }}</h4>
        <p class="text-sm text-gray-500 mt-0.5">{{ __('messages.ewallet_desc') }}</p>
       </div>
      </div>
      <div class="relative">
       <input type="checkbox" id="wallet-toggle" name="wallet_enabled" value="1" class="sr-only toggle-checkbox" {{ old('wallet_enabled', $settings['wallet_enabled'] ?? false) ? 'checked' : '' }}>
       <label for="wallet-toggle" class="flex items-center cursor-pointer">
        <div class="toggle-label w-11 h-6 bg-gray-300 rounded-full relative">
         <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition" id="wallet-dot"></div>
        </div>
       </label>
      </div>
     </div>
     <div class="flex items-center justify-between pt-1">
      <div class="flex items-start">
       <div class="flex-shrink-0 w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center mr-4">
        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
       </div>
       <div>
        <h4 class="text-sm font-semibold text-gray-900 leading-tight">{{ __('messages.credit_card') }}</h4>
        <p class="text-sm text-gray-500 mt-0.5">{{ __('messages.credit_card_desc') }}</p>
       </div>
      </div>
      <div class="relative">
       <input type="checkbox" id="card-toggle" name="credit_card_enabled" value="1" class="sr-only toggle-checkbox" {{ old('credit_card_enabled', $settings['credit_card_enabled'] ?? false) ? 'checked' : '' }}>
       <label for="card-toggle" class="flex items-center cursor-pointer">
        <div class="toggle-label w-11 h-6 bg-gray-300 rounded-full relative">
         <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition" id="card-dot"></div>
        </div>
       </label>
      </div>
     </div>
    </div>
   </div>
  </div>
  <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
   <div class="px-6 py-4 border-b border-gray-200">
    <h3 id="bank-info-title" class="text-lg font-semibold text-gray-900 leading-tight">{{ __('messages.bank_info') }}</h3>
    <p class="text-sm text-gray-500 mt-1">{{ __('messages.bank_info_desc') }}</p>
   </div>
   <div class="p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
     <div>
      <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.bank_name_label') }}</label>
      @php $bank = old('bank_name', $settings['bank_name'] ?? ''); @endphp
      <select name="bank_name" class="input-primary">
       <option value="">{{ __('messages.select_bank') }}</option>
       <option value="vietcombank" {{ $bank==='vietcombank'?'selected':'' }}>Vietcombank</option>
       <option value="techcombank" {{ $bank==='techcombank'?'selected':'' }}>Techcombank</option>
       <option value="bidv" {{ $bank==='bidv'?'selected':'' }}>BIDV</option>
       <option value="vietinbank" {{ $bank==='vietinbank'?'selected':'' }}>VietinBank</option>
       <option value="acb" {{ $bank==='acb'?'selected':'' }}>ACB</option>
       <option value="mbbank" {{ $bank==='mbbank'?'selected':'' }}>MB Bank</option>
       <option value="vpbank" {{ $bank==='vpbank'?'selected':'' }}>VPBank</option>
       <option value="tpbank" {{ $bank==='tpbank'?'selected':'' }}>TPBank</option>
      </select>
     </div>
     <div>
      <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.bank_account_number_label') }}</label>
      <input type="text" name="bank_account_number" class="input-primary" placeholder="{{ __('messages.enter_bank_account_number') }}" value="{{ old('bank_account_number', $settings['bank_account_number'] ?? '') }}">
     </div>
     <div>
      <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.bank_account_name_label') }}</label>
      <input type="text" name="bank_account_name" class="input-primary" placeholder="{{ __('messages.bank_account_name_placeholder') }}" value="{{ old('bank_account_name', $settings['bank_account_name'] ?? '') }}">
     </div>
     <div>
      <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.bank_branch_label') }}</label>
      <input type="text" name="bank_branch" class="input-primary" placeholder="{{ __('messages.bank_branch_placeholder') }}" value="{{ old('bank_branch', $settings['bank_branch'] ?? '') }}">
     </div>
    </div>
    <div class="mt-5">
     <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.transfer_note_label') }}</label>
     <input type="text" name="transfer_note" class="input-primary" placeholder="{{ __('messages.transfer_note_placeholder') }}" value="{{ old('transfer_note', $settings['transfer_note'] ?? '') }}">
     <p class="text-xs text-gray-500 mt-1.5">{{ __('messages.transfer_note_desc') }}</p>
    </div>
   </div>
  </div>
  <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
   <div class="px-6 py-4 border-b border-gray-200">
    <h3 class="text-lg font-semibold text-gray-900 leading-tight">{{ __('messages.additional_settings') }}</h3>
    <p class="text-sm text-gray-500 mt-1">{{ __('messages.additional_settings_desc') }}</p>
   </div>
   <div class="p-6">
    <div class="space-y-5">
     <div>
      <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.min_order_value') }}</label>
      <input type="number" name="min_order" class="input-primary" placeholder="0" value="{{ old('min_order', $settings['min_order'] ?? 0) }}">
      <p class="text-xs text-gray-500 mt-1.5">{{ __('messages.min_order_desc') }}</p>
     </div>
     <div>
      <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.cod_fee_label') }}</label>
      <div class="grid grid-cols-2 gap-3">
       <input type="number" name="cod_fee" class="input-primary" placeholder="20000" value="{{ old('cod_fee', $settings['cod_fee'] ?? 0) }}">
       @php $codFeeType = old('cod_fee_type', $settings['cod_fee_type'] ?? 'fixed'); @endphp
       <select name="cod_fee_type" class="input-primary">
        <option value="fixed" {{ $codFeeType==='fixed'?'selected':'' }}>{{ __('messages.fixed_amount') }}</option>
        <option value="percent" {{ $codFeeType==='percent'?'selected':'' }}>{{ __('messages.percent') }}</option>
       </select>
      </div>
      <p class="text-xs text-gray-500 mt-1.5">{{ __('messages.cod_fee_desc') }}</p>
     </div>
     <div class="flex items-start pt-1">
      <input type="checkbox" name="auto_confirm" value="1" class="mt-1 h-4 w-4 text-blue-600 focus:ring-2 focus:ring-blue-500 border-gray-300 rounded" {{ old('auto_confirm', $settings['auto_confirm'] ?? false) ? 'checked' : '' }}>
      <label class="ml-2.5">
       <span class="text-sm font-medium text-gray-900 leading-tight block">{{ __('messages.auto_confirm_order') }}</span>
       <span class="text-sm text-gray-500 block mt-0.5">{{ __('messages.auto_confirm_order_desc') }}</span>
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
  setupToggle('cod-toggle','cod-dot');
  setupToggle('bank-toggle','bank-dot');
  setupToggle('wallet-toggle','wallet-dot');
  setupToggle('card-toggle','card-dot');
 })();
</script>
@endsection
