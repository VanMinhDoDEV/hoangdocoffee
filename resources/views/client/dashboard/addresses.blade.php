<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Địa chỉ nhận quà</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png') }}">
    <link rel="icon" type="image/png" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png') }}">
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
    :root { --soft-bg: #FDFBF9; --accent-pink: #E8D5D5; --text-main: #4A4444; }
    body { background-color: var(--soft-bg); font-family: 'Montserrat', sans-serif; color: var(--text-main); }
    .font-serif { font-family: 'Cormorant Garamond', serif; }
    .soft-card { background: #ffffff; border-radius: 50px 5px 50px 5px; border: 1px solid #F1EDE9; transition: all 0.5s ease; }
    .soft-card:hover { border-color: var(--accent-pink); box-shadow: 0 15px 30px rgba(232, 213, 213, 0.3); }
    </style>
</head>
<body class="p-6 lg:p-10">
    <div class="max-w-5xl mx-auto space-y-10">
        <header class="flex items-end justify-between">
            <div>
                <h1 class="font-serif text-4xl italic tracking-tight">Địa chỉ nhận quà</h1>
                <p class="text-sm text-gray-400 italic">Quản lý địa chỉ giao hàng của bạn.</p>
            </div>
            <a href="{{ route('client.dashboard') }}" class="text-[10px] uppercase tracking-widest border-b border-black">Về dashboard</a>
        </header>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="soft-card p-8 space-y-6">
                <h3 class="font-serif text-2xl italic">Danh sách địa chỉ</h3>
                <div class="space-y-4">
                    @forelse($addresses as $addr)
                        <div class="border border-gray-100 rounded-[20px] p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="font-serif italic text-lg">{{ $addr->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $addr->phone }}</div>
                                    <div class="text-sm">{{ $addr->address_line }}</div>
                                    <div class="text-sm">{{ $addr->ward }} {{ $addr->city }}</div>
                                </div>
                                <div class="flex items-center gap-3">
                                    @if($addr->is_default)
                                        <span class="text-[10px] px-3 py-1 bg-[#F9F5F2] rounded-full text-[#B08D8D] italic">Mặc định</span>
                                    @else
                                        <form method="post" action="{{ route('client.addresses.default', ['id' => $addr->id]) }}">
                                            @csrf
                                            <button class="text-[10px] uppercase tracking-widest border-b border-gray-300 hover:border-black">Đặt mặc định</button>
                                        </form>
                                    @endif
                                    <form method="post" action="{{ route('client.addresses.delete', ['id' => $addr->id]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-[10px] uppercase tracking-widest border-b border-gray-300 hover:border-black">Xóa</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">Chưa có địa chỉ nào.</p>
                    @endforelse
                </div>
            </div>
            <div class="space-y-8">
                <div class="soft-card p-8 space-y-6">
                    <h3 class="font-serif text-2xl italic">Ảnh đại diện</h3>
                    <div class="flex items-center gap-6">
                        <div class="w-20 h-20 rounded-full overflow-hidden border border-gray-200">
                            @php
                                $avatarUrl = $user->avatar ? asset($user->avatar) : 'https://ui-avatars.com/api/?background=random&name='.urlencode($user->name);
                            @endphp
                            <img src="{{ $avatarUrl }}" class="w-full h-full object-cover" alt="Avatar">
                        </div>
                        <form method="post" action="{{ route('client.profile.avatar') }}" enctype="multipart/form-data" class="flex-1 space-y-3">
                            @csrf
                            <input type="file" name="avatar" accept="image/*" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[#F9F5F2] file:text-[#B08D8D] hover:file:bg-[#F4F1EE]" required>
                            <button class="text-[10px] uppercase tracking-widest border-b border-black hover:text-gray-600">Cập nhật ảnh</button>
                        </form>
                    </div>
                </div>

                <div class="soft-card p-8 space-y-6">
                    <h3 class="font-serif text-2xl italic">Thêm địa chỉ</h3>
                <form method="post" action="{{ route('client.addresses.store') }}" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] uppercase tracking-widest">Họ tên</label>
                            <input type="text" name="name" class="w-full mt-1 border rounded-[12px] px-3 py-2 text-sm" required>
                        </div>
                        <div>
                            <label class="text-[10px] uppercase tracking-widest">Số điện thoại</label>
                            <input type="text" name="phone" class="w-full mt-1 border rounded-[12px] px-3 py-2 text-sm">
                        </div>
                        <div class="col-span-2">
                            <label class="text-[10px] uppercase tracking-widest">Địa chỉ</label>
                            <input type="text" name="address_line" class="w-full mt-1 border rounded-[12px] px-3 py-2 text-sm" required>
                        </div>
                        <div>
                            <label class="text-[10px] uppercase tracking-widest">Tỉnh/Thành phố</label>
                            <select name="city" id="city_select" class="w-full mt-1 border rounded-[12px] px-3 py-2 text-sm" disabled>
                                <option value="">Chọn tỉnh/thành</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] uppercase tracking-widest">Phường/Xã</label>
                            <select name="ward" id="ward_select" class="w-full mt-1 border rounded-[12px] px-3 py-2 text-sm" disabled>
                                <option value="">Chọn phường/xã</option>
                            </select>
                        </div>
                        <div class="col-span-2 flex items-center gap-2">
                            <input type="checkbox" name="is_default" id="is_default" class="rounded">
                            <label for="is_default" class="text-[10px] uppercase tracking-widest">Đặt làm mặc định</label>
                        </div>
                    </div>
                    <button class="w-full py-3 bg-[#1a1a1a] text-white text-[10px] uppercase tracking-widest rounded-[20px] hover:bg-opacity-80 transition">Lưu</button>
                </form>
            </div>
        </div>
    </div>
    <script>
    let provincesData = [];
    async function loadProvinces() {
        const citySel = document.getElementById('city_select');
        citySel.innerHTML = '<option value="">Đang tải...</option>';
        try {
            const res = await fetch('{{ route('api.provinces') }}');
            const json = await res.json();
            const data = json.data || json;
            provincesData = Array.isArray(data) ? data : [];
            citySel.innerHTML = '<option value="">Chọn tỉnh/thành</option>';
            provincesData.forEach(function(p){
                const opt = document.createElement('option');
                opt.value = p.name;
                opt.textContent = p.name;
                opt.dataset.code = p.code;
                citySel.appendChild(opt);
            });
            citySel.disabled = false;
        } catch (e) {
            citySel.innerHTML = '<option value="">Không tải được dữ liệu</option>';
            citySel.disabled = true;
        }
    }
    async function loadCommunesByCode(code) {
        const wardSel = document.getElementById('ward_select');
        wardSel.innerHTML = '<option value="">Đang tải...</option>';
        wardSel.disabled = true;
        try {
            const url = '{{ route('api.communes', ['code' => 'CODE']) }}'.replace('CODE', code);
            const res = await fetch(url);
            const json = await res.json();
            const data = json.data || json;
            wardSel.innerHTML = '<option value="">Chọn phường/xã</option>';
            (Array.isArray(data) ? data : []).forEach(function(c){
                const opt = document.createElement('option');
                opt.value = c.name;
                opt.textContent = c.name;
                wardSel.appendChild(opt);
            });
            wardSel.disabled = false;
        } catch (e) {
            wardSel.innerHTML = '<option value="">Không tải được dữ liệu</option>';
        }
    }
    document.addEventListener('DOMContentLoaded', function(){
        loadProvinces();
        document.getElementById('city_select').addEventListener('change', function(){
            const selected = this.options[this.selectedIndex];
            const code = selected ? (selected.dataset.code || '') : '';
            if (code) {
                loadCommunesByCode(code);
            } else {
                const wardSel = document.getElementById('ward_select');
                wardSel.innerHTML = '<option value="">Chọn phường/xã</option>';
                wardSel.disabled = true;
            }
        });
    });
    </script>
    @include('client.components.alert-modal')
</body>
</html>
