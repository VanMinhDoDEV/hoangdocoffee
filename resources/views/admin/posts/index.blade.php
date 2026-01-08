@extends('layouts.admin')

@section('title', __('messages.all_posts'))

@section('content')
<div class="max-w-[1400px] mx-auto">
        
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">{{ __('messages.all_posts') }}</h1>
            <p class="text-gray-500 mt-1">{!! __('messages.total_posts_count', ['count' => '<span class="font-bold text-blue-600">' . number_format($stats['total']) . '</span>']) !!}</p>
        </div>
        <a href="{{ route('admin.posts.create') }}" class="mt-4 sm:mt-0 inline-flex items-center justify-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg transition-all shadow-md shadow-blue-200">
            <i class="fa-solid fa-plus mr-2"></i> {{ __('messages.create_new_post') }}
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">
        <form action="{{ route('admin.posts.index') }}" method="GET" class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-3">
                <div class="relative">
                    <select name="date" class="appearance-none bg-gray-50 border border-gray-300 text-gray-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pr-10 outline-none">
                        <option value="">{{ __('messages.all_days') }}</option>
                        <option value="this_month" {{ ($filters['date'] ?? '') == 'this_month' ? 'selected' : '' }}>{{ __('messages.this_month') }}</option>
                        <option value="last_month" {{ ($filters['date'] ?? '') == 'last_month' ? 'selected' : '' }}>{{ __('messages.last_month') }}</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-3 top-3.5 text-xs text-gray-400 pointer-events-none"></i>
                </div>
                <div class="relative">
                    <select name="category" class="appearance-none bg-gray-50 border border-gray-300 text-gray-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pr-10 outline-none">
                        <option value="all">{{ __('messages.category_all') }}</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ ($filters['category'] ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-3 top-3.5 text-xs text-gray-400 pointer-events-none"></i>
                </div>
                <button type="submit" class="px-5 py-2.5 bg-gray-800 text-white text-sm font-semibold rounded-lg hover:bg-gray-900 transition">
                    {{ __('messages.filter_data') }}
                </button>
            </div>

            <div class="relative group">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="fa-solid fa-magnifying-glass text-gray-400 group-focus-within:text-blue-500"></i>
                </span>
                <input type="text" name="q" value="{{ $filters['q'] }}" class="block w-full lg:w-80 pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="{{ __('messages.search_post_title') }}">
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4 font-bold text-center w-12">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-4 font-bold">{{ __('messages.post_title') }}</th>
                        <th class="px-6 py-4 font-bold">{{ __('messages.author') }}</th>
                        <th class="px-6 py-4 font-bold">{{ __('messages.category') }}</th>
                        <th class="px-6 py-4 font-bold">{{ __('messages.tags') }}</th>
                        <th class="px-6 py-4 font-bold text-center">{{ __('messages.status') }}</th>
                        <th class="px-6 py-4 font-bold">{{ __('messages.time') }}</th>
                        <th class="px-6 py-4 font-bold text-right">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($posts as $post)
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        <td class="px-6 py-4 text-center">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded">
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <img class="h-10 w-14 object-cover rounded border border-gray-200 {{ $post->status === 'draft' ? 'opacity-75' : '' }}" src="{{ $post->thumbnail ? asset('storage/' . $post->thumbnail) : 'https://via.placeholder.com/150' }}" alt="">
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-gray-900 group-hover:text-blue-600 cursor-pointer line-clamp-1 {{ $post->status === 'draft' ? 'text-gray-400 italic underline decoration-dotted' : '' }}">{{ $post->title }}</div>
                                    <div class="text-[11px] text-gray-400 flex items-center mt-1">
                                        @if($post->status === 'published')
                                            <i class="fa-solid fa-eye mr-1"></i> {{ number_format($post->views) }} {{ strtolower(__('messages.views')) }}
                                        @else
                                            {{ __('messages.no_data') }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-700">{{ $post->author->name ?? __('messages.unknown') }}</td>
                        <td class="px-6 py-4">
                            <span class="text-gray-600">{{ $post->category->name ?? __('messages.uncategorized') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($post->tags->count() > 0)
                                <div class="flex gap-1 flex-wrap">
                                    @foreach($post->tags as $tag)
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-500 rounded text-[10px]">{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($post->status === 'published')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <span class="w-1.5 h-1.5 mr-1.5 bg-green-500 rounded-full"></span> {{ __('messages.published') }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    <span class="w-1.5 h-1.5 mr-1.5 bg-orange-500 rounded-full"></span> {{ __('messages.draft') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs {{ $post->status === 'draft' ? 'text-orange-400 italic' : '' }}">
                            @if($post->status === 'draft')
                                {{ __('messages.modified_at') }} {{ $post->created_at->diffForHumans() }}
                            @else
                                <div>{{ $post->created_at->format('d/m/Y') }}</div>
                                <div class="text-[10px] italic">{{ __('messages.at_time') }} {{ $post->created_at->format('H:i') }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.posts.edit', $post->id) }}" class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition" title="{{ __('messages.edit') }}"><i class="fa-solid fa-pen"></i></a>
                                <form method="post" action="{{ route('admin.posts.destroy', $post->id) }}" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition" title="{{ __('messages.delete') }}"><i class="fa-solid fa-trash-can"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                            {{ __('messages.no_posts_found') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-gray-50 px-6 py-4 border-t flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <select class="bg-white border border-gray-300 text-gray-700 text-xs rounded-lg p-1.5 outline-none" onchange="window.location.href=this.value">
                    <option value="{{ request()->fullUrlWithQuery(['per_page' => 10]) }}" {{ request('per_page') == 10 ? 'selected' : '' }}>{{ __('messages.show_per_page', ['count' => 10]) }}</option>
                    <option value="{{ request()->fullUrlWithQuery(['per_page' => 20]) }}" {{ request('per_page') == 20 ? 'selected' : '' }}>{{ __('messages.show_per_page', ['count' => 20]) }}</option>
                    <option value="{{ request()->fullUrlWithQuery(['per_page' => 50]) }}" {{ request('per_page') == 50 ? 'selected' : '' }}>{{ __('messages.show_per_page', ['count' => 50]) }}</option>
                </select>
                <span class="text-xs text-gray-500">{{ __('messages.showing_posts', ['first' => $posts->firstItem() ?? 0, 'last' => $posts->lastItem() ?? 0, 'total' => number_format($posts->total())]) }}</span>
            </div>
            <div class="inline-flex rounded-md shadow-sm">
                {{ $posts->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>
@endsection
