@extends('layouts.admin')

@section('title', __('messages.manage_comments'))

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ __('messages.comments') }}</h1>
                <p class="text-sm text-gray-500 font-medium">{{ __('messages.comments_desc') }}</p>
            </div>
            <div class="flex gap-2">
                <button class="bg-white border border-gray-300 px-4 py-2 rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-50 transition shadow-sm">
                    <i class="fa-solid fa-file-export mr-2"></i> {{ __('messages.export_csv') }}
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mr-4">
                    <i class="fa-solid fa-comments text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">{{ __('messages.total_comments') }}</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total']) }}</p>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center">
                <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-xl flex items-center justify-center mr-4">
                    <i class="fa-solid fa-clock-rotate-left text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">{{ __('messages.pending_approval') }}</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['pending']) }}</p>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center">
                <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center mr-4">
                    <i class="fa-solid fa-check-double text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">{{ __('messages.approved_replied') }}</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['approved_percent'] }}%</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-white flex flex-col sm:flex-row gap-4 justify-between items-center">
                <div class="relative w-full sm:w-80">
                    <form action="{{ route('admin.posts.comments') }}" method="GET">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('messages.search_comments') }}" class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                    </form>
                </div>
                <div class="flex gap-2 w-full sm:w-auto">
                    <form action="{{ route('admin.posts.comments') }}" method="GET" id="filter-form">
                        <select name="status" onchange="document.getElementById('filter-form').submit()" class="bg-gray-50 border border-gray-200 text-gray-600 text-sm rounded-xl px-4 py-2 focus:outline-none cursor-pointer">
                            <option value="">{{ __('messages.all_status') }}</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>{{ __('messages.approved') }}</option>
                            <option value="spam" {{ request('status') == 'spam' ? 'selected' : '' }}>{{ __('messages.spam') }}</option>
                        </select>
                    </form>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/50 text-gray-500 text-[11px] uppercase tracking-[0.05em] font-bold">
                        <tr>
                            <th class="px-6 py-4">{{ __('messages.member') }}</th>
                            <th class="px-6 py-4">{{ __('messages.comment') }}</th>
                            <th class="px-6 py-4">{{ __('messages.post') }}</th>
                            <th class="px-6 py-4">{{ __('messages.status') }}</th>
                            <th class="px-6 py-4 text-right">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($comments as $comment)
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->name ?? 'Guest') }}&background=random&color=fff" class="w-10 h-10 rounded-full border border-gray-200" alt="avatar">
                                    <div class="ml-3">
                                        <p class="text-sm font-bold text-gray-800 leading-none mb-1">{{ $comment->name ?? __('messages.guest') }}</p>
                                        <p class="text-[11px] text-gray-500">{{ $comment->email ?? __('messages.not_available') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-700 line-clamp-1 max-w-xs font-medium">{{ $comment->content }}</p>
                                <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-tighter italic">{{ $comment->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.posts.edit', $comment->post_id) }}" class="text-xs font-semibold text-blue-600 hover:underline cursor-pointer italic">{{ Str::limit($comment->post->title ?? __('messages.deleted_post'), 30) }}</a>
                            </td>
                            <td class="px-6 py-4">
                                @if($comment->status == 'pending')
                                    <span class="px-2 py-1 text-[10px] font-bold bg-yellow-100 text-yellow-700 rounded-md uppercase">{{ __('messages.pending') }}</span>
                                @elseif($comment->status == 'approved')
                                    <span class="px-2 py-1 text-[10px] font-bold bg-green-50 text-green-600 rounded-md uppercase border border-green-100">{{ __('messages.approved') }}</span>
                                @elseif($comment->status == 'spam')
                                    <span class="px-2 py-1 text-[10px] font-bold bg-red-50 text-red-600 rounded-md uppercase border border-red-100">{{ __('messages.spam') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button onclick="openModal({{ json_encode($comment) }}, '{{ $comment->post->title ?? '' }}')" class="bg-blue-600 hover:bg-blue-700 text-white text-[11px] font-bold px-4 py-2 rounded-lg transition shadow-md shadow-blue-100">
                                    {{ __('messages.process_now') }}
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 italic">{{ __('messages.no_comments_found') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
                {{ $comments->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="commentModal" class="fixed inset-0 z-50 hidden transition-all duration-300">
        <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-md" onclick="closeModal()"></div>
        
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl p-4">
            <div class="bg-white rounded-[2rem] shadow-2xl overflow-hidden flex flex-col max-h-[90vh] border border-gray-100">
                
                <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-xl text-gray-800">{{ __('messages.comment_modal_title') }}</h3>
                        <p class="text-xs text-gray-400 font-medium uppercase mt-1" id="modal-comment-id">ID: #</p>
                    </div>
                    <button onclick="closeModal()" class="w-10 h-10 flex items-center justify-center rounded-2xl hover:bg-gray-100 text-gray-400 transition">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <div class="p-8 overflow-y-auto custom-scrollbar">
                    <div class="flex items-start justify-between mb-8">
                        <div class="flex items-center">
                            <img id="modal-avatar" src="" class="w-14 h-14 rounded-2xl border-2 border-white shadow-lg" alt="">
                            <div class="ml-4">
                                <h4 class="font-bold text-gray-900 text-lg" id="modal-author-name"></h4>
                                <p class="text-xs text-blue-600 font-bold italic underline" id="modal-author-email"></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">{{ __('messages.ip_address') }}</p>
                            <span class="text-xs bg-gray-100 font-mono px-3 py-1.5 rounded-lg text-gray-600" id="modal-ip"></span>
                        </div>
                    </div>

                    <div class="bg-blue-50/50 p-6 rounded-[1.5rem] border border-blue-100/50 mb-8 relative">
                        <i class="fa-solid fa-quote-left absolute -top-3 -left-1 text-blue-200 text-3xl"></i>
                        <p class="text-xs font-bold text-blue-600 mb-3 uppercase tracking-widest">{{ __('messages.sender_content') }}:</p>
                        <p class="text-gray-700 leading-relaxed text-[15px] italic">
                            "<span id="modal-content"></span>"
                        </p>
                        <p class="mt-2 text-xs text-gray-500">{{ __('messages.post') }}: <span id="modal-post-title" class="font-semibold"></span></p>
                    </div>

                    <div class="space-y-4">
                        <form id="reply-form" method="POST" action="">
                            @csrf
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-bold text-gray-700">{{ __('messages.admin_reply') }}</label>
                                <span class="text-[10px] text-gray-400 font-medium italic">{{ __('messages.markdown_support') }}</span>
                            </div>
                            <div class="border border-gray-200 rounded-[1.5rem] overflow-hidden focus-within:ring-4 focus-within:ring-blue-500/10 focus-within:border-blue-400 transition-all shadow-inner">
                                <textarea name="content" rows="4" class="w-full p-5 text-sm outline-none resize-none bg-gray-50/30" placeholder="{{ __('messages.reply_placeholder') }}"></textarea>
                                <div class="bg-white px-5 py-3 flex justify-between items-center border-t border-gray-100">
                                    <div class="flex space-x-4 text-gray-400">
                                        <button type="button" class="hover:text-blue-600 transition"><i class="fa-solid fa-image text-base"></i></button>
                                        <button type="button" class="hover:text-blue-600 transition"><i class="fa-solid fa-face-smile text-base"></i></button>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <input type="checkbox" id="sendMail" class="rounded text-blue-600">
                                        <label for="sendMail" class="text-[10px] font-bold text-gray-500 uppercase cursor-pointer">{{ __('messages.send_email_notification') }}</label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-100 flex flex-wrap gap-4 justify-between items-center">
                    <div class="flex space-x-2">
                        <form id="delete-form" method="POST" action="" onsubmit="return confirm('{{ __('messages.confirm_delete_comment') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-10 h-10 flex items-center justify-center rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition" title="{{ __('messages.delete') }}">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                        
                        <form id="spam-form" method="POST" action="">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="spam">
                            <button type="submit" class="w-10 h-10 flex items-center justify-center rounded-xl bg-orange-50 text-orange-500 hover:bg-orange-500 hover:text-white transition" title="{{ __('messages.mark_as_spam') }}">
                                <i class="fa-solid fa-circle-exclamation"></i>
                            </button>
                        </form>
                    </div>
                    <div class="flex space-x-3">
                        <button onclick="closeModal()" class="px-6 py-2.5 text-sm font-bold text-gray-500 hover:text-gray-800 transition">{{ __('messages.close') }}</button>
                        <button onclick="document.getElementById('reply-form').submit()" class="px-8 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-lg shadow-blue-200 transition transform active:scale-95">
                            {{ __('messages.send_and_approve') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('commentModal');

        function openModal(comment, postTitle) {
            // Populate data
            document.getElementById('modal-comment-id').innerText = '{{ __('messages.id') }}: #' + comment.id;
            document.getElementById('modal-author-name').innerText = comment.name || '{{ __('messages.guest') }}';
            document.getElementById('modal-author-email').innerText = comment.email || '{{ __('messages.not_available') }}';
            document.getElementById('modal-ip').innerText = comment.ip_address || '{{ __('messages.unknown') }}';
            document.getElementById('modal-content').innerText = comment.content;
            document.getElementById('modal-post-title').innerText = postTitle;
            document.getElementById('modal-avatar').src = `https://ui-avatars.com/api/?name=${encodeURIComponent(comment.name || '{{ __('messages.guest') }}')}&background=0D8ABC&color=fff`;

            // Update forms
            document.getElementById('reply-form').action = `/admin/posts/comments/${comment.id}/reply`;
            document.getElementById('delete-form').action = `/admin/posts/comments/${comment.id}`;
            document.getElementById('spam-form').action = `/admin/posts/comments/${comment.id}`;

            // Show modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                modal.querySelector('.absolute.top-1\\/2').classList.add('scale-100');
            }, 10);
        }

        function closeModal() {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
@endsection
