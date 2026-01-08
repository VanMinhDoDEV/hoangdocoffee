<!doctype html>
<html lang="vi">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ __('messages.inventory') }}</title>
  <link rel="shortcut icon" type="image/x-icon" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png') }}">
  <link rel="icon" type="image/png" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png') }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @vite(['resources/css/app.css','resources/js/app.js'])
  <style>
    .tab-button{transition:all .3s;position:relative}
    .tab-button.active::after{content:'';position:absolute;bottom:-2px;left:0;right:0;height:3px;background:currentColor;border-radius:3px 3px 0 0}
    .card-hover{transition:transform .2s,box-shadow .2s}
    .card-hover:hover{transform:translateY(-2px);box-shadow:0 10px 20px rgba(0,0,0,.1)}
  </style>
 </head>
 <body class="bg-gray-50">
  <div class="w-full min-h-screen flex">
   @include('admin.partials.sidebar')
   <main class="flex-1 overflow-auto md:ml-64 transition-all duration-300">
    @include('admin.partials.topbar', ['title' => __('messages.inventory')])
    <div class="p-8">
     <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8" id="stats-container"></div>
     <div class="mb-6 bg-white rounded-xl p-2 shadow-sm border border-gray-100">
      <div class="flex gap-2" id="tabs-container"></div>
     </div>
     <div id="content-area"></div>
     <div id="modal-container"></div>
     <!-- Detail Modal Structure -->
     <div id="detail-modal-backdrop" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity duration-300" onclick="closeDetailModal()"></div>
     <div id="detail-modal" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-2xl shadow-2xl z-50 w-full max-w-2xl max-h-[90%] overflow-hidden transition-all duration-300 opacity-0 scale-95">
      <div class="flex items-center justify-between p-6 border-b border-slate-200">
       <h2 class="text-2xl font-bold text-slate-800" style="line-height: 1.2;">{{ __('messages.movement_details') }}</h2>
       <button onclick="closeDetailModal()" class="p-2 rounded-lg hover:bg-slate-100 transition-colors duration-200" aria-label="{{ __('messages.close') }}">
        <svg class="w-6 h-6 text-slate-400 hover:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
       </button>
      </div>
      <div class="p-6 overflow-y-auto max-h-[calc(90vh-140px)]">
       <div id="detail-modal-content"></div>
      </div>
     </div>
    </div>
   </main>
  </div>
  @php
    $warehousesData = $warehouses->map(function($w){
      return [
        'id' => $w->id,
        'name' => $w->name,
        'code' => $w->code,
        'address' => $w->address,
        'is_active' => (bool)$w->is_active,
      ];
    })->values()->all();
    $inventoriesAllData = $inventoriesAll->map(function($inv){
      return [
        'id' => $inv->id,
        'warehouse_id' => $inv->warehouse_id,
        'warehouse_name' => optional($inv->warehouse)->name,
        'product_variant_id' => $inv->product_variant_id,
        'product_name' => optional(optional($inv->variant)->product)->name,
        'product_sku' => optional($inv->variant)->sku,
        'on_hand' => (int)$inv->on_hand,
        'reserved' => (int)$inv->reserved,
        'incoming' => (int)$inv->incoming,
      ];
    })->values()->all();
    $inventoriesData = $inventories->map(function($inv) use ($warehouse){
      return [
        'id' => $inv->id,
        'warehouse_id' => $inv->warehouse_id,
        'warehouse_name' => $warehouse->name,
        'product_variant_id' => $inv->product_variant_id,
        'product_name' => optional(optional($inv->variant)->product)->name,
        'product_sku' => optional($inv->variant)->sku,
        'on_hand' => (int)$inv->on_hand,
        'reserved' => (int)$inv->reserved,
        'incoming' => (int)$inv->incoming,
      ];
    })->values()->all();
    $movementsData = $movements->map(function($m){
       $typeLabels = [
           'receipt' => __('messages.receipt'),
           'shipment' => __('messages.shipment'), 
           'adjustment' => __('messages.adjustment'),
           'reservation' => __('messages.reservation'),
           'release' => __('messages.release')
       ];
       $type = $typeLabels[$m->movement_type] ?? $m->movement_type;
       $product = optional($m->variant)->product;
       $reason = $m->notes;
       if (!$reason && $m->ref_type) {
           $reason = ucfirst($m->ref_type) . ($m->ref_id ? ' #' . $m->ref_id : '');
       }
       
       $qty = (int)$m->quantity;
       if ($m->movement_type === 'shipment') {
           $qty = -abs($qty);
       }

       return [
         'id' => $m->id,
         'type' => $type,
         'raw_type' => $m->movement_type,
         'quantity' => $qty,
         'time' => optional($m->created_at)->format('H:i:s d/m/Y'),
         'note' => $m->notes,
         'product' => [
             'name' => optional($product)->name ?? __('messages.deleted_product'),
             'sku' => optional($m->variant)->sku ?? 'N/A',
             'variant' => optional($m->variant)->sku, 
             'user' => 'System',
             'warehouse' => optional($m->warehouse)->name,
             'reason' => $reason,
             'change' => $qty,
         ]
       ];
    })->values()->all();
  @endphp
  <script>
    const warehouses = @json($warehousesData);
    const inventoriesAll = @json($inventoriesAllData);
    const pagedInventories = @json($inventoriesData);
    let movements = @json($movementsData);
    let hasMoreMovements = movements.length >= 9;
    let activeTab = 'warehouses';
    let selectedWarehouseId = {{ (int)$warehouse->id }};
    function setupTabs(){
      const container=document.getElementById('tabs-container');if(!container)return;
      const tabs=[
        {id:'warehouses',label:'{{ __('messages.warehouses') }}',icon:`<svg class="inline-block w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>`},
        {id:'inventory',label:'{{ __('messages.inventory') }}',icon:`<svg class="inline-block w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>`},
        {id:'movements',label:'{{ __('messages.stock_movements') }}',icon:`<svg class="inline-block w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>`}
      ];
      container.innerHTML=tabs.map(t=>{const isActive=activeTab===t.id;return `<button class="tab-button ${isActive?'active':''} flex-1 py-3 px-4 rounded-lg font-semibold ${isActive?'bg-blue-600 text-white':'bg-transparent text-slate-700'}" data-tab="${t.id}">${t.icon} <span class="ml-1">${t.label}</span></button>`}).join('');
      container.querySelectorAll('.tab-button').forEach(btn=>btn.addEventListener('click',e=>{activeTab=e.currentTarget.dataset.tab;setupTabs();renderContent();}));
    }
    function updateStats(){
      const c=document.getElementById('stats-container');if(!c)return;
      const activeWarehouses=warehouses.filter(w=>w.is_active).length;
      const totalOnHand=inventoriesAll.reduce((s,i)=>s+(i.on_hand||0),0);
      const totalReserved=inventoriesAll.reduce((s,i)=>s+(i.reserved||0),0);
      const stats=[
        {label:'{{ __('messages.active') }}',value:`${activeWarehouses}/${warehouses.length}`,icon:`<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>`,color:'#2563eb'},
        {label:'{{ __('messages.available') }}',value:Math.max(0,totalOnHand-totalReserved),icon:`<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg>`,color:'#10b981'},
        {label:'{{ __('messages.reserved') }}',value:totalReserved,icon:`<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>`,color:'#f59e0b'},
        {label:'{{ __('messages.stock_movements') }}',value:movements.length,icon:`<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>`,color:'#8b5cf6'}
      ];
      c.innerHTML=stats.map(st=>`<div class="card-hover bg-white rounded-xl shadow-sm border border-gray-100 p-6"><div class="flex items-center justify-between mb-2"><span class="text-slate-700">${st.icon}</span><span class="text-2xl font-bold" style="color:${st.color}">${st.value}</span></div><p class="text-xs font-medium text-gray-500 uppercase">${st.label}</p></div>`).join('');
    }
    function renderContent(){
      updateStats();
      if(activeTab==='warehouses'){renderWarehouses();}else if(activeTab==='inventory'){renderInventory();}else{renderMovements();}
    }
    function renderWarehouses(){
      const container=document.getElementById('content-area');if(!container)return;
      container.innerHTML=`<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6"><div class="flex items-center justify-between mb-6"><h3 class="text-lg font-semibold text-gray-800">{{ __('messages.warehouses') }}</h3><button id="add-warehouse-btn" class="px-3 py-2 rounded-lg bg-blue-600 text-white">{{ __('messages.add_warehouse') }}</button></div><div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">${warehouses.length===0?`<div class="col-span-full text-center py-12 text-gray-500">{{ __('messages.no_data') }}</div>`:warehouses.map(w=>createWarehouseCard(w)).join('')}</div></div>`;
      const addBtn=document.getElementById('add-warehouse-btn');if(addBtn){addBtn.addEventListener('click',()=>openWarehouseModal(null));}
      setupWarehouseActions();
    }
    function createWarehouseCard(w){
      const invCount=inventoriesAll.filter(i=>i.warehouse_id===w.id).length;
      const totalStock=inventoriesAll.filter(i=>i.warehouse_id===w.id).reduce((s,i)=>s+(i.on_hand||0),0);
      return `<div class="card-hover bg-slate-50 rounded-xl border-2 ${w.is_active?'border-blue-600':'border-slate-200'} p-4"><div class="flex justify-between items-start mb-2"><div><div class="flex items-center gap-2"><span class="inline-block w-2 h-2 rounded-full ${w.is_active?'bg-emerald-500':'bg-red-500'}"></span><h4 class="font-semibold text-slate-900">${w.name}</h4></div><p class="text-xs text-slate-500 mt-1">{{ __('messages.code') }}: ${w.code}</p>${w.address?`<p class="text-sm text-slate-700 mt-1">${w.address}</p>`:''}</div></div><div class="grid grid-cols-2 gap-3 bg-white rounded-lg p-3 mb-3"><div><p class="text-xs text-slate-500">{{ __('messages.products') }}</p><p class="text-lg font-bold text-blue-600">${invCount}</p></div><div><p class="text-xs text-slate-500">{{ __('messages.on_hand') }}</p><p class="text-lg font-bold text-slate-900">${totalStock}</p></div></div><div class="flex gap-2"><button class="flex-1 px-3 py-2 rounded-lg bg-blue-600 text-white" data-action="view-warehouse" data-id="${w.id}">{{ __('messages.view') }}</button><button class="px-3 py-2 rounded-lg bg-slate-600 text-white" data-action="edit-warehouse" data-id="${w.id}">{{ __('messages.edit') }}</button><button class="px-3 py-2 rounded-lg bg-red-600 text-white" data-action="delete-warehouse" data-id="${w.id}">{{ __('messages.delete') }}</button></div></div>`;
    }
    function setupWarehouseActions(){
      document.querySelectorAll('[data-action="view-warehouse"]').forEach(btn=>btn.addEventListener('click',e=>{selectedWarehouseId=parseInt(e.currentTarget.dataset.id,10);activeTab='inventory';renderContent();}));
      document.querySelectorAll('[data-action="edit-warehouse"]').forEach(btn=>btn.addEventListener('click',e=>{const id=parseInt(e.currentTarget.dataset.id,10);const w=warehouses.find(x=>x.id===id);if(w)openWarehouseModal(w);}));
      document.querySelectorAll('[data-action="delete-warehouse"]').forEach(btn=>btn.addEventListener('click',async e=>{
        const id=parseInt(e.currentTarget.dataset.id,10);
        const w=warehouses.find(x=>x.id===id);
        if(!w)return;
        if(w.code==='MAIN'){alert('{{ __('messages.system_note') }}: {{ __('messages.cannot_delete_main') }}');return;}
        if(!confirm(`{{ __('messages.confirm_delete') }} "${w.name}"?`))return;
        const token=document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const url='{{ url('/admin/warehouses') }}'+'/'+id;
        const res=await fetch(url,{method:'POST',headers:{'X-CSRF-TOKEN':token,'Accept':'application/json','Content-Type':'application/x-www-form-urlencoded'},body:new URLSearchParams({_method:'DELETE'})});
        if(res.ok){location.reload();}else{alert('{{ __('messages.system_note') }}: {{ __('messages.check_related_data') }}');}
      }));
    }
    function openWarehouseModal(warehouse){
      const modal=document.getElementById('modal-container');if(!modal)return;
      modal.innerHTML=`<div class="fixed inset-0 bg-black/50 flex items-center justify-center p-4"><div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden"><div class="p-6 border-b"><h3 class="text-lg font-semibold text-slate-900">${warehouse?'{{ __('messages.edit') }} {{ __('messages.warehouse') }}':'{{ __('messages.add_warehouse') }}'}</h3></div><form id="warehouse-form" class="p-6 space-y-4"><div><label class="block text-sm font-medium text-slate-700 mb-1">{{ __('messages.name') }} *</label><input type="text" name="name" value="${warehouse?warehouse.name:''}" required class="w-full px-3 py-2 border rounded-lg"></div><div><label class="block text-sm font-medium text-slate-700 mb-1">{{ __('messages.code') }} *</label><input type="text" name="code" value="${warehouse?warehouse.code:''}" required class="w-full px-3 py-2 border rounded-lg"></div><div><label class="block text-sm font-medium text-slate-700 mb-1">{{ __('messages.address') }}</label><textarea name="address" rows="3" class="w-full px-3 py-2 border rounded-lg">${warehouse&&warehouse.address?warehouse.address:''}</textarea></div><label class="inline-flex items-center gap-2 text-sm text-slate-700"><input type="checkbox" name="is_active" ${!warehouse||warehouse.is_active?'checked':''}> {{ __('messages.active') }}</label><div class="flex gap-3 pt-2"><button type="button" id="modal-cancel" class="flex-1 px-3 py-2 border rounded-lg">{{ __('messages.cancel') }}</button><button type="submit" class="flex-1 px-3 py-2 bg-blue-600 text-white rounded-lg">${warehouse?'{{ __('messages.update') }}':'{{ __('messages.create') }}'}</button></div></form></div></div>`;
      const cancelBtn=document.getElementById('modal-cancel');if(cancelBtn){cancelBtn.addEventListener('click',()=>{modal.innerHTML='';});}
      const form=document.getElementById('warehouse-form');if(form){form.addEventListener('submit',async e=>{e.preventDefault();const fd=new FormData(form);const payload={name:fd.get('name'),code:fd.get('code'),address:fd.get('address')||'',is_active:fd.get('is_active')==='on'?1:0};const token=document.querySelector('meta[name="csrf-token"]').getAttribute('content');let url='{{ route('admin.warehouses.store') }}';let method='POST';if(warehouse){url='{{ url('/admin/warehouses') }}'+'/'+warehouse.id;method='POST';payload._method='PUT';}const res=await fetch(url,{method,headers:{'X-CSRF-TOKEN':token,'Accept':'application/json'},body:new URLSearchParams(payload)});if(res.ok){location.reload();}});}
    }
    function renderInventory(){
      const container=document.getElementById('content-area');if(!container)return;
      const items=(selectedWarehouseId?inventoriesAll.filter(i=>i.warehouse_id===selectedWarehouseId):pagedInventories);
      const warehouseName=(warehouses.find(w=>w.id===selectedWarehouseId)||{}).name||'';
      container.innerHTML=`<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6"><div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6"><div><h3 class="text-lg font-semibold text-gray-800">{{ __('messages.inventory_management') }}</h3>${warehouseName?`<div class="mt-1"><button id="clear-filter-btn" class="px-2 py-1 border rounded text-sm">✕ ${warehouseName}</button></div>`:''}</div><div class="flex gap-2"><button id="receipt-btn" class="px-3 py-2 rounded-lg bg-green-600 text-white">{{ __('messages.receipt') }}</button><button id="adjust-btn" class="px-3 py-2 rounded-lg bg-slate-700 text-white">{{ __('messages.adjustment') }}</button></div></div><div class="overflow-x-auto"><table class="min-w-full text-sm"><thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">{{ __('messages.warehouse') }}</th><th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">{{ __('messages.product') }}</th><th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">{{ __('messages.sku') }}</th><th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">{{ __('messages.on_hand') }}</th><th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">{{ __('messages.reserved') }}</th><th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">{{ __('messages.available') }}</th><th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">{{ __('messages.incoming') }}</th><th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">{{ __('messages.actions') }}</th></tr></thead><tbody class="divide-y divide-gray-100">${items.length===0?`<tr><td colspan="8" class="px-6 py-10 text-center text-gray-500">{{ __('messages.no_inventory_data') }}</td></tr>`:items.map(inv=>createInventoryRow(inv)).join('')}</tbody></table></div></div>`;
      const clearBtn=document.getElementById('clear-filter-btn');if(clearBtn){clearBtn.addEventListener('click',()=>{selectedWarehouseId=0;renderContent();});}
      const receiptBtn=document.getElementById('receipt-btn');if(receiptBtn){receiptBtn.addEventListener('click',()=>openInventoryModal('receipt'));}
      const adjustBtn=document.getElementById('adjust-btn');if(adjustBtn){adjustBtn.addEventListener('click',()=>openInventoryModal('adjustment'));}
      setupInventoryActions();
    }
    function createInventoryRow(inv){
      const available=Math.max(0,(inv.on_hand||0)-(inv.reserved||0));
      return `<tr class="hover:bg-gray-50"><td class="px-6 py-3">${inv.warehouse_name||''}</td><td class="px-6 py-3"><div class="font-semibold text-slate-900">${inv.product_name||''}</div></td><td class="px-6 py-3 text-slate-500">${inv.product_sku||''}</td><td class="px-6 py-3 text-center font-bold text-blue-600">${inv.on_hand||0}</td><td class="px-6 py-3 text-center font-semibold text-amber-600">${inv.reserved||0}</td><td class="px-6 py-3 text-center font-semibold ${available>0?'text-emerald-600':'text-red-600'}">${available}</td><td class="px-6 py-3 text-center">${inv.incoming||0}</td><td class="px-6 py-3 text-center"><div class="flex gap-2 justify-center"><button class="px-2 py-1 rounded bg-slate-600 text-white text-xs" data-action="edit-inventory" data-id="${inv.product_variant_id}" data-warehouse="${inv.warehouse_id}">{{ __('messages.adjustment') }}</button></div></td></tr>`;
    }
    function setupInventoryActions(){
      document.querySelectorAll('[data-action="edit-inventory"]').forEach(btn=>btn.addEventListener('click',e=>{const variantId=parseInt(e.currentTarget.dataset.id,10);const wid=parseInt(e.currentTarget.dataset.warehouse,10);openQuickAdjustModal(wid,variantId);})); 
    }
    function openInventoryModal(type){
      const modal=document.getElementById('modal-container');if(!modal)return;
      const warehouseOptions=warehouses.map(w=>`<option value="${w.id}" ${selectedWarehouseId===w.id?'selected':''}>${w.name} (${w.code})</option>`).join('');
      modal.innerHTML=`<div class="fixed inset-0 bg-black/50 flex items-center justify-center p-4"><div class="bg-white rounded-2xl shadow-xl w-full max-w-xl overflow-hidden"><div class="p-6 border-b"><h3 class="text-lg font-semibold text-slate-900">${type==='receipt'?'{{ __('messages.receipt') }}':'{{ __('messages.inventory_adjustment') }}'}</h3></div><form id="inventory-form" class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4"><div class="md:col-span-2"><label class="block text-sm font-medium text-slate-700 mb-1">{{ __('messages.warehouse') }} *</label><select name="warehouse_id" class="w-full px-3 py-2 border rounded-lg">${warehouseOptions}</select></div>${
        type==='receipt'
        ? `<div class="md:col-span-2"><label class="block text-sm font-medium text-slate-700 mb-1">{{ __('messages.find_product') }} *</label><input type="text" id="variant-search" placeholder="{{ __('messages.enter_sku_name') }}" class="w-full px-3 py-2 border rounded-lg"><div id="variant-results" class="mt-2 border rounded-lg overflow-hidden hidden"></div><input type="hidden" name="variant_id"></div>`
        : `<div class="md:col-span-2"><label class="block text-sm font-medium text-slate-700 mb-1">{{ __('messages.variant_id') }} *</label><input type="number" name="variant_id" required class="w-full px-3 py-2 border rounded-lg"></div>`
      }<div><label class="block text-sm font-medium text-slate-700 mb-1">{{ __('messages.quantity') }} *</label><input type="number" name="quantity" required class="w-full px-3 py-2 border rounded-lg"></div><div class="md:col-span-2"><label class="block text-sm font-medium text-slate-700 mb-1">{{ __('messages.note') }}</label><textarea name="notes" rows="3" class="w-full px-3 py-2 border rounded-lg"></textarea></div><div class="md:col-span-2 flex gap-3 pt-2"><button type="button" id="modal-cancel" class="flex-1 px-3 py-2 border rounded-lg">{{ __('messages.cancel') }}</button><button type="submit" class="flex-1 px-3 py-2 bg-blue-600 text-white rounded-lg">{{ __('messages.confirm') }}</button></div></form></div></div>`;
      const cancelBtn=document.getElementById('modal-cancel');if(cancelBtn){cancelBtn.addEventListener('click',()=>{modal.innerHTML='';});}
      const form=document.getElementById('inventory-form');if(form){form.addEventListener('submit',async e=>{e.preventDefault();const fd=new FormData(form);const payload={warehouse_id:fd.get('warehouse_id'),variant_id:fd.get('variant_id'),quantity:fd.get('quantity'),notes:fd.get('notes')||''};const token=document.querySelector('meta[name="csrf-token"]').getAttribute('content');let url=type==='receipt'?'{{ route('admin.inventory.receipt') }}':'{{ route('admin.inventory.adjustment') }}';const res=await fetch(url,{method:'POST',headers:{'X-CSRF-TOKEN':token,'Accept':'application/json'},body:new URLSearchParams(payload)});if(res.ok){location.href='{{ route('admin.inventory') }}'+'?warehouse_id='+payload.warehouse_id;} });}
      if(type==='receipt'){
        const input=document.getElementById('variant-search');const box=document.getElementById('variant-results');const hidden=form.querySelector('input[name=\"variant_id\"]');
        if(input&&box&&hidden){
          let timer=null;
          input.addEventListener('input',()=>{
            const q=input.value.trim();
            if(timer)clearTimeout(timer);
            if(q.length<2){box.classList.add('hidden');box.innerHTML='';hidden.value='';return;}
            timer=setTimeout(async ()=>{
              const url='{{ route('admin.inventory.search_variant') }}'+'?q='+encodeURIComponent(q);
              const res=await fetch(url,{headers:{'Accept':'application/json'}});
              const data=await res.json();
              if(!Array.isArray(data)||data.length===0){box.classList.add('hidden');box.innerHTML='';hidden.value='';return;}
              box.classList.remove('hidden');
              box.innerHTML=data.map(v=>`<button type="button" class="w-full text-left px-3 py-2 hover:bg-slate-100" data-id="${v.id}"><div class="font-semibold text-slate-900">${v.product_name||'—'}</div><div class="text-xs text-slate-500">SKU: ${v.sku||'—'} • {{ __('messages.on_hand') }}: ${v.stock||0}</div></button>`).join('');
              box.querySelectorAll('button').forEach(btn=>btn.addEventListener('click',e=>{hidden.value=e.currentTarget.dataset.id;input.value=e.currentTarget.textContent.trim();box.classList.add('hidden');}));
            },250);
          });
        }
      }
    }
    function openQuickAdjustModal(wid,variantId){
      const modal=document.getElementById('modal-container');if(!modal)return;
      modal.innerHTML=`<div class="fixed inset-0 bg-black/50 flex items-center justify-center p-4"><div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden"><div class="p-6 border-b"><h3 class="text-lg font-semibold text-slate-900">{{ __('messages.quick_adjustment') }}</h3></div><form id="quick-form" class="p-6 space-y-4"><input type="hidden" name="warehouse_id" value="${wid}"><input type="hidden" name="variant_id" value="${variantId}"><div><label class="block text-sm font-medium text-slate-700 mb-1">± {{ __('messages.quantity') }} *</label><input type="number" name="quantity" required class="w-full px-3 py-2 border rounded-lg"></div><div class="flex gap-3 pt-2"><button type="button" id="modal-cancel" class="flex-1 px-3 py-2 border rounded-lg">{{ __('messages.cancel') }}</button><button type="submit" class="flex-1 px-3 py-2 bg-slate-700 text-white rounded-lg">{{ __('messages.update') }}</button></div></form></div></div>`;
      const cancelBtn=document.getElementById('modal-cancel');if(cancelBtn){cancelBtn.addEventListener('click',()=>{modal.innerHTML='';});}
      const form=document.getElementById('quick-form');if(form){form.addEventListener('submit',async e=>{e.preventDefault();const fd=new FormData(form);const token=document.querySelector('meta[name="csrf-token"]').getAttribute('content');const res=await fetch('{{ route('admin.inventory.adjustment') }}',{method:'POST',headers:{'X-CSRF-TOKEN':token,'Accept':'application/json'},body:new URLSearchParams({warehouse_id:fd.get('warehouse_id'),variant_id:fd.get('variant_id'),quantity:fd.get('quantity')})});if(res.ok){location.href='{{ route('admin.inventory') }}'+'?warehouse_id='+fd.get('warehouse_id');}});}
    }
    
    function getTypeStyles(type) {
      if (type === "receipt") {
        return { bgColor: "bg-emerald-50", textColor: "text-emerald-700", borderColor: "border-emerald-200" };
      } else if (type === "adjustment") {
        return { bgColor: "bg-blue-50", textColor: "text-blue-700", borderColor: "border-blue-200" };
      } else if (type === "shipment") {
        return { bgColor: "bg-red-50", textColor: "text-red-700", borderColor: "border-red-200" };
      }
      return { bgColor: "bg-gray-50", textColor: "text-gray-700", borderColor: "border-gray-200" };
    }

    function renderMovements(){
      const container=document.getElementById('content-area');if(!container)return;
      const list=movements.slice().sort((a,b)=>new Date(b.created_at)-new Date(a.created_at));
      
      container.innerHTML=`
        <div class="max-w-7xl mx-auto">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-slate-800">{{ __('messages.stock_movement_history') }}</h1>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                ${list.length===0 
                    ? `<div class="col-span-full text-center py-12 text-gray-500">{{ __('messages.no_movements_found') }}</div>` 
                    : list.map(m=>createMovementCard(m)).join('')}
            </div>
            ${hasMoreMovements ? `
            <div class="mt-10 text-center pb-8">
                <button id="load-more-btn" onclick="loadMoreMovements()" class="px-8 py-3 bg-white border border-slate-200 rounded-xl text-slate-600 font-medium hover:bg-slate-50 hover:text-slate-900 hover:border-slate-300 transition-all shadow-sm flex items-center justify-center mx-auto gap-2">
                    <span>{{ __('messages.load_more') }}</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
            </div>
            ` : ''}
        </div>`;
    }

    async function loadMoreMovements() {
        const btn = document.getElementById('load-more-btn');
        if(btn) {
            btn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-slate-600 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> {{ __('messages.loading') }}';
            btn.disabled = true;
        }

        const offset = movements.length;
        const limit = 9;
        const url = `{{ route('admin.inventory.movements_json') }}?warehouse_id={{ (int)$warehouse->id }}&offset=${offset}&limit=${limit}`;
        
        try {
            const res = await fetch(url);
            if (!res.ok) throw new Error('Network response was not ok');
            const newItems = await res.json();
            
            if (Array.isArray(newItems) && newItems.length > 0) {
                movements = movements.concat(newItems);
                if (newItems.length < limit) {
                    hasMoreMovements = false;
                }
            } else {
                hasMoreMovements = false;
            }
            renderMovements();
        } catch (error) {
            console.error('Error loading more movements:', error);
            if(btn) {
                btn.innerText = '{{ __('messages.retry') }}';
                btn.disabled = false;
            }
        }
    }

    function createMovementCard(item){
      const styles = getTypeStyles(item.raw_type);
      return `
          <div class="card-hover bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-4">
              <div class="flex items-center justify-between mb-3">
                <span class="${styles.bgColor} ${styles.textColor} ${styles.borderColor} border px-3 py-1.5 rounded-lg text-sm font-medium">
                  ${item.type}
                </span>
                <div class="flex items-center gap-3">
                  <span class="text-xl font-bold ${item.quantity < 0 ? 'text-red-600' : 'text-slate-800'}" style="line-height: 1.2;">
                    ${item.quantity > 0 ? '+' : ''}${item.quantity}
                  </span>
                  <button onclick="openDetailModal(${item.id})" class="p-2 rounded-lg hover:bg-slate-100 transition-colors duration-200 group" aria-label="{{ __('messages.view_details') }}">
                    <svg class="w-5 h-5 text-slate-400 group-hover:text-slate-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                  </button>
                </div>
              </div>
              <div class="flex items-center mb-2">
                <svg class="w-4 h-4 mr-1.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm text-slate-600" style="line-height: 1.5;">
                  ${item.time}
                </span>
              </div>
              <div class="pt-2 border-t border-slate-100">
                <p class="text-xs text-slate-500" style="line-height: 1.5;">
                  ${item.note || '{{ __('messages.no_note') }}'}
                </p>
              </div>
            </div>
          </div>
        `;
    }

    function openDetailModal(itemId) {
      const item = movements.find(i => i.id === itemId);
      if (!item) return;
      
      const styles = getTypeStyles(item.raw_type);
      const quantityChange = item.product.change;
      const changeSign = quantityChange > 0 ? '+' : '';
      const changeColor = quantityChange > 0 ? 'text-emerald-600' : 'text-red-600';
      
      const modalContent = document.getElementById('detail-modal-content');
      modalContent.innerHTML = `
        <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl p-6 mb-6">
          <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
              <h3 class="text-xl font-bold text-slate-800 mb-2" style="line-height: 1.2;">${item.product.name}</h3>
              <p class="text-sm text-slate-600 mb-1">SKU: <span class="font-medium">${item.product.sku}</span></p>
              <p class="text-sm text-slate-600">{{ __('messages.variant') }}: <span class="font-medium">${item.product.variant}</span></p>
            </div>
            <span class="${styles.bgColor} ${styles.textColor} ${styles.borderColor} border px-4 py-2 rounded-lg text-sm font-medium">
              ${item.type}
            </span>
          </div>
          
          <div class="flex items-center gap-4 pt-4 border-t border-slate-200">
            <div class="flex-1 text-center bg-white rounded-lg p-3">
              <p class="text-xs text-slate-500 mb-1">{{ __('messages.change') }}</p>
              <p class="text-2xl font-bold ${changeColor}">${changeSign}${quantityChange}</p>
            </div>
          </div>
        </div>
        
        <div class="space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div class="bg-slate-50 rounded-lg p-4">
              <div class="flex items-center mb-2">
                <svg class="w-4 h-4 text-slate-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-xs text-slate-500 font-medium">{{ __('messages.time') }}</p>
              </div>
              <p class="text-sm font-semibold text-slate-700">${item.time}</p>
            </div>
            
            <div class="bg-slate-50 rounded-lg p-4">
              <div class="flex items-center mb-2">
                <svg class="w-4 h-4 text-slate-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <p class="text-xs text-slate-500 font-medium">{{ __('messages.performer') }}</p>
              </div>
              <p class="text-sm font-semibold text-slate-700">${item.product.user}</p>
            </div>
          </div>
          
          <div class="bg-slate-50 rounded-lg p-4">
            <div class="flex items-center mb-2">
              <svg class="w-4 h-4 text-slate-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
              </svg>
              <p class="text-xs text-slate-500 font-medium">{{ __('messages.warehouse') }}</p>
            </div>
            <p class="text-sm font-semibold text-slate-700">${item.product.warehouse}</p>
          </div>
          
          <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
            <div class="flex items-center mb-2">
              <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <p class="text-xs text-blue-700 font-medium">{{ __('messages.reason_ref') }}</p>
            </div>
            <p class="text-sm text-blue-800" style="line-height: 1.5;">${item.product.reason}</p>
          </div>
          
          <div class="bg-slate-50 rounded-lg p-4 border border-slate-200">
            <div class="flex items-center mb-2">
              <svg class="w-4 h-4 text-slate-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
              <p class="text-xs text-slate-500 font-medium">{{ __('messages.system_note') }}</p>
            </div>
            <p class="text-xs text-slate-600" style="line-height: 1.5;">${item.note || '{{ __('messages.none') }}'}</p>
          </div>
        </div>
      `;
      
      const modal = document.getElementById('detail-modal');
      const backdrop = document.getElementById('detail-modal-backdrop');
      
      backdrop.classList.remove('hidden');
      modal.classList.remove('hidden');
      
      setTimeout(() => {
        backdrop.classList.add('opacity-100');
        modal.classList.remove('opacity-0', 'scale-95');
        modal.classList.add('opacity-100', 'scale-100');
      }, 10);
    }
    
    function closeDetailModal() {
      const modal = document.getElementById('detail-modal');
      const backdrop = document.getElementById('detail-modal-backdrop');
      
      backdrop.classList.remove('opacity-100');
      modal.classList.remove('opacity-100', 'scale-100');
      modal.classList.add('opacity-0', 'scale-95');
      
      setTimeout(() => {
        modal.classList.add('hidden');
        backdrop.classList.add('hidden');
      }, 300);
    }

    setupTabs();renderContent();
    window.toggleSubmenu = function(id){const submenu=document.getElementById(id+'-submenu');const arrow=document.getElementById(id+'-arrow');if(!submenu||!arrow)return;submenu.classList.toggle('hidden');arrow.classList.toggle('rotate-180');}
  </script>
 </body>
</html>
