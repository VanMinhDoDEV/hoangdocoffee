@extends('layouts.admin')
@section('title', __('messages.store_settings'))
@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
 <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 {{ session('status') ? '' : 'hidden' }}">
  <div class="flex items-center">
   <div class="flex-shrink-0">
    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
   </div>
   <div class="ml-3">
    <p class="text-sm font-medium text-green-800">{{ session('status') ?? __('messages.store_update_success') }}</p>
   </div>
  </div>
 </div>
 <form method="post" action="{{ route('admin.settings.store.save') }}" id="store-form" enctype="multipart/form-data">
  @csrf
  <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
   <div class="px-6 py-4 border-b border-gray-200">
    <h3 id="settings-section-title" class="text-lg font-semibold text-gray-900 leading-tight">{{ __('messages.store_info') }}</h3>
    <p class="text-sm text-gray-500 mt-1">{{ __('messages.store_info_desc') }}</p>
   </div>
   <div class="p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
     <div>
      <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.store_name') }} *</label>
      <input type="text" name="name" class="input-primary" placeholder="{{ __('messages.store_name_placeholder') }}" value="{{ old('name', $settings['name'] ?? '') }}">
     </div>
     <div>
      <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.contact_email') }} *</label>
      <input type="email" name="email" class="input-primary" placeholder="email@example.com" value="{{ old('email', $settings['email'] ?? '') }}">
     </div>
     <div>
      <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.phone_number') }} *</label>
      <input type="tel" name="phone" class="input-primary" placeholder="0123 456 789" value="{{ old('phone', $settings['phone'] ?? '') }}">
     </div>
     <div>
      <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.website_label') }}</label>
      <input type="url" name="website" class="input-primary" placeholder="https://yourstore.com" value="{{ old('website', $settings['website'] ?? '') }}">
     </div>
    </div>
    <div class="mt-5">
     <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.address_label') }} *</label>
     <textarea name="address" rows="3" class="input-primary resize-none" placeholder="{{ __('messages.address_placeholder') }}">{{ old('address', $settings['address'] ?? '') }}</textarea>
    </div>
    <div class="mt-5">
     <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.store_description') }}</label>
     <textarea name="description" rows="4" class="input-primary resize-none" placeholder="{{ __('messages.store_description_placeholder') }}">{{ old('description', $settings['description'] ?? '') }}</textarea>
    </div>
   </div>
  </div>
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
   <div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200">
     <h3 class="text-lg font-semibold text-gray-900 leading-tight">{{ __('messages.business_hours') }}</h3>
     <p class="text-sm text-gray-500 mt-1">{{ __('messages.business_hours_desc') }}</p>
    </div>
    <div class="p-6 space-y-3">
     <div class="flex items-center justify-between pb-3 border-b border-gray-100">
      <span class="text-sm font-medium text-gray-700">{{ __('messages.mon_fri') }}</span>
      <div class="flex items-center space-x-2">
       <input type="time" name="hours_weekdays_open" class="input-primary w-auto px-2.5 py-1.5 text-xs" value="{{ old('hours_weekdays_open', $settings['hours_weekdays_open'] ?? '08:00') }}">
       <span class="text-gray-400 text-xs">-</span>
       <input type="time" name="hours_weekdays_close" class="input-primary w-auto px-2.5 py-1.5 text-xs" value="{{ old('hours_weekdays_close', $settings['hours_weekdays_close'] ?? '18:00') }}">
      </div>
     </div>
     <div class="flex items-center justify-between pb-3 border-b border-gray-100">
      <span class="text-sm font-medium text-gray-700">{{ __('messages.sat') }}</span>
      <div class="flex items-center space-x-2">
       <input type="time" name="hours_sat_open" class="input-primary w-auto px-2.5 py-1.5 text-xs" value="{{ old('hours_sat_open', $settings['hours_sat_open'] ?? '08:00') }}">
       <span class="text-gray-400 text-xs">-</span>
       <input type="time" name="hours_sat_close" class="input-primary w-auto px-2.5 py-1.5 text-xs" value="{{ old('hours_sat_close', $settings['hours_sat_close'] ?? '17:00') }}">
      </div>
     </div>
     <div class="flex items-center justify-between pt-1">
      <span class="text-sm font-medium text-gray-700">{{ __('messages.sun') }}</span>
      <div class="flex items-center space-x-2">
       <input type="time" name="hours_sun_open" class="input-primary w-auto px-2.5 py-1.5 text-xs" value="{{ old('hours_sun_open', $settings['hours_sun_open'] ?? '09:00') }}">
       <span class="text-gray-400 text-xs">-</span>
       <input type="time" name="hours_sun_close" class="input-primary w-auto px-2.5 py-1.5 text-xs" value="{{ old('hours_sun_close', $settings['hours_sun_close'] ?? '16:00') }}">
      </div>
     </div>
    </div>
   </div>
  <div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200">
     <h3 class="text-lg font-semibold text-gray-900 leading-tight">{{ __('messages.social_media') }}</h3>
     <p class="text-sm text-gray-500 mt-1">{{ __('messages.social_media_desc') }}</p>
    </div>
    <div class="p-6 space-y-4">
     <div>
      <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.facebook') }}</label>
      <input type="url" name="facebook_url" class="input-primary" placeholder="https://facebook.com/yourpage" value="{{ old('facebook_url', $settings['facebook_url'] ?? '') }}">
     </div>
     <div>
      <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.instagram') }}</label>
      <input type="url" name="instagram_url" class="input-primary" placeholder="https://instagram.com/yourprofile" value="{{ old('instagram_url', $settings['instagram_url'] ?? '') }}">
     </div>
     <div>
      <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.zalo') }}</label>
      <input type="tel" name="zalo_phone" class="input-primary" placeholder="{{ __('messages.zalo_phone_placeholder') }}" value="{{ old('zalo_phone', $settings['zalo_phone'] ?? '') }}">
     </div>
    </div>
  </div>
 </div>
 <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
  <div class="px-6 py-4 border-b border-gray-200">
   <h3 class="text-lg font-semibold text-gray-900 leading-tight">Logo Website</h3>
   <p class="text-sm text-gray-500 mt-1">Tải lên logo cho Header và Footer</p>
  </div>
  <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
   <div>
    <label class="block text-sm font-medium text-gray-700 mb-1.5">Logo Header</label>
    @if(!empty($settings['header_logo_url']))
      <div class="mb-3">
        <img src="{{ $settings['header_logo_url'] }}" alt="Header Logo" class="h-14 object-contain border rounded-md p-2 bg-white">
      </div>
    @endif
    <input type="file" name="header_logo" accept="image/*" class="block w-full text-sm text-gray-700 border border-gray-300 rounded-md file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
    <p class="text-xs text-gray-500 mt-1">PNG, JPG, SVG • tối đa 5MB</p>
   </div>
   <div>
    <label class="block text-sm font-medium text-gray-700 mb-1.5">Logo Footer</label>
    @if(!empty($settings['footer_logo_url']))
      <div class="mb-3">
        <img src="{{ $settings['footer_logo_url'] }}" alt="Footer Logo" class="h-14 object-contain border rounded-md p-2 bg-white">
      </div>
    @endif
    <input type="file" name="footer_logo" accept="image/*" class="block w-full text-sm text-gray-700 border border-gray-300 rounded-md file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
    <p class="text-xs text-gray-500 mt-1">PNG, JPG, SVG • tối đa 5MB</p>
   </div>
  </div>
 </div>
  <div class="flex items-center justify-end space-x-3 pb-8">
   <a href="{{ route('admin.dashboard') }}" class="px-5 py-2 border border-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-50 transition-colors text-sm">{{ __('messages.cancel') }}</a>
   <button type="submit" class="px-5 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition-colors shadow-sm text-sm">{{ __('messages.save_changes') }}</button>
  </div>
 </form>
</div>
@endsection
