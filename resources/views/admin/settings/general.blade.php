@extends('layouts.admin')
@section('title', __('messages.general_settings'))
@section('content')
<div class="h-full bg-gray-50">
    <div class="w-full max-xxl mx-auto ">
        <!-- Header Section -->
        <header class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <!-- <h1 id="page-title" class="text-3xl font-bold text-gray-900 leading-tight">{{ __('messages.general_settings') }}</h1> -->
                    <p class="mt-3 text-base text-gray-600 leading-relaxed">{{ __('messages.general_settings_desc') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        {{ __('messages.cancel') }}
                    </a>
                    <button type="button" onclick="document.getElementById('general-settings-form').submit()" class="px-6 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-all hover:shadow-lg">
                        {{ __('messages.save_changes') }}
                    </button>
                </div>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Sidebar Navigation -->
            <aside class="lg:col-span-1">
                <nav class="bg-white rounded-xl shadow-sm border border-gray-200 p-2 sticky top-8">
                    <a href="#" data-target="site-info" class="tab-link flex items-center gap-3 px-4 py-3 mt-1 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                        </svg>
                        <span>{{ __('messages.website_info') }}</span>
                    </a>
                    <a href="#" data-target="features" class="tab-link flex items-center gap-3 px-4 py-3 mt-1 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                        <span>{{ __('messages.features_settings') }}</span>
                    </a>
                    <a href="#" data-target="menus" class="tab-link flex items-center gap-3 px-4 py-3 mt-1 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <span>Menu</span>
                    </a>
                </nav>
            </aside>

            <!-- Main Content Area -->
            <main class="lg:col-span-2 space-y-8">
                

                <form id="general-settings-form" action="{{ route('admin.settings.store.save') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Hidden inputs for preserving data -->
                    <input type="hidden" name="redirect_to" value="general">
                    <input type="hidden" name="address" value="{{ $settings['address'] ?? '' }}">
                    <input type="hidden" name="hours_weekdays_open" value="{{ $settings['hours_weekdays_open'] ?? '' }}">
                    <input type="hidden" name="hours_weekdays_close" value="{{ $settings['hours_weekdays_close'] ?? '' }}">
                    <input type="hidden" name="hours_sat_open" value="{{ $settings['hours_sat_open'] ?? '' }}">
                    <input type="hidden" name="hours_sat_close" value="{{ $settings['hours_sat_close'] ?? '' }}">
                    <input type="hidden" name="hours_sun_open" value="{{ $settings['hours_sun_open'] ?? '' }}">
                    <input type="hidden" name="hours_sun_close" value="{{ $settings['hours_sun_close'] ?? '' }}">
                    <input type="hidden" name="facebook_url" value="{{ $settings['facebook_url'] ?? '' }}">
                    <input type="hidden" name="instagram_url" value="{{ $settings['instagram_url'] ?? '' }}">
                    <input type="hidden" name="zalo_phone" value="{{ $settings['zalo_phone'] ?? '' }}">

                    <!-- Website Information Section -->
                    <section id="site-info" class="bg-white rounded-xl shadow-sm border border-gray-200 transition-all duration-200 hover:translate-y-[-2px] hover:shadow-lg mb-8">
                        <div class="p-8">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h2 id="site-info-title" class="text-xl font-semibold text-gray-900 leading-tight">{{ __('messages.website_info') }}</h2>
                                    <p class="mt-1 text-sm text-gray-600 leading-normal">{{ __('messages.website_info_desc') }}</p>
                                </div>
                            </div>
                            
                            <div class="space-y-6">
                                <!-- Website Favicon -->
                                <div class="pb-6 border-b border-gray-200">
                                    <label class="block text-sm font-medium text-gray-700 mb-4" style="color: rgb(17, 24, 39); font-size: 14px;">{{ __('messages.favicon') }}</label>
                                    <div class="flex items-start gap-6">
                                        <div class="flex-shrink-0">
                                            @if(!empty($settings['favicon']))
                                                <img id="site-favicon-preview" src="{{ $settings['favicon'] }}" alt="Favicon" class="w-16 h-16 rounded-lg object-contain bg-white shadow-md border border-gray-100">
                                                <div id="site-favicon-placeholder" class="hidden w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center text-white text-xl font-bold shadow-md">A</div>
                                            @else
                                                <div id="site-favicon-placeholder" class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center text-white text-xl font-bold shadow-md">
                                                    A
                                                </div>
                                                <img id="site-favicon-preview" src="" alt="Favicon" class="hidden w-16 h-16 rounded-lg object-contain bg-white shadow-md border border-gray-100">
                                            @endif
                                            <p class="mt-2 text-xs text-gray-500 text-center" style="color: rgb(17, 24, 39); font-size: 14px;">{{ __('messages.favicon_desc') }}</p>
                                        </div>
                                        <div class="flex-1">
                                            <input type="file" id="favicon-upload" class="hidden" accept=".png, .ico, .svg, .jpg, .jpeg">
                                            <div onclick="document.getElementById('favicon-upload').click()" class="upload-area rounded-lg p-6 text-center cursor-pointer border-2 border-dashed border-gray-300 hover:border-indigo-500 transition-colors">
                                                <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                                <p class="text-sm font-medium text-gray-900" style="color: rgb(17, 24, 39); font-size: 14px;">{{ __('messages.upload_favicon') }}</p>
                                                <p class="text-xs text-gray-500 mt-1" style="color: rgb(17, 24, 39); font-size: 14px;">{{ __('messages.favicon_upload_help') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Website Title -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.site_title') }}</label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $settings['name'] ?? 'Admin Dashboard - Hệ thống quản lý') }}" class="w-full px-4 py-2.5 text-sm text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white outline-none transition-all duration-200 focus:shadow-md">
                                    <p class="mt-2 text-xs text-gray-500 leading-normal">{{ __('messages.site_title_desc') }}</p>
                                </div>
                                
                                <!-- Website Tagline -->
                                <div>
                                    <label for="tagline" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.tagline') }}</label>
                                    <input type="text" id="tagline" name="tagline" value="{{ old('tagline', $settings['tagline'] ?? '') }}" placeholder="{{ __('messages.tagline_placeholder') }}" class="w-full px-4 py-2.5 text-sm text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white outline-none transition-all duration-200 focus:shadow-md">
                                    <p class="mt-2 text-xs text-gray-500 leading-normal">{{ __('messages.tagline_desc') }}</p>
                                </div>

                                <!-- SEO Description -->
                                <div>
                                    <label for="seo_description" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.seo_description') }} <span id="seo-counter" class="text-xs font-normal text-gray-500 ml-1">({{ mb_strlen($settings['seo_description'] ?? '') }}/160)</span></label>
                                    <textarea id="seo_description" name="seo_description" rows="3" maxlength="160" oninput="document.getElementById('seo-counter').innerText = `(${this.value.length}/160)`" class="w-full px-4 py-2.5 text-sm text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white outline-none resize-none transition-all duration-200 focus:shadow-md">{{ old('seo_description', $settings['seo_description'] ?? '') }}</textarea>
                                </div>

                                <!-- Website Social Image (for sharing) -->
                                <div class="pb-6 border-b border-gray-200">
                                    <label class="block text-sm font-medium text-gray-700 mb-4" style="color: rgb(17, 24, 39); font-size: 14px;">{{ __('messages.social_image') }}</label>
                                    <div class="flex items-start gap-6">
                                        <div class="flex-shrink-0">
                                            @if(!empty($settings['social_image']))
                                                <img id="site-social-image" src="{{ $settings['social_image'] }}" alt="Website Image" class="w-32 h-20 rounded-lg object-cover bg-white shadow-sm border border-gray-100">
                                                <div id="site-social-image-placeholder" class="hidden w-32 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center text-white text-sm font-bold shadow-md">OG</div>
                                            @else
                                                <div id="site-social-image-placeholder" class="w-32 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center text-white text-sm font-bold shadow-md">
                                                    OG
                                                </div>
                                                <img id="site-social-image" src="" alt="Website Image" class="hidden w-32 h-20 rounded-lg object-cover bg-white shadow-sm border border-gray-100">
                                            @endif
                                            <p class="mt-2 text-xs text-gray-500 text-center" style="color: rgb(17, 24, 39); font-size: 14px;">{{ __('messages.social_image_desc') }}</p>
                                        </div>
                                        <div class="flex-1">
                                            <input type="file" id="social-image-upload" class="hidden" accept="image/*">
                                            <div onclick="document.getElementById('social-image-upload').click()" class="upload-area rounded-lg p-6 text-center cursor-pointer border-2 border-dashed border-gray-300 hover:border-indigo-500 transition-colors">
                                                <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                                <p class="text-sm font-medium text-gray-900" style="color: rgb(17, 24, 39); font-size: 14px;">{{ __('messages.upload_social_image') }}</p>
                                                <p class="text-xs text-gray-500 mt-1" style="color: rgb(17, 24, 39); font-size: 14px;">{{ __('messages.social_image_upload_help') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Website URL -->
                                <div>
                                    <label for="website" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.website_url') }}</label>

                                    <input type="url" id="website" name="website" value="{{ old('website', $settings['website'] ?? 'https://example.com') }}" class="w-full px-4 py-2.5 text-sm text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white outline-none transition-all duration-200 focus:shadow-md">
                                </div>
                                <!-- Contact Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.contact_email_label') }}</label>
                                    <input type="email" id="email" name="email" value="{{ old('email', $settings['email'] ?? 'contact@example.com') }}" class="w-full px-4 py-2.5 text-sm text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white outline-none transition-all duration-200 focus:shadow-md">
                                </div>
                                <!-- Contact Phone -->
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.contact_phone') }}</label>
                                    <input type="text" id="phone" name="phone" value="{{ old('phone', $settings['phone'] ?? '') }}" class="w-full px-4 py-2.5 text-sm text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white outline-none transition-all duration-200 focus:shadow-md">
                                </div>
                                <!-- Time Zone -->
                                <div>
                                    <label for="timezone" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.timezone_label') }}</label>
                                    <select id="timezone" class="w-full px-4 py-2.5 text-sm text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white outline-none transition-all duration-200 focus:shadow-md">
                                        <option selected>GMT+7 (Hà Nội, Bangkok, Jakarta)</option>
                                        <option>GMT+8 (Singapore, Kuala Lumpur)</option>
                                        <option>GMT+9 (Tokyo, Seoul)</option>
                                        <option>UTC (London)</option>
                                    </select>
                                </div>
                                <!-- Language -->
                                <div>
                                    <label for="default-language" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.default_language_label') }}</label>
                                    <select id="default-language" class="w-full px-4 py-2.5 text-sm text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white outline-none transition-all duration-200 focus:shadow-md">
                                        <option selected>Tiếng Việt</option>
                                        <option>English</option>
                                        <option>日本語</option>
                                        <option>中文</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Features & Settings Section -->
                    <section id="features" class="bg-white rounded-xl shadow-sm border border-gray-200 transition-all duration-200 hover:translate-y-[-2px] hover:shadow-lg">
                        <div class="p-8">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h2 id="features-title" class="text-xl font-semibold text-gray-900 leading-tight">{{ __('messages.features_title') }}</h2>
                                    <p class="mt-1 text-sm text-gray-600 leading-normal">{{ __('messages.features_desc') }}</p>
                                </div>
                            </div>
                            
                            <div class="space-y-6">
                                <!-- Membership Status -->
                                <div class="pb-6 border-b border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-900">{{ __('messages.membership_title') }}</h3>
                                            <p class="mt-1 text-sm text-gray-600 leading-normal">{{ __('messages.membership_desc') }}</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" checked class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                        </label>
                                    </div>
                                </div>
                                <!-- Maintenance Mode -->
                                <div class="pb-6 border-b border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-900">{{ __('messages.maintenance_mode_title') }}</h3>
                                            <p class="mt-1 text-sm text-gray-600 leading-normal">{{ __('messages.maintenance_mode_desc') }}</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                                        </label>
                                    </div>
                                </div>
                                <!-- Email Notifications -->
                                <div class="pb-6 border-b border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-900">{{ __('messages.email_notifications_title') }}</h3>
                                            <p class="mt-1 text-sm text-gray-600 leading-normal">{{ __('messages.email_notifications_desc') }}</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" checked class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                        </label>
                                    </div>
                                </div>
                                <!-- API Access -->
                                <div class="pb-6 border-b border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-900">{{ __('messages.api_access_title') }}</h3>
                                            <p class="mt-1 text-sm text-gray-600 leading-normal">{{ __('messages.api_access_desc') }}</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" checked class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                        </label>
                                    </div>
                                </div>
                                <!-- Auto Backup -->
                                <div class="pb-6 border-b border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-900">{{ __('messages.auto_backup_title') }}</h3>
                                            <p class="mt-1 text-sm text-gray-600 leading-normal">{{ __('messages.auto_backup_desc') }}</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" checked class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                        </label>
                                    </div>
                                </div>
                                <!-- Analytics Tracking -->
                                <div class="pb-6 border-b border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-900">{{ __('messages.analytics_tracking_title') }}</h3>
                                            <p class="mt-1 text-sm text-gray-600 leading-normal">{{ __('messages.analytics_tracking_desc') }}</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" checked class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                        </label>
                                    </div>
                                </div>
                                <!-- Dark Mode -->
                                <div>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-900">{{ __('messages.dark_mode_title') }}</h3>
                                            <p class="mt-1 text-sm text-gray-600 leading-normal">{{ __('messages.dark_mode_desc') }}</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </form>
                <!-- Menus Management -->
                <section id="menus" class="bg-white rounded-xl shadow-sm border border-gray-200 transition-all duration-200 hover:translate-y-[-2px] hover:shadow-lg hidden">
                    <div class="border-b border-gray-200 bg-gray-50/50 px-6 py-4 flex items-center justify-between">
                        <div class="flex items-center gap-2 overflow-x-auto pr-2" id="created-menus-list"></div>
                        <button onclick="openCreateMenuModal()" class="px-3 py-2 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-blue-500 hover:text-blue-600 transition font-medium flex items-center gap-2">
                            <i class="fas fa-plus-circle"></i> Tạo Menu Mới
                        </button>
                    </div>
                    <div class="p-6 lg:p-8">
                            <div id="editor-container" class="hidden">
                                <div class="flex justify-between items-end mb-8">
                                    <div>
                                        <h1 id="editing-title" class="text-3xl font-black text-gray-800 tracking-tight leading-none mb-2">Tên Menu</h1>
                                        <p class="text-gray-500 flex items-center gap-2">
                                            Loại hiển thị: <span id="editing-type" class="px-2 py-0.5 bg-gray-200 text-gray-700 rounded text-[10px] font-bold uppercase italic tracking-wider">Mega Menu</span>
                                            <button onclick="openEditMenuModal()" class="text-blue-600 hover:text-blue-800 ml-4 text-xs font-bold uppercase"><i class="fas fa-pen"></i> Sửa Menu</button>
                                            <button onclick="deleteCurrentMenu()" class="text-red-500 hover:text-red-700 ml-4 text-xs font-bold uppercase"><i class="fas fa-trash-alt"></i> Xóa Menu</button>
                                        </p>
                                    </div>
                                    <button onclick="saveMenus()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-lg font-bold shadow-lg transition active:scale-95">
                                        Lưu Cấu Trúc
                                    </button>
                                </div>

                                <div class="grid grid-cols-12 gap-8">
                                    <!-- Sources Panel -->
                                    <div class="col-span-12 lg:col-span-4 space-y-4">
                                        <div class="bg-white p-5 rounded-xl border shadow-sm">
                                            <h3 class="font-bold text-gray-700 mb-4 border-b pb-2 uppercase text-[11px] tracking-widest text-slate-400">Nguồn phần tử</h3>
                                            
                                            <!-- Categories Source -->
                                            <div class="mb-4">
                                                <button onclick="toggleAccordion('source-categories')" class="flex items-center justify-between w-full font-bold text-sm text-gray-700 mb-2">
                                                    <span>Danh mục sản phẩm</span>
                                                    <i class="fas fa-chevron-down text-xs"></i>
                                                </button>
                                                <div id="source-categories" class="hidden space-y-2 max-h-40 overflow-y-auto custom-scrollbar border p-2 rounded">
                                                    <input type="text" id="search-cat" onkeyup="filterSource('search-cat', 'source-categories')" placeholder="Tìm..." class="w-full text-xs p-1 border rounded mb-2">
                                                    @foreach($categories as $cat)
                                                    <div class="flex justify-between items-center hover:bg-gray-50 p-1 rounded">
                                                        <label class="flex items-center gap-2 cursor-pointer">
                                                            <span class="text-sm font-medium text-slate-600">{{ $cat->name }}</span>
                                                        </label>
                                                        <button onclick="addToTree('{{ $cat->name }}', '{{ app()->getLocale() === 'vi' ? '/danh-muc/'.$cat->slug : '/categories/'.$cat->slug }}', 'category', '{{ $cat->id }}')" class="text-blue-600 hover:text-blue-800 font-bold text-xs uppercase tracking-tighter">+ Thêm</button>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <!-- Collections Source -->
                                            <div class="mb-4">
                                                <button onclick="toggleAccordion('source-collections')" class="flex items-center justify-between w-full font-bold text-sm text-gray-700 mb-2">
                                                    <span>Bộ sưu tập</span>
                                                    <i class="fas fa-chevron-down text-xs"></i>
                                                </button>
                                                <div id="source-collections" class="hidden space-y-2 max-h-40 overflow-y-auto custom-scrollbar border p-2 rounded">
                                                    <input type="text" id="search-col" onkeyup="filterSource('search-col', 'source-collections')" placeholder="Tìm..." class="w-full text-xs p-1 border rounded mb-2">
                                                    @foreach($collections as $col)
                                                    <div class="flex justify-between items-center hover:bg-gray-50 p-1 rounded">
                                                        <label class="flex items-center gap-2 cursor-pointer">
                                                            <span class="text-sm font-medium text-slate-600">{{ $col->name }}</span>
                                                        </label>
                                                        <button onclick="addToTree('{{ $col->name }}', '{{ app()->getLocale() === 'vi' ? '/bo-suu-tap/'.$col->slug : '/collections/'.$col->slug }}', 'collection', '{{ $col->id }}')" class="text-blue-600 hover:text-blue-800 font-bold text-xs uppercase tracking-tighter">+ Thêm</button>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <!-- Blog Categories Source -->
                                            <div class="mb-4">
                                                <button onclick="toggleAccordion('source-post-categories')" class="flex items-center justify-between w-full font-bold text-sm text-gray-700 mb-2">
                                                    <span>Chuyên mục bài viết</span>
                                                    <i class="fas fa-chevron-down text-xs"></i>
                                                </button>
                                                <div id="source-post-categories" class="hidden space-y-2 max-h-40 overflow-y-auto custom-scrollbar border p-2 rounded">
                                                    <input type="text" id="search-post-cat" onkeyup="filterSource('search-post-cat', 'source-post-categories')" placeholder="Tìm..." class="w-full text-xs p-1 border rounded mb-2">
                                                    @foreach($postCategories as $pCat)
                                                    <div class="flex justify-between items-center hover:bg-gray-50 p-1 rounded">
                                                        <label class="flex items-center gap-2 cursor-pointer">
                                                            <span class="text-sm font-medium text-slate-600">{{ $pCat->name }}</span>
                                                        </label>
                                                        <button onclick="addToTree('{{ $pCat->name }}', '{{ app()->getLocale() === 'vi' ? '/chuyen-muc/'.$pCat->slug : '/blog/categories/'.$pCat->slug }}', 'post_category', '{{ $pCat->id }}')" class="text-blue-600 hover:text-blue-800 font-bold text-xs uppercase tracking-tighter">+ Thêm</button>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <hr class="my-2">
                                            
                                            <!-- Custom Link -->
                                            <div class="space-y-2">
                                                <input type="text" id="custom-name" placeholder="Tên liên kết" class="w-full text-xs p-2 border rounded">
                                                <input type="text" id="custom-url" placeholder="URL (https://...)" class="w-full text-xs p-2 border rounded">
                                                <button onclick="addCustomToMenu()" class="w-full py-2 bg-slate-50 border border-dashed rounded-lg text-slate-500 font-bold text-[11px] hover:bg-slate-100 uppercase">
                                                    + Thêm liên kết tự chọn
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tree Editor -->
                                    <div class="col-span-12 lg:col-span-8 bg-white p-6 rounded-xl border shadow-sm min-h-[500px]">
                                        <div id="tree-root" class="nested-area space-y-3 p-2 bg-slate-50/50 rounded-lg min-h-[200px]">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="welcome-state" class="flex flex-col items-center justify-center h-[60vh] text-center">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-hand-pointer text-gray-300 text-3xl"></i>
                                </div>
                                <h2 class="text-xl font-bold text-gray-400">Chọn hoặc tạo một Menu phía trên để bắt đầu</h2>
                            </div>
                    </div>
                </section>

                <!-- Create Menu Modal -->
                <div id="create-modal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50" style="z-index: 9999;">
                    <div class="bg-white w-[450px] rounded-2xl shadow-2xl p-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6">Tạo Menu Mới</h3>
                        <div class="space-y-5">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tên Menu</label>
                                <input type="text" id="new-menu-name" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:border-blue-500 outline-none transition" placeholder="VD: Menu Footer">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Loại hiển thị</label>
                                <div class="grid grid-cols-1 gap-3">
                                    <label class="flex items-center p-3 border rounded-xl cursor-pointer hover:bg-gray-50 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 transition">
                                        <input type="radio" name="m-type" value="mega" checked class="hidden">
                                        <i class="fas fa-th-large text-gray-400 mr-3"></i>
                                        <span class="font-bold text-gray-700">Mega Menu</span>
                                    </label>
                                    <label class="flex items-center p-3 border rounded-xl cursor-pointer hover:bg-gray-50 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 transition">
                                        <input type="radio" name="m-type" value="dropdown" class="hidden">
                                        <i class="fas fa-caret-square-down text-gray-400 mr-3"></i>
                                        <span class="font-bold text-gray-700">Dropdown Menu</span>
                                    </label>
                                    <label class="flex items-center p-3 border rounded-xl cursor-pointer hover:bg-gray-50 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 transition">
                                        <input type="radio" name="m-type" value="normal" class="hidden">
                                        <i class="fas fa-list text-gray-400 mr-3"></i>
                                        <span class="font-bold text-gray-700 text-sm">Menu Thường (Sidebar/Footer)</span>
                                    </label>
                                </div>
                            </div>
                            <div class="flex gap-3 pt-4">
                                <button onclick="closeModal()" class="flex-1 py-3 font-bold text-gray-500 hover:bg-gray-100 rounded-xl transition">Hủy</button>
                                <button onclick="processCreateMenu()" class="flex-1 py-3 font-bold bg-blue-600 text-white rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 transition">Tạo Ngay</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Edit Menu Modal -->
                <div id="edit-modal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50" style="z-index: 9999;">
                    <div class="bg-white w-[450px] rounded-2xl shadow-2xl p-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6">Chỉnh sửa Menu</h3>
                        <div class="space-y-5">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tên Menu</label>
                                <input type="text" id="edit-menu-name" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:border-blue-500 outline-none transition" placeholder="VD: Menu Header">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Loại hiển thị</label>
                                <div class="grid grid-cols-1 gap-3">
                                    <label class="flex items-center p-3 border rounded-xl cursor-pointer hover:bg-gray-50 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 transition">
                                        <input type="radio" name="edit-m-type" value="mega" class="hidden">
                                        <i class="fas fa-th-large text-gray-400 mr-3"></i>
                                        <span class="font-bold text-gray-700">Mega Menu</span>
                                    </label>
                                    <label class="flex items-center p-3 border rounded-xl cursor-pointer hover:bg-gray-50 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 transition">
                                        <input type="radio" name="edit-m-type" value="dropdown" class="hidden">
                                        <i class="fas fa-caret-square-down text-gray-400 mr-3"></i>
                                        <span class="font-bold text-gray-700">Dropdown Menu</span>
                                    </label>
                                    <label class="flex items-center p-3 border rounded-xl cursor-pointer hover:bg-gray-50 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 transition">
                                        <input type="radio" name="edit-m-type" value="normal" class="hidden">
                                        <i class="fas fa-list text-gray-400 mr-3"></i>
                                        <span class="font-bold text-gray-700 text-sm">Menu Thường (Sidebar/Footer)</span>
                                    </label>
                                </div>
                            </div>
                            <div class="flex gap-3 pt-4">
                                <button onclick="closeEditModal()" class="flex-1 py-3 font-bold text-gray-500 hover:bg-gray-100 rounded-xl transition">Hủy</button>
                                <button onclick="processEditMenu()" class="flex-1 py-3 font-bold bg-blue-600 text-white rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 transition">Cập nhật</button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
function handleImageUpload(inputId, fieldName, routeUrl, imgId, placeholderId, successMsg, errorMsg) {
    const input = document.getElementById(inputId);
    if (!input) return;

    input.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append(fieldName, file);
        formData.append('_token', '{{ csrf_token() }}');

        const uploadArea = e.target.nextElementSibling;
        const pText = uploadArea ? uploadArea.querySelector('p.text-sm') : null;
        const originalText = pText ? pText.innerText : '';
        if (pText) pText.innerText = '{{ __('messages.uploading') }}';

        fetch(routeUrl, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') {
                const img = document.getElementById(imgId);
                const placeholder = document.getElementById(placeholderId);
                if (img) {
                    img.src = data.url;
                    img.classList.remove('hidden');
                }
                if (placeholder) placeholder.classList.add('hidden');
                showToast('success', '{{ __('messages.success') }}', data.message || successMsg);
            } else {
                showToast('error', '{{ __('messages.error') }}', data.message || errorMsg);
            }
        })
        .catch((err) => {
            console.error(err);
            showToast('error', '{{ __('messages.error') }}', errorMsg);
        })
        .finally(() => {
            if (pText) pText.innerText = originalText;
            e.target.value = '';
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    handleImageUpload(
        'social-image-upload',
        'image',
        '{{ route("admin.settings.general.social_image") }}', 
        'site-social-image', 
        'site-social-image-placeholder', 
        '{{ __('messages.update_social_image_success') }}', 
        '{{ __('messages.update_social_image_error') }}'
    );

    handleImageUpload(
        'favicon-upload',
        'favicon',
        '{{ route("admin.settings.general.favicon") }}', 
        'site-favicon-preview', 
        'site-favicon-placeholder', 
        '{{ __('messages.update_favicon_success') }}', 
        '{{ __('messages.update_favicon_error') }}'
    );
    var tabs = ['site-info','features','menus'];
    function showTab(id){
        var target = (id === 'stats' || id === 'overview') ? 'site-info' : id;
        tabs.forEach(function(t){
            var s = document.getElementById(t);
            if (s) { if (t === target) { s.classList.remove('hidden'); } else { s.classList.add('hidden'); } }
        });
        var links = document.querySelectorAll('a.tab-link');
        links.forEach(function(a){
            var isActive = a.getAttribute('data-target') === target;
            if (isActive) {
                a.classList.add('text-indigo-600','bg-indigo-50');
                a.classList.remove('text-gray-700');
            } else {
                a.classList.remove('text-indigo-600','bg-indigo-50');
                a.classList.add('text-gray-700');
            }
        });
    }
    var init = (location.hash ? location.hash.replace('#','') : 'site-info');
    showTab(init);
    document.querySelectorAll('a.tab-link').forEach(function(a){
        a.addEventListener('click', function(e){
            e.preventDefault();
            var id = a.getAttribute('data-target') || 'site-info';
            showTab(id);
            try { history.replaceState(null,'','#'+id); } catch(_){}
        });
    });
    // --- Menu Builder Logic ---
    window.menus = {}; 
    window.currentMenuId = null;
    window.generateId = () => 'item_' + Math.random().toString(36).substr(2, 9);

    window.filterSource = function(inputId, containerId) {
        const input = document.getElementById(inputId);
        const filter = input.value.toLowerCase();
        const container = document.getElementById(containerId);
        const labels = container.getElementsByTagName('label');
        
        for (let i = 0; i < labels.length; i++) {
            const span = labels[i].querySelector('span');
            if (span) {
                const txt = span.textContent || span.innerText;
                if (txt.toLowerCase().indexOf(filter) > -1) {
                    labels[i].style.display = "";
                } else {
                    labels[i].style.display = "none";
                }
            }
        }
    };

    window.toggleAccordion = function(id) {
        const el = document.getElementById(id);
        const icon = document.getElementById('icon-' + id);
        if (el) {
            el.classList.toggle('hidden');
            if (icon) icon.classList.toggle('rotate-180');
        }
    };

    window.initMenus = function(serverMenus) {
        if (Array.isArray(serverMenus)) {
            window.menus = {};
            serverMenus.forEach(m => {
                 if(m.id) window.menus[m.id] = m;
                 else {
                     const id = generateId();
                     m.id = id;
                     m.items = m.items || []; 
                     window.menus[id] = m;
                 }
            });
        } else {
            window.menus = serverMenus || {};
        }
        
        renderMenuList();
        const keys = Object.keys(window.menus);
        if (keys.length > 0) {
            switchMenu(keys[0]);
        } else {
            document.getElementById('welcome-state').classList.remove('hidden');
            document.getElementById('editor-container').classList.add('hidden');
        }
    };

    window.renderMenuList = function() {
        const container = document.getElementById('created-menus-list');
        if (!container) return;
        container.innerHTML = '';
        
        Object.values(window.menus).forEach(m => {
            const btn = document.createElement('button');
            btn.className = `inline-flex items-center gap-2 px-3 py-2 rounded-lg border transition mr-2 ${currentMenuId === m.id ? 'bg-blue-50 border-blue-300 text-blue-700' : 'bg-white border-gray-200 text-gray-700 hover:border-blue-300 hover:text-blue-700'}`;
            btn.onclick = () => switchMenu(m.id);
            
            btn.innerHTML = `
                <span class="font-bold text-sm">${m.name}</span>
                <span class="text-[10px] uppercase font-bold ${currentMenuId === m.id ? 'text-blue-500' : 'text-gray-400'}">${m.type || 'Mega Menu'}</span>
            `;
            container.appendChild(btn);
        });
    };

    window.switchMenu = function(id) {
        // Save current if exists
        if (currentMenuId && window.menus[currentMenuId]) {
            window.menus[currentMenuId].items = serializeTree();
        }

        currentMenuId = id;
        const menu = window.menus[id];
        
        document.getElementById('welcome-state').classList.add('hidden');
        document.getElementById('editor-container').classList.remove('hidden');
        
        document.getElementById('editing-title').innerText = menu.name;
        document.getElementById('editing-type').innerText = menu.type || 'Mega Menu';
        
        renderMenuList(); // Update active state in sidebar
        renderTree(menu.items);
    };

    window.openCreateMenuModal = function() {
        document.getElementById('create-modal').classList.remove('hidden');
    };

    window.closeModal = function() {
        document.getElementById('create-modal').classList.add('hidden');
        document.getElementById('new-menu-name').value = '';
    };

    window.processCreateMenu = function() {
        const name = document.getElementById('new-menu-name').value;
        const type = document.querySelector('input[name="m-type"]:checked').value;
        
        if (!name) { alert('Vui lòng nhập tên menu'); return; }
        
        const id = generateId();
        window.menus[id] = { id, name, type, items: [] };
        
        closeModal();
        renderMenuList();
        switchMenu(id);
    };
    
    window.openEditMenuModal = function() {
        if (!currentMenuId) return;
        const m = window.menus[currentMenuId];
        document.getElementById('edit-menu-name').value = m.name || '';
        const type = m.type || 'mega';
        document.querySelectorAll('input[name="edit-m-type"]').forEach(r => { r.checked = (r.value === type); });
        document.getElementById('edit-modal').classList.remove('hidden');
    };
    window.closeEditModal = function() {
        document.getElementById('edit-modal').classList.add('hidden');
    };
    window.processEditMenu = function() {
        if (!currentMenuId) return;
        const name = document.getElementById('edit-menu-name').value;
        const typeEl = document.querySelector('input[name="edit-m-type"]:checked');
        const type = typeEl ? typeEl.value : 'mega';
        if (!name) { alert('Vui lòng nhập tên menu'); return; }
        window.menus[currentMenuId].name = name;
        window.menus[currentMenuId].type = type;
        document.getElementById('editing-title').innerText = name;
        document.getElementById('editing-type').innerText = type;
        renderMenuList();
        closeEditModal();
    };

    window.deleteCurrentMenu = function() {
        if (!currentMenuId) return;
        if (!confirm('Bạn có chắc muốn xóa menu này?')) return;
        delete window.menus[currentMenuId];
        currentMenuId = null;
        renderMenuList();
        
        const keys = Object.keys(window.menus);
        if (keys.length > 0) switchMenu(keys[0]);
        else {
            document.getElementById('welcome-state').classList.remove('hidden');
            document.getElementById('editor-container').classList.add('hidden');
        }
    };

    window.addToTree = function(name, url, type, objectId) {
        if (!currentMenuId) {
             alert('Vui lòng chọn menu để sửa trước');
             return;
        }
        
        const item = {
            id: generateId(),
            name: name,
            url: url,
            type: type,
            objectId: objectId,
            children: []
        };
        
        const root = document.getElementById('tree-root');
        const el = createItemElement(item);
        root.appendChild(el);
        root.scrollTop = root.scrollHeight;
    };

    window.addCustomToMenu = function() {
        const name = document.getElementById('custom-name').value;
        const url = document.getElementById('custom-url').value;
        
        if (!name) { alert('Vui lòng nhập tên'); return; }
        
        if (url && url.trim() !== '') {
            addToTree(name, url.trim(), 'custom', '');
        } else {
            addToTree(name, '', 'heading', '');
        }
        
        document.getElementById('custom-name').value = '';
        document.getElementById('custom-url').value = '';
    };

    function createItemElement(item) {
        const div = document.createElement('div');
        div.className = 'menu-item group relative bg-white border border-gray-200 rounded-lg mb-2';
        div.setAttribute('data-id', item.id);
        div.setAttribute('data-name', item.name);
        div.setAttribute('data-url', item.url);
        div.setAttribute('data-type', item.type || 'custom');
        div.setAttribute('data-object-id', item.objectId || '');

        const labelType = (item.type && item.type !== 'custom') ? item.type : ((item.url && item.url.trim() !== '') ? 'Link' : 'Heading');
        const content = `
            <div class="flex items-center p-3 handle cursor-move bg-gray-50 rounded-t-lg border-b border-gray-100">
                <i class="fas fa-grip-vertical text-gray-400 mr-3"></i>
                <span class="font-bold text-gray-700 text-sm flex-1">${item.name}</span>
                <span class="text-[10px] uppercase font-bold text-gray-400 mr-3">${labelType}</span>
                <button onclick="toggleCollapse('${item.id}')" class="text-gray-400 hover:text-blue-600 transition mr-2"><i class="fas fa-chevron-down"></i></button>
                <button onclick="toggleEditPanel('${item.id}')" class="text-gray-400 hover:text-blue-600 transition mr-2"><i class="fas fa-pen"></i></button>
                <button onclick="deleteItem('${item.id}')" class="text-gray-400 hover:text-red-500 transition"><i class="fas fa-trash-alt"></i></button>
            </div>
            <div class="p-3 space-y-2">
                <div class="edit-panel hidden" id="edit-panel-${item.id}">
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Tên hiển thị</label>
                            <input type="text" class="w-full text-xs border rounded p-1.5" value="${item.name}" onchange="updateItemData(this)">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Đường dẫn</label>
                            <input type="text" class="w-full text-xs border rounded p-1.5" value="${item.url}" onchange="updateItemData(this)">
                        </div>
                    </div>
                </div>
                <div class="nested-sortable min-h-[10px] pl-4 border-l-2 border-gray-100 mt-2 space-y-2" id="children-${item.id}">
                </div>
            </div>
        `;
        div.innerHTML = content;
        
        const childContainer = div.querySelector(`#children-${item.id}`);
        if (item.children && item.children.length > 0) {
            item.children.forEach(c => {
                childContainer.appendChild(createItemElement(c));
            });
        }
        
        new Sortable(childContainer, {
            group: 'nested',
            animation: 150,
            fallbackOnBody: true,
            swapThreshold: 0.65,
            handle: '.handle',
            ghostClass: 'bg-blue-50'
        });

        return div;
    }

    window.renderTree = function(items) {
        const root = document.getElementById('tree-root');
        root.innerHTML = '';
        
        if (items && items.length > 0) {
            items.forEach(item => {
                root.appendChild(createItemElement(item));
            });
        }
        
        new Sortable(root, {
            group: 'nested',
            animation: 150,
            fallbackOnBody: true,
            swapThreshold: 0.65,
            handle: '.handle',
            ghostClass: 'bg-blue-50'
        });
    };

    window.deleteItem = function(id) {
        if (confirm('Xóa mục này?')) {
            const el = document.querySelector(`.menu-item[data-id="${id}"]`);
            if (el) el.remove();
        }
    };

    window.updateItemData = function(input) {
        // No op - DOM is source of truth
    };

    window.toggleEditPanel = function(id) {
        const panel = document.getElementById(`edit-panel-${id}`);
        if (!panel) return;
        panel.classList.toggle('hidden');
    };

    window.toggleCollapse = function(id) {
        const el = document.querySelector(`.menu-item[data-id="${id}"]`);
        if (!el) return;
        const collapsed = el.getAttribute('data-collapsed') === 'true';
        const containers = el.querySelectorAll('.nested-sortable');
        containers.forEach(c => {
            if (collapsed) c.classList.remove('hidden');
            else c.classList.add('hidden');
        });
        el.setAttribute('data-collapsed', collapsed ? 'false' : 'true');
    };

    window.serializeTree = function() {
        const root = document.getElementById('tree-root');
        return getChildren(root);
    };

    function getChildren(container) {
        const items = [];
        const children = container.children;
        for (let i = 0; i < children.length; i++) {
            const el = children[i];
            if (!el.classList.contains('menu-item')) continue;
            
            const id = el.getAttribute('data-id');
            const inputs = el.querySelectorAll('input');
            const name = inputs[0].value;
            const url = inputs[1].value;
            const type = el.getAttribute('data-type');
            const objectId = el.getAttribute('data-object-id');
            
            const childContainer = el.querySelector(`#children-${id}`);
            const subItems = getChildren(childContainer);
            
            items.push({
                id, name, url, type, objectId,
                children: subItems
            });
        }
        return items;
    }

    window.saveMenus = function() {
        if (currentMenuId) {
            window.menus[currentMenuId].items = serializeTree();
        }
        
        fetch('{{ route("admin.settings.menus.save") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ menus: Object.values(window.menus) })
        })
        .then(r => r.json())
        .then(data => {
            if(data.status === 'success') {
                showToast('success', 'Thành công', 'Đã lưu cấu trúc menu');
            } else {
                showToast('error', 'Lỗi', 'Không thể lưu menu');
            }
        })
        .catch(e => {
            console.error(e);
            showToast('error', 'Lỗi', 'Đã xảy ra lỗi hệ thống');
        });
    };



    const serverMenus = {!! json_encode($menus ?? []) !!};
    initMenus(serverMenus);

});
</script>
@endsection
