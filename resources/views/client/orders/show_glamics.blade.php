@extends('client.layouts.master')

@section('title', 'Đơn hàng #' . str_pad($order->id, 6, '0', STR_PAD_LEFT) . ' | ' . ($storeSettings['name'] ?? 'Shop06'))

@section('content')
@include('client.components.breadcrumb', [
  'title' => 'Chi tiết đơn hàng #' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
  'items' => [
    ['label' => 'Trang chủ', 'url' => route('home')],
    ['label' => 'Chi tiết đơn hàng #' . str_pad($order->id, 6, '0', STR_PAD_LEFT)]
  ]
])
<div class="shop-product-section section section-padding"> 
  <div class="container">
    @include('client.orders._show_modal', ['order' => $order])
  </div>
</div>

</div>
@endsection
