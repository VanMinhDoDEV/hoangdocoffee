@extends('layouts.admin')
@section('title', __('messages.users'))
@section('content')
<div class="space-y-6">
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-white p-6 rounded-2xl shadow-sm flex items-center space-x-4 stat-card">
      <div class="bg-indigo-100 p-3 rounded-lg text-indigo-600"><i class="fa-solid fa-users text-xl"></i></div>
      <div>
        <h3 class="text-2xl font-bold">{{ $stats['total'] ?? ($users->total() ?? 0) }}</h3>
        <p class="text-gray-500 text-sm">Tổng người dùng</p>
      </div>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm flex items-center space-x-4 stat-card">
      <div class="bg-emerald-100 p-3 rounded-lg text-emerald-600"><i class="fa-solid fa-user-check text-xl"></i></div>
      <div>
        <h3 class="text-2xl font-bold">{{ $stats['verified'] ?? 0 }}</h3>
        <p class="text-gray-500 text-sm">Email đã xác minh</p>
      </div>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm flex items-center space-x-4 stat-card">
      <div class="bg-amber-100 p-3 rounded-lg text-amber-600"><i class="fa-solid fa-envelope-open text-xl"></i></div>
      <div>
        <h3 class="text-2xl font-bold">{{ $stats['unverified'] ?? 0 }}</h3>
        <p class="text-gray-500 text-sm">Chưa xác minh</p>
      </div>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm flex items-center space-x-4 stat-card">
      <div class="bg-purple-100 p-3 rounded-lg text-purple-600"><i class="fa-solid fa-user-shield text-xl"></i></div>
      <div>
        <h3 class="text-2xl font-bold">{{ $stats['admins'] ?? 0 }}</h3>
        <p class="text-gray-500 text-sm">Quản trị viên</p>
      </div>
    </div>
  </div>

  <div class="bg-white shadow-lg rounded-xl p-6">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
      <div class="flex gap-3">
        <select id="filter-role" class="input-primary bg-white">
          <option value="">{{ __('messages.role') }}</option>
          <option value="owner">Owner</option>
          <option value="admin">Admin</option>
          <option value="manager">Manager</option>
          <option value="editor">Editor</option>
          <option value="warehouse">Warehouse</option>
          <option value="support">Support</option>
          <option value="customer">Customer</option>
        </select>
        <select id="filter-verify" class="input-primary bg-white">
          <option value="">{{ __('messages.email_status') }}</option>
          <option value="verified">{{ __('messages.verified') }}</option>
          <option value="unverified">{{ __('messages.unverified') }}</option>
        </select>
      </div>
      <div class="flex gap-3 flex-1 lg:max-w-md">
        <div class="relative flex-1">
          <input type="text" id="search-input" placeholder="{{ __('messages.search_user') }}"
                 class="input-primary pl-10">
          <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
        <button type="button" id="open-user-create" class="whitespace-nowrap inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
          <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
          {{ __('messages.add_user') }}
        </button>
      </div>
    </div>
  </div>

  <div>
    <div id="users-cards" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach($users as $u)
        @php
          $isVerified = !is_null($u->email_verified_at);
          $badgeClass = $isVerified ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';
          $badgeText = $isVerified ? __('messages.verified') : __('messages.unverified');
          $role = strtolower($u->role ?? 'customer');
          $initials = collect(explode(' ', trim($u->name ?? '')))->map(fn($p) => mb_substr($p,0,1))->join('');
        @endphp
        <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden user-card relative"
             data-name="{{ strtolower($u->name ?? '') }}"
             data-email="{{ strtolower($u->email ?? '') }}"
             data-role="{{ $role }}"
             data-verified="{{ $isVerified ? 'verified' : 'unverified' }}">
          <div class="p-6">
            <div class="flex items-center justify-between mb-4">
              <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                  <div class="h-16 w-16 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-xl ring-4 ring-indigo-50">
                    {{ $initials }}
                  </div>
                </div>
                <div class="flex-1 min-w-0">
                  <div class="text-lg font-bold text-gray-900 truncate">{{ $u->name }}</div>
                  <div class="text-sm text-gray-500 truncate">{{ '@'.\Illuminate\Support\Str::slug($u->name ?? '', '-') }}</div>
                </div>
              </div>
              <div class="flex items-center gap-2">
                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeClass }}">
                  {{ $badgeText }}
                </span>
              </div>
            </div>
            <div class="space-y-3 mb-5">
              <div class="flex items-center text-sm">
                <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span class="text-gray-600 truncate">{{ $u->email }}</span>
              </div>
              <div class="flex items-center justify-between">
                <div class="flex items-center text-sm">
                  <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                  </svg>
                  <span class="text-gray-600">{{ ucfirst($u->role ?? 'customer') }}</span>
                </div>
              </div>
            </div>
            <div class="flex space-x-2 pt-4 border-t border-gray-100">
              <button type="button" class="action-button flex-1 inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50" title="{{ __('messages.edit') }}" data-action="edit" data-id="{{ $u->id }}" data-name="{{ $u->name }}" data-email="{{ $u->email }}" data-role="{{ $u->role ?? 'customer' }}">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                {{ __('messages.edit') }}
              </button>
              <form method="post" action="{{ route('admin.users.destroy', $u->id) }}" onsubmit="return confirm('{{ __('messages.confirm_delete_user') }}')">
                @csrf
                @method('DELETE')
                <button type="submit" class="action-button flex-1 inline-flex items-center justify-center px-4 py-2 border border-red-300 rounded-lg text-sm font-medium text-red-700 bg-white hover:bg-red-50" title="{{ __('messages.delete') }}">
                  <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                  </svg>
                  {{ __('messages.delete') }}
                </button>
              </form>
              <button type="button" class="action-button inline-flex items-center justify-center px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 action-menu-toggle" title="More" data-user-id="{{ $u->id }}">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                </svg>
              </button>
            </div>
          </div>
          <div class="action-menu hidden absolute right-4 top-4 w-56 bg-white rounded-lg shadow-lg border border-gray-200 z-10" data-menu-id="{{ $u->id }}">
            <button type="button" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600" data-action="edit" data-id="{{ $u->id }}" data-name="{{ $u->name }}" data-email="{{ $u->email }}" data-role="{{ $u->role ?? 'customer' }}">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
              </svg>
              {{ __('messages.edit') }}
            </button>
            <form method="post" action="{{ route('admin.users.destroy', $u->id) }}">
              @csrf
              @method('DELETE')
              <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-700 hover:bg-red-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                {{ __('messages.delete') }}
              </button>
            </form>
            <form method="post" action="{{ route('admin.users.verify_email', $u->id) }}">
              @csrf
              <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ __('messages.verify_email') }}
              </button>
            </form>
            @php $actorRole = strtolower(auth()->user()->role ?? 'customer'); @endphp
            @if(in_array($actorRole, ['admin','manager']))
            <form method="post" action="{{ route('admin.users.toggle_role', $u->id) }}">
              @csrf
              <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                {{ __('messages.change_role') }}
              </button>
            </form>
            @endif
          </div>
        </div>
      @endforeach
    </div>
    <div class="mt-6">@include('components.pagination', ['paginator' => $users])</div>
  </div>
