@extends('layouts.admin')

@section('title', __('messages.user_settings'))

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <header class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <p class="mt-1 text-sm text-gray-500">{{ __('messages.manage_profile_desc') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" onclick="window.history.back()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    {{ __('messages.cancel') }}
                </button>
                <button type="submit" form="profile-form" class="px-6 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-all shadow-sm hover:shadow">
                    {{ __('messages.save_changes') }}
                </button>
            </div>
        </div>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Sidebar Navigation -->
        <aside class="lg:col-span-3">
            <nav class="sticky top-8 space-y-1">
                <a href="#profile" class="flex items-center gap-3 px-4 py-2 text-sm font-medium text-indigo-700 bg-indigo-50 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span>{{ __('messages.profile') }}</span>
                </a>
                <a href="#security" class="flex items-center gap-3 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    <span>{{ __('messages.security') }}</span>
                </a>
                <a href="#preferences" class="flex items-center gap-3 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span>{{ __('messages.preferences') }}</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="lg:col-span-9 space-y-8">
            <form id="profile-form" method="POST" action="{{ route('admin.settings.profile.save') }}" class="space-y-8" enctype="multipart/form-data">
                @csrf
                <!-- Profile Section -->
                <section id="profile" class="bg-white rounded-xl shadow-sm border border-gray-200 transition-all duration-200 hover:translate-y-[-2px] hover:shadow-lg">
                    <div class="p-8">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">{{ __('messages.profile') }}</h2>
                                <p class="mt-1 text-sm text-gray-600">{{ __('messages.update_profile_info') }}</p>
                            </div>
                        </div>

                        <!-- Avatar Upload -->
                        <div class="mb-8 pb-8 border-b border-gray-200">
                            <label class="block text-sm font-medium text-gray-700 mb-4" style="color: rgb(17, 24, 39); font-size: 14px;">{{ __('messages.avatar') }}</label>
                            <div class="flex items-start gap-6">
                                <div class="flex-shrink-0">
                                    <div class="relative">
                                        <img id="profile-avatar" src="{{ auth()->user()->avatar ? Storage::url(auth()->user()->avatar) : '' }}" alt="Avatar" class="w-20 h-20 rounded-full object-cover {{ auth()->user()->avatar ? '' : 'hidden' }} shadow-md border border-gray-100">
                                        <div id="profile-avatar-placeholder" class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white text-2xl font-semibold shadow-md {{ auth()->user()->avatar ? 'hidden' : '' }}">
                                            {{ substr(auth()->user()->name, 0, 2) }}
                                        </div>
                                    </div>
                                    <p class="mt-2 text-xs text-gray-500 text-center" style="color: rgb(17, 24, 39); font-size: 14px;">{{ __('messages.recommend_size') }}</p>
                                </div>
                                <div class="flex-1">
                                    <input type="file" id="avatar-upload" class="hidden" accept="image/*">
                                    <div onclick="document.getElementById('avatar-upload').click()" class="upload-area rounded-lg p-6 text-center cursor-pointer border-2 border-dashed border-gray-300 hover:border-indigo-500 transition-colors">
                                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                        <p class="text-sm font-medium text-gray-900" style="color: rgb(17, 24, 39); font-size: 14px;">{{ __('messages.click_to_upload') }}</p>
                                        <p class="text-xs text-gray-500 mt-1" style="color: rgb(17, 24, 39); font-size: 14px;">{{ __('messages.image_requirements') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Fields -->
                        <div class="space-y-6">
                            <div class="col-span-2">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.full_name') }}</label>
                                <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" class="input-primary">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.email') }}</label>
                                <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="input-primary">
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.phone_number') }}</label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" class="input-primary">
                            </div>

                            <div>
                                <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.bio') }}</label>
                                <textarea id="bio" name="bio" rows="4" class="input-primary resize-none" placeholder="{{ __('messages.bio_placeholder') }}">{{ old('bio', auth()->user()->bio) }}</textarea>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Security Section -->
                <section id="security" class="bg-white rounded-xl shadow-sm border border-gray-200 transition-all duration-200 hover:translate-y-[-2px] hover:shadow-lg">
                    <div class="p-8">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">{{ __('messages.security_privacy_title') }}</h2>
                                <p class="mt-1 text-sm text-gray-600">{{ __('messages.security_privacy_desc') }}</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <!-- Password Change -->
                            <div class="pb-6 border-b border-gray-200">
                                <h3 class="text-sm font-medium text-gray-900 mb-4">{{ __('messages.change_password') }}</h3>
                                <div class="space-y-4">
                                    <input type="password" name="current_password" placeholder="{{ __('messages.current_password_placeholder') }}" class="input-primary">
                                    <input type="password" name="new_password" placeholder="{{ __('messages.new_password_placeholder') }}" class="input-primary">
                                    <input type="password" name="new_password_confirmation" placeholder="{{ __('messages.confirm_new_password_placeholder') }}" class="input-primary">
                                </div>
                            </div>

                            <!-- Two-Factor Authentication -->
                            <div class="pb-6 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900">{{ __('messages.two_factor_auth') }}</h3>
                                        <p class="mt-1 text-sm text-gray-600">{{ __('messages.two_factor_auth_desc') }}</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="two_factor_enabled" value="1" class="sr-only peer" {{ auth()->user()->two_factor_enabled ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Preferences Section -->
                <section id="preferences" class="bg-white rounded-xl shadow-sm border border-gray-200 transition-all duration-200 hover:translate-y-[-2px] hover:shadow-lg">
                    <div class="p-8">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">{{ __('messages.display_options') }}</h2>
                                <p class="mt-1 text-sm text-gray-600">{{ __('messages.display_options_desc') }}</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <!-- Language -->
                            <div class="pb-6 border-b border-gray-200">
                                <label for="language" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.language_label') }}</label>
                                <select id="language" name="language" class="input-primary">
                                    <option value="vi" {{ (old('language', $adminLanguage ?? 'vi')==='vi')?'selected':'' }}>{{ __('messages.lang_vi') }}</option>
                                    <option value="en" {{ (old('language', $adminLanguage ?? 'vi')==='en')?'selected':'' }}>{{ __('messages.lang_en') }}</option>
                                    <option value="ja" {{ (old('language', $adminLanguage ?? 'vi')==='ja')?'selected':'' }}>{{ __('messages.lang_ja') }}</option>
                                </select>
                            </div>

                            <!-- Notifications -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-4">{{ __('messages.notifications_label') }}</label>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ __('messages.email_notifications') }}</p>
                                            <p class="text-xs text-gray-600 mt-0.5">{{ __('messages.email_notifications_desc') }}</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" checked class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </form>
        </main>
    </div>
</div>

<script>
    document.getElementById('avatar-upload').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('avatar', file);
        formData.append('_token', '{{ csrf_token() }}');

        // UI updates for loading state
        const uploadArea = e.target.nextElementSibling;
        const pText = uploadArea ? uploadArea.querySelector('p.text-sm') : null;
        const originalText = pText ? pText.innerText : '';
        
        if (pText) {
            pText.innerText = '{{ __('messages.uploading') }}';
        }
        if (uploadArea) {
            uploadArea.style.pointerEvents = 'none';
            uploadArea.classList.add('opacity-75');
        }

        fetch('{{ route("admin.settings.profile.avatar") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Update profile avatar
                const profileImg = document.getElementById('profile-avatar');
                const profilePlaceholder = document.getElementById('profile-avatar-placeholder');
                
                if (profileImg) {
                    profileImg.src = data.url;
                    profileImg.classList.remove('hidden');
                }
                if (profilePlaceholder) profilePlaceholder.classList.add('hidden');

                // Update topbar avatar
                const topbarImg = document.getElementById('topbar-avatar');
                const topbarPlaceholder = document.getElementById('topbar-avatar-placeholder');
                
                if (topbarImg) {
                    topbarImg.src = data.url;
                    topbarImg.classList.remove('hidden');
                }
                if (topbarPlaceholder) topbarPlaceholder.classList.add('hidden');
                
                // Show success toast/alert if needed
                showToast('success', 'Success', data.message || '{{ __('messages.update_avatar_success') }}');
            } else {
                showToast('error', 'Error', data.message || '{{ __('messages.upload_error') }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('error', 'Error', '{{ __('messages.upload_error') }}');
        })
        .finally(() => {
            if (pText) pText.innerText = originalText;
            if (uploadArea) {
                uploadArea.style.pointerEvents = 'auto';
                uploadArea.classList.remove('opacity-75');
            }
            e.target.value = ''; // Reset input
        });
    });
</script>
@endsection

@push('styles')
<style>
    .input-primary {
        @apply w-full px-4 py-2.5 text-sm text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white outline-none transition-all duration-200;
    }
</style>
@endpush
