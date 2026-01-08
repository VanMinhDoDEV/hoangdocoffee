<!doctype html>
<html lang="vi">
  <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ isset($product) ? 'Chỉnh sửa sản phẩm' : 'Thêm sản phẩm mới' }}</title>
  <link rel="shortcut icon" type="image/x-icon" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png') }}">
  <link rel="icon" type="image/png" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png') }}">
  @vite(['resources/css/app.css','resources/js/app.js'])

  <style>
    .nav-link{transition:all .2s ease}
    .nav-link:hover{background-color:rgba(255,255,255,.1)}
    .nav-link.active{background-color:rgba(255,255,255,.15);border-left:3px solid #3498db}
    .btn{transition:all .2s ease}
    .btn:hover{filter:brightness(1.05)}
    .help{font-size:.75rem}
  </style>
 </head>
 <body class="bg-gray-50">
 <div class="w-full min-h-screen flex">
  @include('admin.partials.sidebar')
  <main class="flex-1 overflow-auto md:ml-64 transition-all duration-300">
    @include('admin.partials.topbar', ['title' => (isset($product) ? 'Chỉnh sửa sản phẩm' : 'Thêm sản phẩm mới')])
    <div class="p-8">
      <script>
        window.toggleSubmenu = function(id){
          const submenu=document.getElementById(id+'-submenu');
          const arrow=document.getElementById(id+'-arrow');
          if(!submenu||!arrow)return;
          submenu.classList.toggle('hidden');
          arrow.classList.toggle('rotate-180');
        };
      </script>
      @if(session('status'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700">{{ session('status') }}</div>
      @endif
      <form id="productForm" method="post" action="{{ isset($product) ? route('admin.products.update', $product->id) : route('admin.products.store') }}" class="space-y-6" enctype="application/x-www-form-urlencoded">
        @csrf
        @if(isset($product))
          @method('PUT')
        @endif
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
              <h2 class="text-lg font-semibold text-gray-900 mb-4">Thông tin sản phẩm</h2>
              <div class="space-y-4">
                <div>
                  <label for="productTitle" class="block text-sm font-medium text-gray-700 mb-1">Tên sản phẩm</label>
                  <input id="productTitle" name="name" type="text" placeholder="Áo thun Cotton" class="input-primary" value="{{ old('name', $product->name ?? '') }}" required>
                  @error('name')<p class="help text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                    <input id="slug" name="slug" type="text" placeholder="ao-thun-cotton" class="input-primary" value="{{ old('slug', $product->slug ?? '') }}">
                    @error('slug')<p class="help text-red-600 mt-1">{{ $message }}</p>@enderror
                  </div>
                  <div>
                    <label for="product_sku" class="block text-sm font-medium text-gray-700 mb-1">SKU sản phẩm chính</label>
                    <input id="product_sku" name="product_sku" type="text" placeholder="VD: PRO-0001" class="input-primary" value="{{ old('product_sku', $product->product_sku ?? '') }}">
                    @error('product_sku')<p class="help text-red-600 mt-1">{{ $message }}</p>@enderror
                  </div>
                </div>
                <div>
                  <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label>
                  <textarea id="description" name="description" rows="4" class="input-primary resize-none">{{ old('description', $product->description ?? '') }}</textarea>
                  @error('description')<p class="help text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                  <label for="video_url" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.video_url') }}</label>
                  <input id="video_url" name="video_url" type="url" placeholder="https://www.youtube.com/watch?v=..." class="input-primary" value="{{ old('video_url', $product->video_url ?? '') }}">
                  @error('video_url')<p class="help text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                  <label for="articleInput" class="block text-sm font-medium text-gray-700 mb-1">Bài viết</label>
                  <textarea id="articleInput" name="article" rows="8" class="input-primary" data-upload-url="{{ route('admin.products.upload_image') }}">{{ old('article', $product->article ?? '') }}</textarea>
                  @error('article')<p class="help text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
              </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
              <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Hình ảnh sản phẩm</h2>
                <button type="button" id="addUrlBtn" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">Thêm từ URL</button>
              </div>
              <div class="space-y-4">
                <div id="urlInputsContainer" class="space-y-2"></div>
                <div class="upload-zone rounded-lg p-8 text-center">
                  <div class="space-y-2">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                    <div class="text-sm text-gray-600"><span class="font-medium">Kéo thả ảnh vào đây</span></div>
                    <p class="text-xs text-gray-500">hoặc</p>
                    <label class="inline-block">
                      <span class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 cursor-pointer">Chọn file</span>
                      <input type="file" id="fileInput" class="hidden" accept="image/*" multiple>
                    </label>
                  </div>
                </div>
                <div id="imagesPreview" class="grid grid-cols-2 sm:grid-cols-4 gap-4"></div>
                <input type="hidden" id="primaryImageUrlInput" name="primary_image_url">
                @error('primary_image_url')<p class="help text-red-600 mt-1">{{ $message }}</p>@enderror
                <input type="hidden" name="variants" id="variantsJson">
              </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
              <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Biến thể (size, màu)</h2>
                <button type="button" id="addVariantBtn" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">Thêm biến thể</button>
              </div>
              <div id="variantsContainer" class="space-y-4"></div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
              <h2 class="text-lg font-semibold text-gray-900 mb-4">Kho hàng • Vận chuyển • Thanh toán</h2>
              <div class="flex border-b border-gray-200 mb-4">
                <button type="button" class="inventory-tab px-4 py-2 text-sm font-medium text-blue-600 border-b-2 border-blue-600 focus:outline-none" data-tab="inventory">Kho hàng</button>
                <button type="button" class="inventory-tab px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 focus:outline-none" data-tab="shipping">Vận chuyển</button>
                <button type="button" class="inventory-tab px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 focus:outline-none" data-tab="payment">Thanh toán</button>
              </div>
              <div id="inventory-tab-content">
                <div class="tab-content" data-tab="inventory">
                  <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                      <div>
                        <label for="addStock" class="block text-sm font-medium text-gray-700 mb-1">Số lượng tồn kho hiện tại</label>
                        <input id="addStock" name="addStock" type="number" min="0" class="input-primary" placeholder="Ví dụ: 50" value="{{ old('addStock', $simpleProductStock ?? 0) }}">
                        <p class="text-xs text-gray-500 mt-1">Áp dụng cho sản phẩm chính (không có biến thể). Nếu có biến thể, hãy chỉnh trong phần Biến thể.</p>
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tình trạng tồn kho</label>
                        <label class="flex items-center cursor-pointer">
                          <input type="checkbox" name="in_stock" value="1" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" {{ old('in_stock', ($product->in_stock ?? true)) ? 'checked' : '' }}>
                          <span class="ml-2 text-sm text-gray-700">Còn hàng</span>
                        </label>
                      </div>
                    </div>
                    <div class="pt-4 border-t border-gray-200 space-y-3">
                      <div class="flex justify-between items-center"><span class="text-sm text-gray-600">Số biến thể trong kho:</span> <span id="variantCountSummary" class="text-sm font-semibold text-gray-900">0</span></div>
                      <div class="flex justify-between items-center"><span class="text-sm text-gray-600">Tổng tồn kho biến thể:</span> <span id="variantStockSum" class="text-sm font-semibold text-gray-900">0</span></div>
                      <div class="flex justify-between items-center"><span class="text-sm text-gray-600">Quản lý theo biến thể (size, màu):</span> <span class="text-sm font-medium text-gray-700">Khuyến nghị</span></div>
                    </div>
                  </div>
                </div>
                <div class="tab-content hidden" data-tab="shipping">
                  <div class="space-y-4">
                    <input type="hidden" name="shipping_mode" id="shipping_mode" value="company">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                      <button type="button" class="shipping-toggle flex flex-col gap-2 text-left p-4 border rounded-lg hover:bg-gray-50 focus:outline-none ring-1 ring-transparent" data-mode="seller">
                        <div class="flex items-center justify-between">
                          <span class="text-sm font-medium text-gray-800">Hàng được người bán vận chuyển</span>
                          <span class="check-indicator w-5 h-5 rounded border flex items-center justify-center text-xs">OK</span>
                        </div>
                        <p class="text-xs text-gray-600">Bạn sẽ chịu trách nhiệm về việc nhận hàng.</p>
                        <p class="text-xs text-gray-500">Bất kỳ hư hỏng hoặc chậm trễ nào trong quá trình vận chuyển có thể khiến bạn phải trả phí thiệt hại.</p>
                      </button>
                      <button type="button" class="shipping-toggle flex flex-col gap-2 text-left p-4 border rounded-lg hover:bg-gray-50 focus:outline-none ring-1 ring-blue-400 bg-blue-50" data-mode="company">
                        <div class="flex items-center justify-between">
                          <span class="text-sm font-medium text-gray-800">Được thực hiện bởi [Tên công ty] • Khuyến khích</span>
                          <span class="check-indicator w-5 h-5 rounded border bg-blue-600 text-white flex items-center justify-center text-xs">OK</span>
                        </div>
                        <p class="text-xs text-gray-600">Sản phẩm của bạn, trách nhiệm của chúng tôi.</p>
                        <p class="text-xs text-gray-500">Chỉ với một khoản phí nhỏ, chúng tôi sẽ lo toàn bộ quá trình giao hàng cho bạn.</p>
                        <p class="text-xs text-gray-500">Xem điều khoản và điều kiện giao hàng của chúng tôi để biết thêm chi tiết.</p>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="tab-content hidden" data-tab="payment">
                  <div class="space-y-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phương thức thanh toán</label>
                    <p class="text-xs text-gray-500 mb-3">Chọn các phương thức thanh toán áp dụng cho sản phẩm này (chỉ hiển thị các phương thức đã bật trong Cài đặt hệ thống).</p>
                    
                    <input type="hidden" id="payment_methods" name="payment_methods" value="{{ old('payment_methods', $product->payment_method) }}">
                    <!-- Old payment_method input kept for compatibility -->
                    <input type="hidden" name="payment_method" value="">
                    
                    <div class="space-y-3" id="payment-toggles-container">
                       @php
                         $globalMethods = $paymentSettings['enabled_methods'] ?? [];
                         $currentMethods = [];
                         $rawMethod = old('payment_methods', $product->payment_method);
                         
                         if ($rawMethod === 'all' || is_null($rawMethod)) {
                             // If 'all' or null (legacy), enable all global methods
                             $currentMethods = $globalMethods; 
                         } else {
                             $currentMethods = array_map('trim', explode(',', $rawMethod));
                         }

                         $methodMap = [
                             'cod' => ['name' => 'Thanh toán khi nhận hàng (COD)', 'icon' => '💵', 'desc' => 'Thanh toán tiền mặt khi giao hàng'],
                             'bank_transfer' => ['name' => 'Chuyển khoản ngân hàng', 'icon' => '🏦', 'desc' => 'Chuyển khoản qua số tài khoản ngân hàng'],
                             'wallet' => ['name' => 'Ví điện tử', 'icon' => '👛', 'desc' => 'Thanh toán qua ví điện tử (Momo, ZaloPay...)'],
                             'credit' => ['name' => 'Thẻ tín dụng', 'icon' => '💳', 'desc' => 'Thanh toán qua thẻ Visa/Mastercard'],
                         ];
                       @endphp

                       @if(empty($globalMethods))
                         <div class="p-4 bg-red-50 text-red-600 rounded-lg text-sm flex items-center gap-2">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                             <span>Chưa có phương thức thanh toán nào được bật trong <a href="{{ route('admin.settings.payment') }}" class="underline font-medium hover:text-red-800">Cài đặt hệ thống</a>.</span>
                         </div>
                       @else
                         @foreach($globalMethods as $method)
                            @php 
                                $info = $methodMap[$method] ?? ['name' => ucfirst($method), 'icon' => '💰', 'desc' => ''];
                                $isChecked = in_array($method, $currentMethods);
                            @endphp
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg bg-white hover:bg-gray-50 transition-colors">
                               <div class="flex items-center gap-3">
                                   <span class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xl shadow-sm border border-blue-100">
                                       {{ $info['icon'] }}
                                   </span>
                                   <div>
                                       <div class="text-sm font-semibold text-gray-900">{{ $info['name'] }}</div>
                                       @if($info['desc'])
                                           <div class="text-xs text-gray-500 mt-0.5">{{ $info['desc'] }}</div>
                                       @endif
                                   </div>
                               </div>
                               <div class="relative">
                                   <input type="checkbox" id="payment-toggle-{{ $method }}" 
                                          class="sr-only payment-checkbox" 
                                          value="{{ $method }}"
                                          {{ $isChecked ? 'checked' : '' }}>
                                   <label for="payment-toggle-{{ $method }}" class="flex items-center cursor-pointer">
                                        <div class="toggle-label w-11 h-6 {{ $isChecked ? 'bg-blue-600' : 'bg-gray-300' }} rounded-full relative transition-colors duration-200 shadow-inner">
                                             <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform duration-200 transform {{ $isChecked ? 'translate-x-5' : 'translate-x-0' }} shadow"></div>
                                        </div>
                                   </label>
                               </div>
                            </div>
                         @endforeach
                       @endif
                    </div>
                  </div>
                  <script>
                    (function(){
                        const container = document.getElementById('payment-toggles-container');
                        if(!container) return;
                        const hidden = document.getElementById('payment_methods');
                        const checkboxes = container.querySelectorAll('.payment-checkbox');
                        
                        function update(){
                            const vals = Array.from(checkboxes).filter(c=>c.checked).map(c=>c.value);
                            hidden.value = vals.join(',');
                        }
                        
                        checkboxes.forEach(cb => {
                            cb.addEventListener('change', function(){
                                const label = this.nextElementSibling.querySelector('.toggle-label');
                                const dot = label.querySelector('.dot');
                                if(this.checked){
                                    label.classList.remove('bg-gray-300');
                                    label.classList.add('bg-blue-600');
                                    dot.classList.add('translate-x-5');
                                    dot.classList.remove('translate-x-0');
                                } else {
                                    label.classList.add('bg-gray-300');
                                    label.classList.remove('bg-blue-600');
                                    dot.classList.remove('translate-x-5');
                                    dot.classList.add('translate-x-0');
                                }
                                update();
                            });
                        });
                        update();
                    })();
                  </script>
                </div>
                
              </div>
            </div>
          </div>
          <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
              <h2 class="text-lg font-semibold text-gray-900 mb-4">Trạng thái</h2>
              <div class="space-y-4">
                <label class="flex items-center cursor-pointer">
                  <input name="is_active" type="checkbox" value="1" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" {{ old('is_active', ($product->is_active ?? true)) ? 'checked' : '' }}>
                  <span class="ml-2 text-sm text-gray-700">Kích hoạt sản phẩm</span>
                </label>
              </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
              <h2 class="text-lg font-semibold text-gray-900 mb-4">Pricing</h2>
              <div class="space-y-4">
                <div><label for="price" class="block text-sm font-medium text-gray-700 mb-1">Giá</label><input id="price" name="price" type="number" step="0.01" min="0" class="input-primary" value="{{ old('price', $product->price ?? '') }}"></div>
                <div><label for="discounted_price" class="block text-sm font-medium text-gray-700 mb-1">Giá khuyến mãi</label><input id="discounted_price" name="discounted_price" type="number" step="0.01" min="0" class="input-primary" value="{{ old('discounted_price', $product->discounted_price ?? '') }}"></div>
                <div class="space-y-2">
                  <label class="flex items-center cursor-pointer"><input type="checkbox" name="tax" value="1" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" {{ old('tax', ($product->tax ?? false)) ? 'checked' : '' }}> <span class="ml-2 text-sm text-gray-700">Tính thuế</span></label>
                </div>
              </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
              <h2 class="text-lg font-semibold text-gray-900 mb-4">Tổ chức</h2>
              <div class="space-y-4">
                <div>
                  <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Danh mục</label>
                  <select id="category_id" name="category_id" class="input-primary">
                    <option value="">-- Chọn danh mục --</option>
                    @isset($allCategories)
                      @foreach($allCategories as $c)
                        <option value="{{ $c->id }}" {{ (string)old('category_id', (string)($product->category_id ?? '')) === (string)$c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                      @endforeach
                    @endisset
                  </select>
                </div>
                <div>
                  <label for="collection" class="block text-sm font-medium text-gray-700 mb-1">Bộ sưu tập</label>
                  <div class="relative">
                    <input id="collection" name="collection" type="text" class="input-primary" autocomplete="off" value="{{ old('collection', $product->collection ?? '') }}">
                    <div id="collectionSuggest" class="absolute left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg hidden max-h-48 overflow-auto z-50"></div>
                  </div>
                </div>
                <div>
                  <label for="material" class="block text-sm font-medium text-gray-700 mb-1">Chất liệu</label>
                  <div class="relative">
                    <div id="materialTagsBox" class="border border-gray-300 rounded-md bg-white px-3 py-2 min-h-[42px] flex flex-wrap gap-2 items-center cursor-text">
                      <input id="materialInput" type="text" class="flex-1 outline-none" autocomplete="off" placeholder="Nhập chất liệu và nhấn Enter">
                    </div>
                    <div id="materialSuggest" class="absolute left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg hidden max-h-48 overflow-auto z-50"></div>
                    <input type="hidden" name="material" id="materialHidden" value="{{ old('material', $product->material ?? '') }}">
                  </div>
                  @error('material')<p class="help text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                  <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" {{ old('is_featured', ($product->is_featured ?? false)) ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-700">Sản phẩm nổi bật</span>
                  </label>
                </div>
                <div>
                  <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                  <select id="status" name="status" class="input-primary">
                    @php $statusVal = old('status', $product->status ?? 'active'); @endphp
                    <option value="active" {{ $statusVal==='active'?'selected':'' }}>Active</option>
                    <option value="draft" {{ $statusVal==='draft'?'selected':'' }}>Draft</option>
                    <option value="archived" {{ $statusVal==='archived'?'selected':'' }}>Archived</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="space-y-3">
              <button type="submit" class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">{{ isset($product) ? 'Cập nhật sản phẩm' : 'Tạo sản phẩm' }}</button>
              <a href="{{ route('admin.products') }}" class="w-full inline-flex items-center justify-center px-4 py-3 bg-white text-gray-700 border border-gray-300 rounded-lg font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">Hủy</a>
            </div>
          </div>
        </div>
      </form>
    </div>
   </main>
  </div>
  <script>
    let variantOptions = {};
    window.attributesData = [];
    const variantsContainer = document.getElementById('variantsContainer');
    const variantsJson = document.getElementById('variantsJson');
    var variants = [];

    (async function loadAttributes(){
      try{
        const resp = await fetch('{{ route('admin.products.attributes_json') }}',{credentials:'same-origin',headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}});
        const data = await resp.json();
        const attrs = Array.isArray(data.attributes)?data.attributes:[];
        window.attributesData = attrs.map(a=>({id:a.id,code:a.code,label:a.label,type:a.type,values:(a.values||[])}));
        variantOptions = {};
        window.attributesData.forEach(a=>{
          variantOptions[a.code] = (a.values||[]).map(v=>({value:v.value,slug:v.slug}));
        });
        renderVariants();
      }catch(_){}
    })();

    function toBackendVariantsPayload(){
      return variants.filter(v=>!v.is_default).map(v=>{
        const payload={sku:v.sku||null,stock:Number(v.stock||0),price:Number(v.price||0),sale_price:Number(v.sale_price||0),is_active:true,values:[],images:((v.images||[]).map(x=>typeof x==='string'?x:(x&&x.url?x.url:null)).filter(Boolean))};
        payload.values=(Array.isArray(v.values)?v.values.map(p=>({option_code:(p.option_code||p.option||null),value:p.value})):[]);
        return payload;
      });
    }
    function addVariant(){
      const id='variant_'+Date.now()+'_'+Math.random().toString(36).slice(2,9);
      variants.push({id,option:null,values:[],sku:'',stock:0,price:0,sale_price:0,is_active:true,images:[]});
      renderVariants();
    }
    function renderVariantImagesPreview(variantId, previewEl){
      previewEl.innerHTML='';
      const i=variants.findIndex(v=>v.id===variantId);
      if(i===-1)return;
      (variants[i].images||[]).forEach((image,ix)=>{
        const im=typeof image==='string'?{id:'u_'+ix,url:image,name:'Image '+(ix+1),size:'Unknown'}:image;
        const urlStr = String(im.url || '');
        const safeUrl = (urlStr.startsWith('http') || urlStr.startsWith('/storage/')) ? urlStr : ('/storage/' + urlStr.replace(/^storage\//,''));
        const card=document.createElement('div');
        card.className='relative group rounded-lg overflow-hidden border border-gray-200 bg-gray-50';
        card.innerHTML=`<div class="relative rounded-lg overflow-hidden border border-gray-200" style="width:96px;height:96px"><img src="${safeUrl}" alt="Variant image" class="w-full h-full object-cover"><button type="button" class="absolute top-1 right-1 p-1 bg-red-600 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-700" data-action="remove" data-index="${ix}"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button></div><div class="p-1 bg-white" style="max-width:96px"><p class="text-[11px] text-gray-600 truncate">${im.name}</p><p class="text-[10px] text-gray-400">${im.size}</p></div>`;
        previewEl.appendChild(card);
      });
      previewEl.querySelectorAll('button[data-action="remove"]').forEach(btn=>{
        btn.addEventListener('click',()=>{
          const idx=parseInt(btn.getAttribute('data-index')||'-1',10);
          const i=variants.findIndex(v=>v.id===variantId);
          if(i===-1)return;
          const arr=Array.isArray(variants[i].images)?variants[i].images:[];
          if(idx>=0 && idx<arr.length){arr.splice(idx,1);}
          variants[i].images=arr;
          if(variantsJson){variantsJson.value=JSON.stringify(toBackendVariantsPayload());}
          renderVariantImagesPreview(variantId, previewEl);
          renderVariants();
        });
      });
    }
    function removeVariant(id){
      variants = (variants||[]).filter(v=>String(v.id)!==String(id));
      if(variantsJson){variantsJson.value=JSON.stringify(toBackendVariantsPayload());}
      renderVariants();
    }
    function copyVariant(id){
      const idx = variants.findIndex(v => v.id === id);
      if(idx === -1) return;
      const source = variants[idx];
      const newId = 'variant_' + Date.now() + '_' + Math.random().toString(36).slice(2, 9);
      
      const newVariant = JSON.parse(JSON.stringify(source));
      newVariant.id = newId;
      if(newVariant.images){
          newVariant.images = newVariant.images.map(img => {
               if(typeof img === 'object' && img !== null){
                   return {...img, id: 'vimg_' + Date.now() + '_' + Math.random().toString(36).slice(2,9)};
               }
               return img;
          });
      }
      variants.splice(idx + 1, 0, newVariant);
      if(variantsJson){variantsJson.value=JSON.stringify(toBackendVariantsPayload());}
      renderVariants();
    }
    function abbrValue(val){
      let s=String(val||'').trim();
      if(!s) return '';
      if(s.startsWith('#')){return s.slice(1).toUpperCase().slice(0,3);}
      s=s.toUpperCase().replace(/\s+/g,'');
      return s.slice(0,3);
    }
    function generateVariantSku(variant){
      const baseInput=document.getElementById('product_sku');
      const base = (baseInput && baseInput.value) ? String(baseInput.value).trim() : (String(variant.sku||'').split('-').slice(0,2).join('-')||String(variant.sku||''));
      const tokens=[];
      (window.attributesData||[]).forEach(a=>{
        const sel = Array.isArray(variant.values)?variant.values.find(p=>String(p.option_code).toLowerCase()===String(a.code).toLowerCase()):null;
        if(sel && sel.value){ const t=abbrValue(sel.value); if(t){ tokens.push(t); } }
      });
      if(tokens.length){ return base? (base+'-'+tokens.join('-')) : tokens.join('-'); }
      return base;
    }
    function renderVariantsNew(){
      variantsContainer.innerHTML='';
      variants.forEach(variant=>{
        if(variant.is_default) return;
        variant.collapsed = (typeof variant.collapsed === 'boolean') ? variant.collapsed : true;
        const row=document.createElement('div');
        row.className='variant-row border border-gray-200 rounded-lg overflow-hidden bg-white hover:bg-gray-50 transition';
        const summary=document.createElement('div');
        summary.className='variant-summary group border-b border-gray-100 cursor-pointer hover:bg-gray-50 px-4 py-3 flex items-center gap-3';
        const toggleBtn=document.createElement('button');
        toggleBtn.className='collapse-icon text-gray-500 hover:text-gray-700 p-1'+(variant.collapsed?' rotated':'');
        toggleBtn.innerHTML='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>';
        toggleBtn.addEventListener('click',e=>{
          e.stopPropagation();
          const willOpen = !!variant.collapsed;
          variants.forEach(v=>{v.collapsed=true;});
          if(willOpen){ variant.collapsed=false; }
          renderVariants();
        });
        const firstImg=(variant.images||[])[0];
        let previewHtml='';
        if(firstImg){
          const im=typeof firstImg==='string'?{url:firstImg}:firstImg;
          previewHtml=`<img src="${im.url}" class="w-10 h-10 rounded object-cover border border-gray-200" alt="">`;
        }else{
          previewHtml=`<div class="w-10 h-10 rounded bg-gray-100 border border-gray-200 flex items-center justify-center"><svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>`;
        }
        const previewWrap=document.createElement('div');
        previewWrap.innerHTML=previewHtml;
        const info=document.createElement('div');
        info.className='flex-1';
        const selPairs=Array.isArray(variant.values)?variant.values:[];
        let badges='';
        selPairs.forEach(p=>{
          const v=String(p.value||'').trim();
          let colorVal=null;
          const hex3=/^#?[0-9A-Fa-f]{3}$/;
          const hex6=/^#?[0-9A-Fa-f]{6}$/;
          if(hex6.test(v)){
            colorVal=v.startsWith('#')?v:'#'+v;
          }else if(hex3.test(v)){
            const raw=v.startsWith('#')?v.slice(1):v;
            const r=raw[0],g=raw[1],b=raw[2];
            colorVal='#'+r+r+g+g+b+b;
          }else{
            const probe=document.createElement('div');
            try{probe.style.backgroundColor=v;}catch(_){}
            if(probe.style.backgroundColor){colorVal=v;}
          }
          const badge=colorVal?`<div class="w-8 h-5 rounded-md border-2" style="background-color:${colorVal};border-color:#e5e7eb"></div>`:`<span class="px-2 py-0.5 bg-gray-100 text-gray-700 text-xs font-medium rounded">${String(p.value||'')}</span>`;
          badges+=badge;
        });
        if(!badges){badges='<span class="text-xs text-gray-400">—</span>';}
        info.innerHTML=`<div class="flex items-center gap-2"><span class="font-medium text-gray-900">${variant.sku||''}</span>${badges}</div><div class="flex items-center gap-3 mt-1 text-xs text-gray-500"><span>💰 ${Number(variant.price||0)} VNĐ</span><span>📦 ${Number(variant.stock||0)} sản phẩm</span><span>🖼️ ${(variant.images||[]).length} ảnh</span></div>`;
        
        const actionsDiv = document.createElement('div');
        actionsDiv.className = 'flex items-center gap-1';

        const copyBtn=document.createElement('button');
        copyBtn.className='p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors opacity-0 group-hover:opacity-100 transition-opacity';
        copyBtn.innerHTML='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>';
        copyBtn.title = 'Copy biến thể';
        copyBtn.addEventListener('click',e=>{e.stopPropagation();copyVariant(variant.id);});
        
        const del=document.createElement('button');
        del.className='delete-btn p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors opacity-0 group-hover:opacity-100 transition-opacity';
        del.innerHTML='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>';
        del.addEventListener('click',e=>{e.stopPropagation();removeVariant(variant.id);});
        
        actionsDiv.appendChild(copyBtn);
        actionsDiv.appendChild(del);

        summary.appendChild(toggleBtn);
        summary.appendChild(previewWrap);
        summary.appendChild(info);
        summary.appendChild(actionsDiv);
        summary.addEventListener('click',()=>{
          const willOpen = !!variant.collapsed;
          variants.forEach(v=>{v.collapsed=true;});
          if(willOpen){ variant.collapsed=false; }
          renderVariants();
        });
        row.appendChild(summary);
        const details=document.createElement('div');
        details.className='variant-details p-4';
        if(variant.collapsed){details.style.display='none';}
        const body=document.createElement('div');
        body.className='flex items-start gap-4';
        const gallery=document.createElement('div');
        gallery.className='w-full md:w-1/3';

        // Header with Label and Add URL Button
        const header = document.createElement('div');
        header.className = 'flex items-center justify-between mb-2';
        
        const label=document.createElement('label');
        label.className='block text-sm font-medium text-gray-700';
        label.textContent='Hình ảnh';
        header.appendChild(label);

        const addUrlBtn = document.createElement('button');
        addUrlBtn.type = 'button';
        addUrlBtn.className = 'px-3 py-1 bg-white border border-gray-300 text-gray-700 rounded-lg text-xs font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors flex items-center gap-1';
        addUrlBtn.innerHTML = '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg> Thêm từ URL';
        header.appendChild(addUrlBtn);
        gallery.appendChild(header);

        // URL Inputs Container
        const urlInputsContainer = document.createElement('div');
        urlInputsContainer.className = 'mb-2 space-y-2';
        gallery.appendChild(urlInputsContainer);

        // Upload Area
        const uploadArea=document.createElement('label');
        uploadArea.className='image-upload-area w-full h-24 border-2 border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center cursor-pointer transition-all duration-200 hover:border-blue-500 hover:bg-blue-50';
        const file=document.createElement('input');
        file.type='file';
        file.accept='image/*';
        try{file.multiple=true;}catch(_){}
        file.className='hidden variant-file-input';
        file.setAttribute('data-variant-id',variant.id);
        uploadArea.appendChild(file);
        uploadArea.innerHTML+='<svg class="w-8 h-8 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 48 48"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg><span class="text-xs text-gray-500">Kéo thả hoặc click để chọn ảnh</span>';
        gallery.appendChild(uploadArea);

        // Preview Grid
        const previewEl=document.createElement('div');
        previewEl.className='variant-images-preview grid grid-cols-3 gap-2 mt-3';
        previewEl.setAttribute('data-variant-id',variant.id);
        gallery.appendChild(previewEl);

        // Logic for Add URL Button
        addUrlBtn.addEventListener('click', () => {
             const w=document.createElement('div');
             w.className='flex gap-2';
             w.innerHTML=`<input type="url" placeholder="https://example.com/image.jpg" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent url-input text-sm" /><button type="button" class="px-3 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors remove-url-btn"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>`;
             const removeBtn=w.querySelector('.remove-url-btn');
             removeBtn.addEventListener('click',()=>{w.remove();});
             const urlInput=w.querySelector('.url-input');
             urlInput.addEventListener('change',async e=>{
                const url=e.target.value.trim();
                if(url){
                   try {
                     const resp = await fetch('{{ route('admin.products.upload_image') }}', {
                       method: 'POST',
                       headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': '{{ csrf_token() }}'
                       },
                       body: JSON.stringify({url})
                     });
                     const data = await resp.json();
                     if(data && data.url){
                        const i = variants.findIndex(v => v.id === variant.id);
                        if(i !== -1){
                            variants[i].images = variants[i].images || [];
                            const imgId='vurl_'+Date.now()+'_'+Math.random().toString(36).slice(2,9);
                            const basename = (data.url.split('/').pop()||'Image');
                            variants[i].images.push({
                                id:imgId,
                                url:data.url,
                                name:(data.name||basename),
                                size: (typeof formatFileSize === 'function' ? formatFileSize(data.size) : (data.size||''))
                            });
                            if(variantsJson) variantsJson.value = JSON.stringify(toBackendVariantsPayload());
                            renderVariantImagesPreview(String(variant.id), previewEl);
                            w.remove();
                            renderVariants(); // Re-render to update summary count
                        }
                     }
                   } catch(err){
                       // alert('Lỗi tải ảnh từ URL');
                   }
                }
             });
             urlInputsContainer.appendChild(w);
        });

        // Logic for Upload Area
        uploadArea.addEventListener('click',(e)=>{ try{ e.preventDefault(); e.stopPropagation(); file.click(); }catch(_){ }});
        file.addEventListener('change',async e=>{
          const files=e.target.files||[];
          for(const f of files){
            if(!f) continue;
            const form=new FormData();
            form.append('file',f);
            try{
              const resp=await fetch('{{ route('admin.products.upload_image') }}',{method:'POST',credentials:'same-origin',headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:form});
              const data=await resp.json();
              if(data && data.url){
                const i=variants.findIndex(v=>v.id===variant.id);
                if(i!==-1){
                  variants[i].images=variants[i].images||[];
                  const imgId='vfile_'+Date.now()+'_'+Math.random().toString(36).slice(2,9);
                  variants[i].images.push({
                      id:imgId,
                      url:data.url,
                      name:(data.name||f.name),
                      size:(typeof formatFileSize === 'function' ? formatFileSize(data.size||f.size) : (data.size||f.size||''))
                  });
                  if(variantsJson){variantsJson.value=JSON.stringify(toBackendVariantsPayload());}
                  renderVariantImagesPreview(String(variant.id), previewEl);
                  renderVariants();
                }
              }
            }catch(_){}
          }
        });
        body.appendChild(gallery);
        const right=document.createElement('div');
        right.className='w-full md:w-2/3';
        const inputsGrid=document.createElement('div');
        inputsGrid.className='grid grid-cols-2 gap-4';
        const mkInput=(id,labelTxt,type,key,val)=>{
          const wrap=document.createElement('div');
          const lb=document.createElement('label');
          lb.className='block text-sm font-medium text-gray-700 mb-1';
          lb.setAttribute('for',id);
          lb.textContent=labelTxt;
          const inp=document.createElement('input');
          inp.type=type;inp.id=id;inp.value=val||'';
          inp.className='input-primary text-sm';
          inp.setAttribute('data-key',key);
          inp.setAttribute('data-variant-id',variant.id);
          wrap.appendChild(lb);wrap.appendChild(inp);
          inp.addEventListener('input',e=>{
            const k=e.target.getAttribute('data-key');
            const vid=e.target.getAttribute('data-variant-id');
            const i=variants.findIndex(v=>v.id===vid);
            if(i!==-1){
              let v=e.target.value;
              if(k==='stock'||k==='price'||k==='sale_price'){v=Number(v||0);}
              variants[i][k]=v;
              if(variantsJson){variantsJson.value=JSON.stringify(toBackendVariantsPayload());}
              renderVariants();
            }
          });
          return wrap;
        };
        inputsGrid.appendChild(mkInput('sku-'+variant.id,'Mã SKU','text','sku',variant.sku||''));
        inputsGrid.appendChild(mkInput('price-'+variant.id,'Giá bán (VNĐ)','number','price',variant.price||0));
        inputsGrid.appendChild(mkInput('sale-'+variant.id,'Giá khuyến mại','number','sale_price',variant.sale_price||0));
        inputsGrid.appendChild(mkInput('stock-'+variant.id,'Tồn kho','number','stock',variant.stock||0));
        right.appendChild(inputsGrid);
        const attrsWrap=document.createElement('div');
        const attrsLb=document.createElement('label');
        attrsLb.className='block text-sm font-medium text-gray-700 mb-1';
        attrsLb.textContent='Thuộc tính';
        attrsWrap.appendChild(attrsLb);
        const attrsGrid=document.createElement('div');
        attrsGrid.className='space-y-4';
        (window.attributesData||[]).forEach(attr=>{
          const group=document.createElement('div');
          const lb=document.createElement('div');
          lb.className='text-sm font-medium text-gray-700 mb-1';
          lb.textContent=attr.label||attr.code;
          const grid=document.createElement('div');
          grid.className='grid grid-cols-2 md:grid-cols-4 gap-2';
          const allVals=(variantOptions[attr.code]||[]);
          const selectedForAttr=(Array.isArray(variant.values)?variant.values.find(p=>String(p.option_code||p.option||'').toLowerCase()===String(attr.code).toLowerCase()):null);
          const isColorGroup = String((attr && attr.type) ? attr.type : '').toLowerCase() === 'color';
          allVals.forEach(vl=>{
            const valStr=vl.value||vl;
            const btn=document.createElement('button');
            btn.type='button';
            btn.className=isColorGroup?'value-option-btn p-1 border border-gray-300 rounded-md':'value-option-btn flex items-center gap-2 px-3 py-1 border border-gray-300 rounded-md size-badge transition-all hover:bg-blue-500 hover:text-white';
            btn.setAttribute('data-variant-id',variant.id);
            btn.setAttribute('data-attr-code',attr.code);
            btn.setAttribute('data-value',valStr);
            const sw=document.createElement('span');
            sw.className='inline-block w-8 h-8 rounded-md border-2 transform transition hover:scale-110 hover:border-blue-500';
            const hexCol=(valStr||'').startsWith('#')?valStr:'#'+(valStr||'');
            sw.style.backgroundColor=/^#?[0-9A-Fa-f]{6}$/.test(valStr||'')?hexCol:'transparent';
            const text=document.createElement('span');text.className='text-sm';text.textContent=isColorGroup?'':String(valStr||vl.slug||'');
            if(isColorGroup || (valStr && /^#?[0-9A-Fa-f]{6}$/.test(valStr))) { btn.appendChild(sw); } btn.appendChild(text);
            const isSelected = selectedForAttr && String(selectedForAttr.value).toLowerCase()===String(valStr).toLowerCase();
            if(isSelected){btn.classList.add('ring-2','ring-blue-500','ring-offset-2');}
            btn.addEventListener('click',()=>{
              const i=variants.findIndex(v=>v.id===variant.id);
              if(i===-1)return;
              const arr=Array.isArray(variants[i].values)?variants[i].values:[];
              const existedIndex=arr.findIndex(p=>String(p.option_code||p.option||'').toLowerCase()===String(attr.code).toLowerCase());
              if(existedIndex!==-1){arr[existedIndex]={option_code:attr.code,value:valStr};}
              else{arr.push({option_code:attr.code,value:valStr});}
              variants[i].values=arr;
              renderVariants();
            });
            grid.appendChild(btn);
          });
          group.appendChild(lb);group.appendChild(grid);attrsGrid.appendChild(group);
        });
        attrsWrap.appendChild(attrsGrid);
        right.appendChild(attrsWrap);
        body.appendChild(right);
        details.appendChild(body);
        renderVariantImagesPreview(String(variant.id), previewEl);
        row.appendChild(details);
        variantsContainer.appendChild(row);
      });
      if(variantsJson){variantsJson.value=JSON.stringify(toBackendVariantsPayload());}
    }

    function toggleSubmenu(id){const submenu=document.getElementById(id+'-submenu');const arrow=document.getElementById(id+'-arrow');if(!submenu||!arrow)return;submenu.classList.toggle('hidden');arrow.classList.toggle('rotate-180');}
    (function(){})();
    let uploadedImages=[];const urlInputsContainer=document.getElementById('urlInputsContainer');const imagesPreview=document.getElementById('imagesPreview');const addUrlBtn=document.getElementById('addUrlBtn');const fileInput=document.getElementById('fileInput');const uploadZone=document.querySelector('.upload-zone');const primaryImageUrlInput=document.getElementById('primaryImageUrlInput');
    @isset($product)
      @php
        $imgList = ($product->images ?? collect());
        $primary = $imgList->firstWhere('is_primary', true);
        $primaryUrl = $primary ? $primary->url : (($imgList[0]->url ?? '') ?: '');
        $preImages = $imgList->map(function($im){
          return ['id' => 'existing_'.($im->id), 'url' => $im->url, 'type' => 'url', 'name' => basename($im->url), 'size' => ''];
        })->values();
      @endphp
      primaryImageUrlInput && (primaryImageUrlInput.value = @json($primaryUrl));
      uploadedImages = @json($preImages);
      renderImages(); syncHiddenInputs();
    @endisset
    if(addUrlBtn){addUrlBtn.addEventListener('click',()=>{const w=document.createElement('div');w.className='flex gap-2';w.innerHTML=`<input name="images_urls[]" type="url" placeholder="https://example.com/image.jpg" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent url-input" /><button type="button" class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors remove-url-btn"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>`;const removeBtn=w.querySelector('.remove-url-btn');removeBtn.addEventListener('click',()=>{w.remove();});const urlInput=w.querySelector('.url-input');urlInput.addEventListener('change',async e=>{const url=e.target.value.trim();if(url){if(await addImageFromUrl(url)){w.remove();}}});urlInputsContainer.appendChild(w);});}
    function formatFileSize(bytes){if(!bytes||bytes<=0)return 'Unknown';const k=1024;const sizes=['Bytes','KB','MB','GB'];const i=Math.floor(Math.log(bytes)/Math.log(k));return Math.round(bytes/Math.pow(k,i)*100)/100+' '+sizes[i];}
    async function addImageFromUrl(url){try{const resp=await fetch('{{ route('admin.products.upload_image') }}',{method:'POST',credentials:'same-origin',headers:{'Accept':'application/json','Content-Type':'application/json','X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({url})});const data=await resp.json();if(data&&data.url){const id='url_'+Date.now()+'_'+Math.random().toString(36).slice(2,11);const basename=(url.split('/').pop()||'Image');uploadedImages.push({id,url:data.url,type:'url',name:(data.name||basename),size:formatFileSize(data.size)});if(!primaryImageUrlInput.value){primaryImageUrlInput.value=data.url;}renderImages();syncHiddenInputs();return true;}}catch(e){alert('Tải ảnh từ URL thất bại');}return false;}
    async function uploadFile(file){const form=new FormData();form.append('file',file);try{const resp=await fetch('{{ route('admin.products.upload_image') }}',{method:'POST',credentials:'same-origin',headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:form});const data=await resp.json();if(data&&data.url){const id='file_'+Date.now()+'_'+Math.random().toString(36).slice(2,11);uploadedImages.push({id,url:data.url,type:'file',name:(data.name||file.name),size:formatFileSize(data.size||file.size)});if(!primaryImageUrlInput.value){primaryImageUrlInput.value=data.url;}renderImages();syncHiddenInputs();}}catch(e){alert('Upload ảnh thất bại');}}
    function renderImages(){imagesPreview.innerHTML='';uploadedImages.forEach((image,index)=>{const isPrimary=primaryImageUrlInput.value&&image.url===primaryImageUrlInput.value;const card=document.createElement('div');card.className='relative group rounded-lg overflow-hidden border border-gray-200 bg-gray-50';card.innerHTML=`<div class=\"aspect-square relative\"><img src=\"${image.url}\" alt=\"Product image\" class=\"w-full h-full object-cover\" onerror=\"this.src='';this.alt='Image failed to load';this.parentElement.innerHTML='<div class=\\\'flex items-center justify-center h-full bg-gray-200 text-gray-400\\\'>Failed to load</div>';\"><button type=\"button\" class=\"absolute top-2 right-2 p-1.5 bg-red-600 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-700\" data-action=\"remove\" data-id=\"${image.id}\"><svg class=\"w-4 h-4\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M6 18L18 6M6 6l12 12\" /></svg></button><button type=\"button\" class=\"absolute top-2 left-2 px-2 py-1 text-xs rounded ${isPrimary?'bg-yellow-400 text-gray-900':'bg-white text-gray-700 border'}\" data-action=\"primary\" data-id=\"${image.id}\">${isPrimary?'Primary':'Set primary'}</button></div><div class=\"p-2 bg-white\"><p class=\"text-xs text-gray-600 truncate\">${image.name||('Image '+(index+1))}</p><p class=\"text-[11px] text-gray-400\">${image.size||'Unknown'}</p></div>`;imagesPreview.appendChild(card);});imagesPreview.querySelectorAll('button[data-action=\"remove\"]').forEach(btn=>{btn.addEventListener('click',()=>{removeImage(btn.dataset.id);});});imagesPreview.querySelectorAll('button[data-action=\"primary\"]').forEach(btn=>{btn.addEventListener('click',()=>{const img=uploadedImages.find(i=>i.id===btn.dataset.id);if(img){primaryImageUrlInput.value=img.url;renderImages();syncHiddenInputs();}});});}
    function removeImage(id){uploadedImages=uploadedImages.filter(i=>i.id!==id);if(primaryImageUrlInput.value&&!uploadedImages.find(i=>i.url===primaryImageUrlInput.value)){primaryImageUrlInput.value=uploadedImages[0]?uploadedImages[0].url:'';}renderImages();syncHiddenInputs();}
    function syncHiddenInputs(){const form=document.getElementById('productForm');if(!form)return;Array.from(form.querySelectorAll('input[name="images_urls[]"]')).forEach(n=>n.remove());uploadedImages.forEach(img=>{if(img.url!==primaryImageUrlInput.value){const hidden=document.createElement('input');hidden.type='hidden';hidden.name='images_urls[]';hidden.value=img.url;form.appendChild(hidden);}})
    }
    ;['dragenter','dragover','dragleave','drop'].forEach(ev=>{if(uploadZone)uploadZone.addEventListener(ev,e=>{e.preventDefault();e.stopPropagation();});});['dragenter','dragover'].forEach(ev=>{if(uploadZone)uploadZone.addEventListener(ev,()=>{uploadZone.classList.add('dragover');});});['dragleave','drop'].forEach(ev=>{if(uploadZone)uploadZone.addEventListener(ev,()=>{uploadZone.classList.remove('dragover');});});if(uploadZone){uploadZone.addEventListener('drop',async e=>{const files=e.dataTransfer.files;for(const f of files){if(f&&f.type&&f.type.startsWith('image/')){await uploadFile(f);}}});}
    if(fileInput){fileInput.addEventListener('change',async e=>{const files=e.target.files||[];for(const f of files){if(f&&f.type&&f.type.startsWith('image/')){await uploadFile(f);}}});}
    (function initCollectionSuggest(){const input=document.getElementById('collection');const box=document.getElementById('collectionSuggest');if(!input||!box)return;let cache=[];let loaded=false;let hideTimer=null;async function ensure(){if(loaded)return;try{const resp=await fetch('{{ route('admin.products.collections_json') }}',{credentials:'same-origin',headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}});const data=await resp.json();cache=Array.isArray(data.collections)?data.collections:[];loaded=true;}catch(e){cache=[];loaded=true;}}function render(items){box.innerHTML='';if(!items||items.length===0){box.classList.add('hidden');return;}items.slice(0,50).forEach(v=>{const btn=document.createElement('button');btn.type='button';btn.className='w-full text-left px-3 py-2 hover:bg-gray-50';btn.textContent=v;btn.addEventListener('click',()=>{input.value=v;box.classList.add('hidden');});box.appendChild(btn);});box.classList.remove('hidden');}input.addEventListener('focus',async()=>{await ensure();const q=input.value.trim().toLowerCase();const items=cache.filter(v=>v.toLowerCase().includes(q));render(items.length?items:cache);});input.addEventListener('input',()=>{const q=input.value.trim().toLowerCase();const items=cache.filter(v=>v.toLowerCase().includes(q));render(items);});input.addEventListener('blur',()=>{hideTimer=setTimeout(()=>{box.classList.add('hidden');},200);});box.addEventListener('mousedown',e=>{e.preventDefault();});})();
    (function initMaterialSuggest(){const input=document.getElementById('materialInput');const box=document.getElementById('materialSuggest');const tagsBox=document.getElementById('materialTagsBox');const hidden=document.getElementById('materialHidden');if(!input||!box||!tagsBox||!hidden)return;let cache=[];let loaded=false;let hideTimer=null;let tags=[];if(hidden.value){tags=(hidden.value||'').split(',').map(s=>s.trim()).filter(Boolean);}function sync(){hidden.value=tags.join(', ');Array.from(document.querySelectorAll('input[name="materials[]"]')).forEach(n=>n.remove());const form=document.getElementById('productForm');if(form){tags.forEach(t=>{const h=document.createElement('input');h.type='hidden';h.name='materials[]';h.value=t;form.appendChild(h);});}}function renderTags(){tagsBox.querySelectorAll('.tag-item').forEach(n=>n.remove());tags.forEach(t=>{const el=document.createElement('span');el.className='tag-item inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-800 text-sm rounded-md';el.textContent=t;const rm=document.createElement('button');rm.type='button';rm.className='hover:bg-blue-200 rounded-full p-0.5';rm.innerHTML='<svg class=\"w-3 h-3\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M6 18L18 6M6 6l12 12\" /></svg>';rm.addEventListener('click',()=>{tags=tags.filter(x=>x!==t);renderTags();sync();});el.appendChild(rm);tagsBox.insertBefore(el,input);});sync();}renderTags();input.value='';function addTag(v){const s=(v||'').trim();if(!s)return;if(!tags.includes(s)){tags.push(s);renderTags();}input.value='';}async function ensure(){if(loaded)return;try{const resp=await fetch('{{ route('admin.products.materials_json') }}',{credentials:'same-origin',headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}});const data=await resp.json();cache=Array.isArray(data.materials)?data.materials:[];loaded=true;}catch(e){cache=[];loaded=true;}}function render(items){box.innerHTML='';if(!items||items.length===0){box.classList.add('hidden');return;}items.slice(0,50).forEach(v=>{const div=document.createElement('div');div.className='flex items-center justify-between px-3 py-2 hover:bg-gray-50 cursor-pointer group';const span=document.createElement('span');span.textContent=v;span.className='flex-1';span.onclick=()=>{addTag(v);box.classList.add('hidden');};const del=document.createElement('button');del.type='button';del.className='text-gray-400 hover:text-red-500 p-1 opacity-0 group-hover:opacity-100 transition-opacity';del.innerHTML='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>';del.title='Xóa chất liệu này khỏi hệ thống';del.onclick=async(e)=>{e.stopPropagation();if(!confirm('Bạn có chắc muốn xóa chất liệu "'+v+'" khỏi TẤT CẢ sản phẩm?'))return;try{const resp=await fetch('{{ route('admin.products.materials.delete') }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({name:v})});const d=await resp.json();if(d.status==='success'){cache=cache.filter(c=>c!==v);render(cache.filter(c=>c.toLowerCase().includes(input.value.trim().toLowerCase())));}else{alert(d.message||'Lỗi khi xóa');}}catch(err){alert('Lỗi kết nối');}};div.appendChild(span);div.appendChild(del);box.appendChild(div);});box.classList.remove('hidden');}input.addEventListener('keydown',e=>{if(e.key==='Enter'){e.preventDefault();addTag(input.value||'');}});input.addEventListener('focus',async()=>{await ensure();const q=input.value.trim().toLowerCase();const items=cache.filter(v=>v.toLowerCase().includes(q));render(items.length?items:cache);});input.addEventListener('input',()=>{const q=input.value.trim().toLowerCase();const items=cache.filter(v=>v.toLowerCase().includes(q));render(items);});input.addEventListener('blur',()=>{hideTimer=setTimeout(()=>{box.classList.add('hidden');},200);});box.addEventListener('mousedown',e=>{e.preventDefault();});})();
    const tabs=document.querySelectorAll('.inventory-tab');const contents=document.querySelectorAll('#inventory-tab-content .tab-content');tabs.forEach(btn=>{btn.addEventListener('click',()=>{tabs.forEach(b=>{b.classList.remove('text-blue-600','border-b-2','border-blue-600');b.classList.add('text-gray-600');});btn.classList.add('text-blue-600','border-b-2','border-blue-600');contents.forEach(c=>{if(c.getAttribute('data-tab')===btn.getAttribute('data-tab')){c.classList.remove('hidden');}else{c.classList.add('hidden');}});});});
    function updateInventorySummary(){try{const count=(variants||[]).length;const sum=(variants||[]).reduce((acc,v)=>acc+Number(v.stock||0),0);const c=document.getElementById('variantCountSummary');const s=document.getElementById('variantStockSum');if(c)c.textContent=String(count);if(s)s.textContent=String(sum);}catch(_){}}updateInventorySummary();
    (function initShippingToggle(){const modeInput=document.getElementById('shipping_mode');document.querySelectorAll('.shipping-toggle').forEach(btn=>{btn.addEventListener('click',()=>{document.querySelectorAll('.shipping-toggle').forEach(b=>{b.classList.remove('ring-1','ring-blue-400','bg-blue-50');const ci=b.querySelector('.check-indicator');if(ci){ci.classList.remove('bg-blue-600','text-white');ci.classList.add('bg-white','text-gray-500');}});btn.classList.add('ring-1','ring-blue-400','bg-blue-50');const ci=btn.querySelector('.check-indicator');if(ci){ci.classList.add('bg-blue-600','text-white');ci.classList.remove('bg-white','text-gray-500');}const mode=btn.getAttribute('data-mode')||'company';if(modeInput)modeInput.value=mode;});});})();
    (function initPaymentToggle(){
      const select=document.getElementById('payment_method');
      const hidden=document.getElementById('payment_methods');
      const active=new Set();
      function sync(){
        const arr=Array.from(active);
        hidden && (hidden.value=arr.join(','));
        if(select){
          if(arr.length===0){select.value='all';}
          else if(arr.length>1){select.value='all';}
          else{select.value=arr[0];}
        }
      }
      document.querySelectorAll('.payment-toggle').forEach(btn=>{
        btn.addEventListener('click',()=>{
          const m=btn.getAttribute('data-method');
          if(active.has(m)){
            active.delete(m);
            btn.classList.remove('ring-1','ring-blue-400','bg-blue-50');
          }else{
            active.add(m);
            btn.classList.add('ring-1','ring-blue-400','bg-blue-50');
          }
          sync();
        });
      });
      @php $pm = $product->payment_method ?? 'all'; @endphp
      const pm = @json($pm);
      if(pm==='all'){
        active.add('credit');active.add('cod');active.add('bank_transfer');
        document.querySelectorAll('.payment-toggle').forEach(b=>b.classList.add('ring-1','ring-blue-400','bg-blue-50'));
      }else{
        active.add(pm);
        document.querySelectorAll('.payment-toggle').forEach(b=>{
          if(b.getAttribute('data-method')===pm){
            b.classList.add('ring-1','ring-blue-400','bg-blue-50');
          }
        });
      }
      sync();
    })();
    
    function renderVariants(){
      const active=document.activeElement;
      const shouldPreserve=active && active.tagName==='INPUT' && active.hasAttribute('data-key') && active.hasAttribute('data-variant-id');
      let meta=null;
      if(shouldPreserve){
        meta={
          vid: active.getAttribute('data-variant-id'),
          key: active.getAttribute('data-key'),
          selStart: active.selectionStart,
          selEnd: active.selectionEnd,
          val: active.value
        };
      }
      renderVariantsNew();
      if(variantsJson){variantsJson.value=JSON.stringify(toBackendVariantsPayload());}
      updateInventorySummary();
      prefillAddStockIfNoVariants();
      if(meta){
        const selector='input[data-variant-id="'+meta.vid+'"][data-key="'+meta.key+'"]';
        const el=document.querySelector(selector);
        if(el){
          el.focus();
          try{
            el.value=meta.val;
            if(typeof meta.selStart==='number' && typeof meta.selEnd==='number'){
              el.setSelectionRange(meta.selStart, meta.selEnd);
            }
          }catch(_){}
        }
      }
    }
    @isset($product)
      try { variants = @json($prefillVariants ?? []).filter(v => !v.is_default); renderVariants(); } catch(e) {}
    @endisset
    function prefillAddStockIfNoVariants(){
      try{
        const addStockEl=document.getElementById('addStock');
        const hasVariants=(variants||[]).length>0;
        if(addStockEl){
          addStockEl.disabled = hasVariants;
          addStockEl.placeholder = hasVariants ? 'Không hỗ trợ khi có biến thể' : 'Ví dụ: 50';
          if(!hasVariants){
            addStockEl.value = @json(old('addStock', $simpleProductStock ?? 0));
          }
        }
      }catch(_){}
    }
    prefillAddStockIfNoVariants();
    if(addVariantBtn){addVariantBtn.addEventListener('click',addVariant);}document.addEventListener('click',e=>{if(!e.target.closest('.variant-input-wrapper')&&!e.target.closest('.variant-dropdown')){document.querySelectorAll('.variant-dropdown').forEach(dd=>dd.classList.add('hidden'));}});
    (function fixPaymentInitAndSubmit(){
      const select=document.getElementById('payment_method');
      const hidden=document.getElementById('payment_methods');
      const pm = @json(old('payment_methods', $product->payment_method ?? 'all'));
      const parts = (typeof pm==='string' ? pm.split(',').map(s=>s.trim()).filter(Boolean) : []);
      const methods = (pm==='all' || parts.length===0) ? ['credit','cod','bank_transfer'] : parts;
      methods.forEach(m=>{
        const btn=document.querySelector('.payment-toggle[data-method="'+m+'"]');
        if(btn){ btn.classList.add('ring-1','ring-blue-400','bg-blue-50'); }
      });
      if(hidden){
        if(methods.length===0 || methods.length>1){ hidden.value='all'; }
        else { hidden.value=methods[0]||'all'; }
      }
      if(select){
        if(methods.length===0 || methods.length>1){ select.value='all'; }
        else { select.value=methods[0]||'all'; }
      }
      const form=document.querySelector('form');
      if(form){
        form.addEventListener('submit',()=>{
          const variantsJson = document.getElementById('variantsJson');
          if(variantsJson){ variantsJson.value = JSON.stringify(toBackendVariantsPayload()); }
          if(hidden && select){
            const activeBtns = Array.from(document.querySelectorAll('.payment-toggle.ring-1.ring-blue-400.bg-blue-50'));
            const arr = activeBtns.map(b=>b.getAttribute('data-method')).filter(Boolean);
            if(arr.length===0 || arr.length>1){ hidden.value='all'; select.value='all'; }
            else { hidden.value=arr[0]||'all'; select.value=arr[0]||'all'; }
          }
        });
      }
    })();
    (function fixShippingInit(){
      const modeInput=document.getElementById('shipping_mode');
      const initialMode = @json(old('shipping_mode', $product->shipping_mode ?? 'company'));
      const btn = document.querySelector('.shipping-toggle[data-mode="'+(initialMode||'company')+'"]');
      if(btn){
        btn.classList.add('ring-1','ring-blue-400','bg-blue-50');
        const ci=btn.querySelector('.check-indicator');
        if(ci){ci.classList.add('bg-blue-600','text-white');ci.classList.remove('bg-white','text-gray-500');}
      }
      if(modeInput){ modeInput.value = initialMode || 'company'; }
    })();
  </script>
 </body>
 </html>
