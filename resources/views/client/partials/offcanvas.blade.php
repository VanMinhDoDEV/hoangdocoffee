<div class="offcanvas offcanvas-end w-100 border-0" id="offcanvas-search" style="background-color: var(--bg-body-color);">
    <button type="button" class="btn-close offcanvas-search-close-btn" data-bs-dismiss="offcanvas" aria-label="Close" style="position: absolute !important; top: 20px !important; right: 20px !important; left: auto !important; bottom: auto !important; z-index: 1051; transform: none !important; margin: 0 !important; filter: invert(1);"></button>
    <div class="offcanvas-body d-flex flex-column" id="search-body">
        <div class="search-header d-flex align-items-center justify-content-center flex-grow-1" id="search-header" style="transition: all 0.3s ease-in-out;">
            <div class="offcanvas-search-form position-relative">
                <form action="{{ route('products.search') }}" method="GET">
                    <input type="search" name="q" placeholder="Search our store" autocomplete="off" class="pe-5">
                    <i class="sli-magnifier position-absolute top-50 end-0 translate-middle-y me-3" style="pointer-events: none;"></i>
                </form>
            </div>
        </div>
        
        <div id="search-results-wrapper" class="container-fluid mt-4" style="display: none; height: calc(100vh - 100px); overflow-y: auto;">
            <div class="row" id="search-results-content">
                <!-- Ajax results here -->
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('#offcanvas-search input[name="q"]');
    const searchHeader = document.getElementById('search-header');
    const resultsWrapper = document.getElementById('search-results-wrapper');
    const resultsContent = document.getElementById('search-results-content');
    let searchTimeout = null;

    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        clearTimeout(searchTimeout);

        if (query.length > 0) {
            searchTimeout = setTimeout(() => {
                // UI Transformation
                searchHeader.classList.remove('align-items-center', 'justify-content-center', 'flex-grow-1');
                searchHeader.classList.add('w-100', 'justify-content-start', 'pt-4', 'px-4');
                
                fetch('{{ route("products.ajax_search") }}?q=' + encodeURIComponent(query))
                    .then(response => response.text())
                    .then(html => {
                        resultsContent.innerHTML = html;
                        resultsWrapper.style.display = 'block';
                    })
                    .catch(err => console.error(err));
            }, 500);
        } else {
            // Reset UI
            resultsWrapper.style.display = 'none';
            resultsContent.innerHTML = '';
            searchHeader.classList.add('align-items-center', 'justify-content-center', 'flex-grow-1');
            searchHeader.classList.remove('w-100', 'justify-content-start', 'pt-4', 'px-4');
        }
    });

    // Reset when closed
    const offcanvasSearch = document.getElementById('offcanvas-search');
    offcanvasSearch.addEventListener('hidden.bs.offcanvas', function () {
        searchInput.value = '';
        resultsWrapper.style.display = 'none';
        resultsContent.innerHTML = '';
        searchHeader.classList.add('align-items-center', 'justify-content-center', 'flex-grow-1');
        searchHeader.classList.remove('w-100', 'justify-content-start', 'pt-4', 'px-4');
    });
});
</script>

<div class="offcanvas offcanvas-end" id="offcanvas-cart">
    <div class="offcanvas-header">
        <h5>Shopping Cart</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    @php
        $cartItems = session('cart.items', []);
        $cartSubtotal = 0;
        foreach($cartItems as $item) {
            $cartSubtotal += ((float)($item['price'] ?? 0)) * ((int)($item['quantity'] ?? 0));
        }
        $available = [];
        $priceMap = [];
        foreach ($cartItems as $it) {
            $vid = (int)($it['variant_id'] ?? 0);
            if ($vid) {
                $available[$vid] = ($available[$vid] ?? 0) + (int)($it['quantity'] ?? 0);
                $priceMap[$vid] = (float)($it['price'] ?? 0);
            }
        }
        $discount = 0.0;
        $combos = \App\Models\Combo::with('lines')->where('is_active', true)->get();
        foreach ($combos as $combo) {
            $sets = PHP_INT_MAX;
            $lineVariants = [];
            foreach ($combo->lines as $ln) {
                $vid = (int)$ln->product_variant_id;
                $perSet = (int)$ln->quantity;
                $have = (int)($available[$vid] ?? 0);
                if ($perSet <= 0) { $sets = 0; break; }
                $setsForLine = intdiv($have, $perSet);
                $sets = min($sets, $setsForLine);
                $lineVariants[] = ['vid'=>$vid,'per'=>$perSet];
            }
            if ($sets > 0 && $sets !== PHP_INT_MAX) {
                $raw = 0.0;
                foreach ($lineVariants as $lv) {
                    $vid = $lv['vid']; $per = $lv['per'];
                    $raw += ($priceMap[$vid] ?? 0) * ($per * $sets);
                }
                $target = (float)($combo->price ?? 0) * $sets;
                if ($raw > $target) { $discount += ($raw - $target); }
                foreach ($lineVariants as $lv) {
                    $vid = $lv['vid']; $per = $lv['per'];
                    $available[$vid] = max(0, (int)$available[$vid] - ($per * $sets));
                }
            }
        }
        $cartTotal = max(0, $cartSubtotal - $discount);
    @endphp
    <div class="offcanvas-body d-flex flex-column">
        <div class="header-cart-body">
        <div class="header-cart-products">
          @include('client.partials.mini-cart-items', ['items' => $cartItems])
        </div>
      </div>
        <div class="header-cart-footer">
            <div id="header-cart-totals">
                @if($discount > 0)
                <h4 class="header-cart-total">Giảm giá Combo: <span>-{{ number_format($discount, 0, ',', '.') }}₫</span></h4>
                @endif
                <h4 class="header-cart-total">Total: <span>{{ number_format($cartTotal, 0, ',', '.') }}₫</span></h4>
            </div>
            <div class="header-cart-buttons">
                <a href="{{ route('checkout.from_cart') }}" class="btn btn-outline-dark btn-primary-hover">CHECKOUT</a>
                <a href="{{ route('cart.index') }}" class="btn btn-outline-dark btn-primary-hover">VIEW CART</a>
            </div>
        </div>
    </div>
</div>

@php
    $menus = [];
    try {
        $raw = \Illuminate\Support\Facades\Storage::disk('local')->exists('settings.json')
            ? \Illuminate\Support\Facades\Storage::disk('local')->get('settings.json')
            : null;
        $data = $raw ? json_decode($raw, true) : [];
        $menus = $data['menus'] ?? [];
    } catch (\Throwable $e) {}

    $primaryMenu = $menus[0] ?? [];
    $items = $primaryMenu['items'] ?? [];
@endphp

<div class="offcanvas offcanvas-end" id="offcanvas-header">
    <div class="offcanvas-header">
        <h5>Mobile Menu</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <nav class="mobile-menu">
            <ul>
                @if(count($items) > 0)
                    @foreach($items as $item)
                        <li>
                            <a href="{{ $item['url'] ?: '#' }}">{{ $item['name'] }}</a>
                            @if(!empty($item['children']))
                                <button class="mobile-sub-menu-toggle"></button>
                                <ul class="mobile-sub-menu">
                                    @foreach($item['children'] as $child)
                                        <li><a href="{{ $child['url'] ?: '#' }}">{{ $child['name'] }}</a></li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                @else
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="#">Shop</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Contact</a></li>
                @endif
            </ul>
        </nav>
    </div>
</div>
