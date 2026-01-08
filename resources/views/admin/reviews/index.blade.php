@extends('layouts.admin')

@section('title', __('messages.reviews_dashboard'))

@section('content')
<div class="w-full px-4 py-6 sm:px-6 lg:px-8">
    <!-- Header Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Rating Overview -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-5xl font-bold text-gray-900">{{ number_format($avgRating, 2) }}</span>
                        <span class="text-gray-500 text-sm">/ 5</span>
                    </div>
                    <p class="text-gray-600 mt-2">{{ __('messages.total_reviews', ['count' => $totalReviews]) }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ __('messages.all_reviews_genuine') }}</p>
                </div>
                <div class="flex items-center gap-1 bg-green-50 text-green-700 px-3 py-1 rounded-full text-sm font-medium">
                    <span>+{{ $newReviewsThisWeek }}</span>
                    <span class="text-xs">{{ __('messages.this_week') }}</span>
                </div>
            </div>
            
            <!-- Star Rating Bars -->
            <div class="space-y-2 mt-6">
                @foreach($distribution as $stars => $data)
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-600 w-12">{{ $stars }} {{ __('messages.star') }}</span>
                    <div class="flex-1 bg-gray-100 rounded-full h-2 overflow-hidden">
                        <div class="bg-yellow-400 h-full rounded-full" style="width: {{ $data['percentage'] }}%"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-900 w-8 text-right">{{ $data['count'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Reviews Statistics -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">{{ __('messages.reviews_statistics') }}</h3>
            <div class="space-y-6">
                <div>
                    <div class="flex items-baseline gap-2 mb-2">
                        <span class="text-4xl font-bold text-gray-900">{{ $newReviewsThisWeek }}</span>
                        <span class="text-sm text-gray-500">{{ __('messages.new_reviews') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($growth >= 0)
                            <span class="text-green-600 text-sm font-medium">+{{ number_format($growth, 1) }}%</span>
                        @else
                            <span class="text-red-600 text-sm font-medium">{{ number_format($growth, 1) }}%</span>
                        @endif
                        <span class="text-gray-400 text-xs">{{ __('messages.vs_last_week') }}</span>
                    </div>
                </div>
                
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                        </svg>
                        <span class="font-semibold text-blue-900">{{ __('messages.weekly_report') }}</span>
                    </div>
                    <p class="text-sm text-blue-700">{{ __('messages.performance_improving') }}</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-sm p-6 text-white">
            <h3 class="text-lg font-semibold mb-4">{{ __('messages.quick_actions') }}</h3>
            <div class="space-y-3">
                <button class="w-full bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-lg px-4 py-3 text-left transition-all duration-200 hover:translate-x-1">
                    <div class="font-medium">{{ __('messages.export_reviews') }}</div>
                    <div class="text-sm text-blue-100">{{ __('messages.download_csv_pdf') }}</div>
                </button>
            </div>
        </div>
    </div>

    <!-- Reviews Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <!-- Table Header -->
        <div class="p-6 border-b border-gray-100">
            <form method="GET" action="{{ route('admin.reviews.index') }}" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4 flex-1">
                    <div class="relative flex-1 max-w-md">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.search_review') }}" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    {{ __('messages.filter') }}
                </button>
            </form>

            <div class="flex gap-2 mt-4 flex-wrap">
                <a href="{{ route('admin.reviews.index') }}" class="px-4 py-1.5 rounded-full text-sm font-medium {{ !request('status') || request('status') == 'all' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ __('messages.all') }}
                </a>
                <a href="{{ route('admin.reviews.index', array_merge(request()->all(), ['status' => 'published'])) }}" class="px-4 py-1.5 rounded-full text-sm font-medium {{ request('status') == 'published' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ __('messages.published') }}
                </a>
                <a href="{{ route('admin.reviews.index', array_merge(request()->all(), ['status' => 'pending'])) }}" class="px-4 py-1.5 rounded-full text-sm font-medium {{ request('status') == 'pending' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ __('messages.pending') }}
                </a>
                <a href="{{ route('admin.reviews.index', array_merge(request()->all(), ['rating' => '5'])) }}" class="px-4 py-1.5 rounded-full text-sm font-medium {{ request('rating') == '5' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ __('messages.5_star') }}
                </a>
            </div>
        </div>

        <!-- Bulk Actions Form -->
        <form id="bulk-action-form" action="{{ route('admin.reviews.bulk_action') }}" method="POST">
            @csrf
            
            <!-- Table Content -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left">
                                <input type="checkbox" id="select-all-checkbox" class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-2 focus:ring-blue-500 cursor-pointer">
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.product') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.reviewer') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.review') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.date') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.status') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100" id="reviews-tbody">
                        @forelse($reviews as $review)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <input type="checkbox" name="ids[]" value="{{ $review->id }}" class="row-checkbox w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-2 focus:ring-blue-500 cursor-pointer">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                        @if($review->product && $review->product->images->count() > 0)
                                            @php
                                                $imageUrl = $review->product->images->first()->url;
                                                if (!str_starts_with($imageUrl, 'http')) {
                                                    $imageUrl = Storage::url($imageUrl);
                                                }
                                            @endphp
                                            <img src="{{ $imageUrl }}" alt="" class="w-10 h-10 object-cover rounded-lg">
                                        @else
                                            <span class="text-blue-600 font-semibold text-xs">{{ $review->product ? substr($review->product->name, 0, 2) : 'NA' }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900 line-clamp-1 max-w-[150px]" title="{{ $review->product->name ?? 'Unknown Product' }}">{{ $review->product->name ?? __('messages.unknown_product') }}</div>
                                        <div class="text-sm text-gray-500">{{ $review->product->brand ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($review->user && $review->user->avatar)
                                        @php
                                            $avatarUrl = $review->user->avatar;
                                            if (!str_starts_with($avatarUrl, 'http')) {
                                                $avatarUrl = Storage::url($avatarUrl);
                                            }
                                        @endphp
                                        <img src="{{ $avatarUrl }}" alt="{{ $review->reviewer_name }}" class="w-10 h-10 rounded-full object-cover flex-shrink-0">
                                    @else
                                        <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-pink-400 rounded-full flex items-center justify-center flex-shrink-0 text-white font-bold">
                                            {{ substr($review->reviewer_name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $review->reviewer_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $review->reviewer_email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-md">
                                    <div class="flex gap-1 mb-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }}">★</span>
                                        @endfor
                                    </div>
                                    @if($review->title)
                                        <div class="font-medium text-gray-900 mb-1">{{ $review->title }}</div>
                                    @endif
                                    <div class="text-sm text-gray-600 line-clamp-2" title="{{ $review->content }}">{{ $review->content }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-600">{{ $review->created_at->format('d-m-Y') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium 
                                    @if($review->status === 'published') bg-green-100 text-green-800
                                    @elseif($review->status === 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ __('messages.status_' . $review->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4"> 
                                <div class="relative"> 
                                    <button type="button" class="action-menu-btn p-2 hover:bg-gray-100 rounded-lg transition-colors" data-review-id="{{ $review->id }}"> 
                                        <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 24 24"> 
                                            <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"></path> 
                                        </svg> 
                                    </button> 
                                    <div class="action-menu absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50 hidden"> 
                                        <button type="button" onclick="openViewModal({{ json_encode($review->load(['product', 'user'])) }})" class="view-btn w-full px-4 py-2 text-left hover:bg-gray-50 transition-colors flex items-center gap-3 text-gray-700"> 
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"> 
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path> 
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path> 
                                            </svg> 
                                            <span class="font-medium">{{ __('messages.view') }}</span> 
                                        </button> 
                                        @if($review->status === 'pending')
                                        <button type="button" onclick="updateStatus({{ $review->id }}, 'published')" class="w-full px-4 py-2 text-left hover:bg-gray-50 transition-colors flex items-center gap-3 text-green-600"> 
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"> 
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path> 
                                            </svg> 
                                            <span class="font-medium">{{ __('messages.approve') }}</span> 
                                        </button>
                                        @elseif($review->status === 'published')
                                        <button type="button" onclick="updateStatus({{ $review->id }}, 'hidden')" class="w-full px-4 py-2 text-left hover:bg-gray-50 transition-colors flex items-center gap-3 text-gray-700"> 
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"> 
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path> 
                                            </svg> 
                                            <span class="font-medium">{{ __('messages.hide') }}</span> 
                                        </button>
                                        @endif 
                                        <button type="button" onclick="deleteReview({{ $review->id }})" class="delete-btn w-full px-4 py-2 text-left hover:bg-red-50 transition-colors flex items-center gap-3 text-red-600"> 
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"> 
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path> 
                                            </svg> 
                                            <span class="font-medium">{{ __('messages.delete') }}</span> 
                                        </button> 
                                    </div> 
                                </div> 
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                {{ __('messages.no_reviews_found') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Bulk Actions (Hidden input) -->
            <input type="hidden" name="action" id="bulk-action-input">
        </form>

        <!-- Hidden Forms for Actions -->
        @foreach($reviews as $review)
            <form id="status-form-{{ $review->id }}" action="{{ route('admin.reviews.update', $review->id) }}" method="POST" class="hidden">
                @csrf
                @method('PUT')
            </form>
            <form id="delete-form-{{ $review->id }}" action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endforeach

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $reviews->links() }}
        </div>
        
        <!-- Bulk Action Buttons (displayed when items selected) -->
        <div id="bulk-actions-bar" class="fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-white border border-gray-200 shadow-lg rounded-full px-6 py-3 hidden flex items-center gap-4 z-50">
            <span class="text-sm font-medium text-gray-700"><span id="selected-count">0</span> {{ __('messages.selected') }}</span>
            <div class="h-4 w-px bg-gray-300"></div>
            <button type="button" onclick="submitBulkAction('published')" class="text-sm font-medium text-green-600 hover:text-green-700">
                {{ __('messages.approve_selected') }}
            </button>
            <button type="button" onclick="submitBulkAction('hidden')" class="text-sm font-medium text-gray-600 hover:text-gray-700">
                {{ __('messages.hide_selected') }}
            </button>
            <button type="button" onclick="submitBulkAction('delete')" class="text-sm font-medium text-red-600 hover:text-red-700">
                {{ __('messages.delete_selected') }}
            </button>
        </div>

    </div>
</div>

    <!-- View Review Modal -->
    <div id="view-review-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-xl shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center pb-3 border-b">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">{{ __('messages.review_details') }}</h3>
                    <button onclick="closeModal('view-review-modal')" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="mt-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('messages.product') }}</label>
                        <p id="view-product-name" class="mt-1 text-sm text-gray-900 font-semibold"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('messages.reviewer') }}</label>
                        <p id="view-reviewer-name" class="mt-1 text-sm text-gray-900"></p>
                        <p id="view-reviewer-email" class="text-xs text-gray-500"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('messages.rating') }}</label>
                        <div id="view-rating" class="mt-1 flex text-yellow-400"></div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('messages.title') }}</label>
                        <p id="view-title" class="mt-1 text-sm text-gray-900 italic"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('messages.content') }}</label>
                        <p id="view-content" class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('messages.status') }}</label>
                        <span id="view-status" class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full"></span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('messages.date') }}</label>
                        <p id="view-date" class="mt-1 text-sm text-gray-900"></p>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button onclick="closeModal('view-review-modal')" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        {{ __('messages.close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Review Modal -->
    <div id="edit-review-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center pb-3 border-b">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('messages.update_status') }}</h3>
                    <button onclick="closeModal('edit-review-modal')" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form id="edit-review-form" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mt-4">
                        <label for="edit-status" class="block text-sm font-medium text-gray-700">{{ __('messages.status') }}</label>
                        <select id="edit-status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="pending">{{ __('messages.pending') }}</option>
                            <option value="published">{{ __('messages.published') }}</option>
                            <option value="hidden">{{ __('messages.hidden') }}</option>
                        </select>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="closeModal('edit-review-modal')" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            {{ __('messages.cancel') }}
                        </button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            {{ __('messages.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script>
    // Action menu functionality
    document.addEventListener('click', (e) => {
        // Close all menus when clicking outside
        if (!e.target.closest('.action-menu-btn') && !e.target.closest('.action-menu')) {
            document.querySelectorAll('.action-menu').forEach(menu => {
                menu.classList.add('hidden');
            });
        }
        
        // Toggle menu when clicking the button
        if (e.target.closest('.action-menu-btn')) {
            e.stopPropagation();
            const button = e.target.closest('.action-menu-btn');
            const menu = button.nextElementSibling;
            const allMenus = document.querySelectorAll('.action-menu');
            
            // Close other menus
            allMenus.forEach(m => {
                if (m !== menu) {
                    m.classList.add('hidden');
                }
            });
            
            // Toggle current menu
            menu.classList.toggle('hidden');
        }
    });

    function openViewModal(review) {
        document.getElementById('view-product-name').textContent = review.product ? review.product.name : '{{ __('messages.unknown_product') }}';
        document.getElementById('view-reviewer-name').textContent = review.reviewer_name;
        document.getElementById('view-reviewer-email').textContent = review.reviewer_email;
        document.getElementById('view-title').textContent = review.title || '';
        document.getElementById('view-content').textContent = review.content;
        
        // Format date to d-m-Y
        const date = new Date(review.created_at);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        document.getElementById('view-date').textContent = `${day}-${month}-${year}`;
        
        // Rating
        let stars = '';
        for (let i = 1; i <= 5; i++) {
            stars += i <= review.rating ? '★' : '<span class="text-gray-200">★</span>';
        }
        document.getElementById('view-rating').innerHTML = stars;

        // Status
        const statusEl = document.getElementById('view-status');
        statusEl.textContent = review.status.charAt(0).toUpperCase() + review.status.slice(1);
        statusEl.className = 'mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full';
        if (review.status === 'published') statusEl.classList.add('bg-green-100', 'text-green-800');
        else if (review.status === 'pending') statusEl.classList.add('bg-yellow-100', 'text-yellow-800');
        else statusEl.classList.add('bg-gray-100', 'text-gray-800');

        document.getElementById('view-review-modal').classList.remove('hidden');
        // Hide action menu
        document.querySelectorAll('.action-menu').forEach(menu => menu.classList.add('hidden'));
    }

    function openEditModal(review) {
        const form = document.getElementById('edit-review-form');
        form.action = `/admin/reviews/${review.id}`;
        document.getElementById('edit-status').value = review.status;
        
        document.getElementById('edit-review-modal').classList.remove('hidden');
        // Hide action menu
        document.querySelectorAll('.action-menu').forEach(menu => menu.classList.add('hidden'));
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    function updateStatus(id, status) {
        const form = document.getElementById('status-form-' + id);
        if (form) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'status';
            input.value = status;
            form.appendChild(input);
            form.submit();
        }
        // Hide action menu
        document.querySelectorAll('.action-menu').forEach(menu => menu.classList.add('hidden'));
    }

    function deleteReview(id) {
        if(confirm('{{ __('messages.confirm_delete') }}')) {
            document.getElementById('delete-form-' + id).submit();
        }
        // Hide action menu
        document.querySelectorAll('.action-menu').forEach(menu => menu.classList.add('hidden'));
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('select-all-checkbox');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        const bulkActionsBar = document.getElementById('bulk-actions-bar');
        const selectedCountSpan = document.getElementById('selected-count');
        const bulkActionForm = document.getElementById('bulk-action-form');
        const bulkActionInput = document.getElementById('bulk-action-input');

        function updateBulkActionsVisibility() {
            const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
            selectedCountSpan.textContent = checkedCount;
            if (checkedCount > 0) {
                bulkActionsBar.classList.remove('hidden');
            } else {
                bulkActionsBar.classList.add('hidden');
            }
        }

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                rowCheckboxes.forEach(cb => cb.checked = this.checked);
                updateBulkActionsVisibility();
            });
        }

        rowCheckboxes.forEach(cb => {
            cb.addEventListener('change', updateBulkActionsVisibility);
        });

        window.submitBulkAction = function(action) {
            if (action === 'delete' && !confirm('{{ __('messages.confirm_delete_selected') }}')) {
                return;
            }
            bulkActionInput.value = action;
            bulkActionForm.submit();
        };
    });
</script>
@endsection