</div>
<script>
  (function(){
    const search = document.getElementById('search-input');
    const roleSel = document.getElementById('filter-role');
    const verSel = document.getElementById('filter-verify');
    function normalize(s){ return (s||'').toLowerCase(); }
    function applyFilters(){
      const q = normalize(search.value);
      const role = roleSel.value || '';
      const ver = verSel.value || '';
      document.querySelectorAll('.user-card').forEach(function(card){
        const name = card.getAttribute('data-name')||'';
        const email = card.getAttribute('data-email')||'';
        const cRole = card.getAttribute('data-role')||'';
        const cVer = card.getAttribute('data-verified')||'';
        const matchText = !q || name.indexOf(q)>-1 || email.indexOf(q)>-1;
        const matchRole = !role || cRole===role;
        const matchVer = !ver || cVer===ver;
        card.style.display = (matchText && matchRole && matchVer) ? '' : 'none';
      });
    }
    if(search){ search.addEventListener('input', applyFilters); }
    if(roleSel){ roleSel.addEventListener('change', applyFilters); }
    if(verSel){ verSel.addEventListener('change', applyFilters); }
    document.querySelectorAll('.action-menu-toggle').forEach(function(btn){
      btn.addEventListener('click', function(e){
        const id = e.currentTarget.getAttribute('data-user-id');
        const menu = document.querySelector('.action-menu[data-menu-id="'+id+'"]');
        if(!menu) return;
        menu.classList.toggle('hidden');
      });
    });
    document.addEventListener('click', function(e){
      if(e.target.closest('.action-menu-btn') || e.target.closest('.action-menu')) return;
      document.querySelectorAll('.action-menu').forEach(function(m){ m.classList.add('hidden'); });
    });
    const editModal = (function(){
      let el;
      function ensure(){
        if(el) return el;
        el = document.createElement('div');
        el.innerHTML = `
          <div class="fixed inset-0 bg-gradient-to-br from-slate-900/60 to-slate-800/60 backdrop-blur-sm hidden items-center justify-center z-50" id="user-edit-modal">
            <div class="modal-content bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
              <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-5" style="background: linear-gradient(to right, rgb(37, 99, 235), rgb(37, 99, 235));">
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                      <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white" style="font-size: 20px;">{{ __('messages.edit_user') }}</h3>
                  </div>
                  <button type="button" id="user-edit-close" class="text-white/80 hover:text-white hover:bg-white/10 rounded-lg p-2 transition-all duration-200" style="font-size: 16px;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                  </button>
                </div>
              </div>
              <form id="user-edit-form" method="post" class="p-6">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="PUT">
                <div class="space-y-5">
                  <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2" style="font-size: 14px;">
                      <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        {{ __('messages.user_name') }}
                      </span>
                    </label>
                    <input id="ue-name" name="name" type="text" class="input-primary" placeholder="{{ __('messages.user_name') }}" required style="font-size: 16px;">
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2" style="font-size: 14px;">
                      <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        {{ __('messages.email_address') }}
                      </span>
                    </label>
                    <input id="ue-email" name="email" type="email" class="input-primary" placeholder="example@email.com" required>
                  </div>
                  @php $actorRole = strtolower(auth()->user()->role ?? 'customer'); @endphp
                  @if(in_array($actorRole, ['admin','manager']))
                  <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2" style="font-size: 14px;">
                      <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        {{ __('messages.role') }}
                      </span>
                    </label>
                    <div class="relative">
                      <select id="ue-role" name="role" class="input-primary pl-12 cursor-pointer" style="font-size: 16px;">
                        <option value="owner">Owner</option>
                        <option value="admin">Admin</option>
                        <option value="manager">Manager</option>
                        <option value="editor">Editor</option>
                        <option value="warehouse">Warehouse</option>
                        <option value="support">Support</option>
                        <option value="customer">Customer</option>
                      </select>
                      <div id="role-icon" class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                      </div>
                    </div>
                  </div>
                  @endif
                </div>
                <div class="flex gap-3 mt-8">
                  <button type="button" id="user-edit-cancel" class="flex-1 px-4 py-3 border-2 border-slate-200 text-slate-700 font-semibold rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all duration-200" style="font-size: 16px; background-color: rgb(248, 250, 252);">{{ __('messages.cancel') }}</button>
                  <button type="submit" class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 transition-all duration-200" style="font-size: 16px; background: linear-gradient(to right, rgb(37, 99, 235), rgb(37, 99, 235));">{{ __('messages.save_changes') }}</button>
                </div>
              </form>
            </div>
          </div>`;
        document.body.appendChild(el);
        return el;
      }
      function open(payload){
        ensure();
        const wrap = document.getElementById('user-edit-modal');
        const form = document.getElementById('user-edit-form');
        document.getElementById('ue-name').value = payload.name||'';
        document.getElementById('ue-email').value = payload.email||'';
        var roleEl = document.getElementById('ue-role');
        if (roleEl) { roleEl.value = (payload.role||'customer').toLowerCase(); }
        form.setAttribute('action', payload.action);
        wrap.classList.remove('hidden'); wrap.classList.add('flex');
        document.getElementById('user-edit-close').onclick = close;
        document.getElementById('user-edit-cancel').onclick = close;
      }
      function close(){
        const wrap = document.getElementById('user-edit-modal');
        if(wrap){ wrap.classList.add('hidden'); wrap.classList.remove('flex'); }
      }
      return { open, close };
    })();
    const createModal = (function(){
      let el;
      function ensure(){
        if(el) return el;
        el = document.createElement('div');
        el.innerHTML = `
          <div class="fixed inset-0 bg-gradient-to-br from-slate-900/60 to-slate-800/60 backdrop-blur-sm hidden items-center justify-center z-50" id="user-create-modal">
            <div class="modal-content bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
              <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-5" style="background: linear-gradient(to right, rgb(37, 99, 235), rgb(37, 99, 235));">
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                      <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white" style="font-size: 20px;">Thêm Người Dùng</h3>
                  </div>
                  <button type="button" id="user-create-close" class="text-white/80 hover:text-white hover:bg-white/10 rounded-lg p-2 transition-all duration-200" style="font-size: 16px;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                  </button>
                </div>
              </div>
              <form id="user-create-form" method="post" class="p-6" action="{{ route('admin.users.store') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="space-y-5">
                  <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2" style="font-size: 14px;">
                      <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Tên người dùng
                      </span>
                    </label>
                    <input id="uc-name" name="name" type="text" class="input-focus w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 transition-all duration-200" placeholder="Nhập tên người dùng" required style="font-size: 16px; border-color: rgb(226, 232, 240);">
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2" style="font-size: 14px;">
                      <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Địa chỉ email
                      </span>
                    </label>
                    <input id="uc-email" name="email" type="email" class="input-focus w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 transition-all duration-200" placeholder="example@email.com" required style="font-size: 16px; border-color: rgb(226, 232, 240);">
                  </div>
                  @php $actorRole = strtolower(auth()->user()->role ?? 'customer'); @endphp
                  @if(in_array($actorRole, ['admin','manager']))
                  <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2" style="font-size: 14px;">
                      <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        Vai trò
                      </span>
                    </label>
                    <div class="relative">
                      <select id="uc-role" name="role" class="input-focus w-full px-4 py-3 pl-12 border-2 border-slate-200 rounded-xl text-slate-900 bg-white transition-all duration-200 cursor-pointer" style="font-size: 16px; border-color: rgb(226, 232, 240);">
                        <option value="customer">Customer</option>
                        <option value="support">Support</option>
                        <option value="warehouse">Warehouse</option>
                        <option value="editor">Editor</option>
                        <option value="manager">Manager</option>
                        <option value="admin">Admin</option>
                        <option value="owner">Owner</option>
                      </select>
                      <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                      </div>
                    </div>
                  </div>
                  @endif
                </div>
                <div class="flex gap-3 mt-8">
                  <button type="button" id="user-create-cancel" class="flex-1 px-4 py-3 border-2 border-slate-200 text-slate-700 font-semibold rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all duration-200" style="font-size: 16px; background-color: rgb(248, 250, 252);">Hủy bỏ</button>
                  <button type="submit" class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 transition-all duration-200" style="font-size: 16px; background: linear-gradient(to right, rgb(37, 99, 235), rgb(37, 99, 235));">Tạo người dùng</button>
                </div>
              </form>
            </div>
          </div>`;
        document.body.appendChild(el);
        return el;
      }
      function open(){
        ensure();
        const wrap = document.getElementById('user-create-modal');
        wrap.classList.remove('hidden'); wrap.classList.add('flex');
        document.getElementById('user-create-close').onclick = close;
        document.getElementById('user-create-cancel').onclick = close;
      }
      function close(){
        const wrap = document.getElementById('user-create-modal');
        if(wrap){ wrap.classList.add('hidden'); wrap.classList.remove('flex'); }
      }
      return { open, close };
    })();
    document.querySelectorAll('[data-action="edit"]').forEach(function(btn){
      btn.addEventListener('click', function(e){
        const b = e.currentTarget;
        const id = b.getAttribute('data-id');
        const name = b.getAttribute('data-name');
        const email = b.getAttribute('data-email');
        const role = b.getAttribute('data-role');
        editModal.open({ name, email, role, action: '{{ url('/admin/users') }}'+'/'+id });
        document.querySelectorAll('.action-menu').forEach(function(m){ m.classList.add('hidden'); });
      });
    });
    var openCreateBtn = document.getElementById('open-user-create');
    if (openCreateBtn) {
      openCreateBtn.addEventListener('click', function(){
        createModal.open();
      });
    }
  })();
</script>
@endsection
