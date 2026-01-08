<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Wishlist;
use App\Models\Product;
use App\Models\Address;
use App\Models\Order;

class ClientDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $tab = (string)$request->query('tab', 'journey');
        $wishlistItems = $user ? Wishlist::where('user_id', $user->id)->with('product')->latest()->limit(10)->get() : collect();
        $addresses = $user ? Address::where('user_id', $user->id)->orderByDesc('is_default')->latest()->get() : collect();
        $journeyOrders = collect();
        $journeyStats = [];
        if ($user) {
            $journeyOrders = Order::where('user_id', $user->id)
                ->with(['items.productVariant.product.images' => function($q) {
                    $q->orderByDesc('is_primary')->orderBy('position');
                }])
                ->latest()
                ->limit(5)
                ->get();
            $journeyStats = [
                'pending' => Order::where('user_id', $user->id)->where('status', 'new')->count(),
                'processing' => Order::where('user_id', $user->id)->where('status', 'processing')->count(),
                'shipped' => Order::where('user_id', $user->id)->where('status', 'shipped')->count(),
                'completed' => Order::where('user_id', $user->id)->where('status', 'completed')->count(),
                'cancelled' => Order::where('user_id', $user->id)->where('status', 'cancelled')->count(),
                'failed' => Order::where('user_id', $user->id)->where('status', 'failed')->count(),
                'total_spent' => (float)Order::where('user_id', $user->id)->where('status', 'completed')->sum('total'),
            ];
        }
        $settings = [];
        try {
            if (Storage::disk('local')->exists('settings.json')) {
                $raw = Storage::disk('local')->get('settings.json');
                $settings = json_decode($raw, true) ?: [];
            }
        } catch (\Throwable $e) {
            $settings = [];
        }
        $store = is_array($settings) ? ($settings['store'] ?? []) : [];
        $headerLogoUrl = is_array($store) ? ($store['header_logo_url'] ?? null) : null;
        $storeTagline = is_array($store) ? ($store['tagline'] ?? null) : null;
        return view('client.dashboard.index', compact('tab','wishlistItems','user','addresses','journeyOrders','journeyStats','headerLogoUrl','storeTagline'));
    }

    public function wishlistAdd(Request $request)
    {
        $user = $request->user();
        if (!$user) { return response()->json(['error' => 'unauthenticated'], 401); }
        $data = $request->validate([
            'product_id' => ['required','integer','exists:products,id'],
            'product_variant_id' => ['nullable','integer'],
        ]);
        $exists = Wishlist::where('user_id', $user->id)
            ->where('product_id', $data['product_id'])
            ->where('product_variant_id', $data['product_variant_id'] ?? null)
            ->first();
        if ($exists) { 
            $exists->delete();
            $count = Wishlist::where('user_id', $user->id)->count();
            return response()->json(['status' => 'removed', 'wishlist_count' => $count]); 
        }
        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $data['product_id'],
            'product_variant_id' => $data['product_variant_id'] ?? null,
        ]);
        $count = Wishlist::where('user_id', $user->id)->count();
        return response()->json(['status' => 'ok', 'wishlist_count' => $count]);
    }

    public function wishlistRemove(Request $request)
    {
        $user = $request->user();
        if (!$user) { return response()->json(['error' => 'unauthenticated'], 401); }
        $data = $request->validate([
            'product_id' => ['required','integer'],
            'product_variant_id' => ['nullable','integer'],
        ]);
        Wishlist::where('user_id', $user->id)
            ->where('product_id', $data['product_id'])
            ->where('product_variant_id', $data['product_variant_id'] ?? null)
            ->delete();
        $count = Wishlist::where('user_id', $user->id)->count();
        return response()->json(['status' => 'ok', 'wishlist_count' => $count]);
    }

    public function uploadAvatar(Request $request)
    {
        $user = $request->user();
        if (!$user) { return redirect()->route('login'); }
        $request->validate([
            'avatar' => ['required', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = '/storage/' . $path;
            $user->save();
        }

        return back()->with('status', 'Đã cập nhật ảnh đại diện');
    }

    public function addressStore(Request $request)
    {
        $user = $request->user();
        if (!$user) { return redirect()->route('login'); }
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'phone' => ['nullable','string','max:50'],
            'address_line' => ['required','string','max:255'],
            'ward' => ['nullable','string','max:255'],
            'city' => ['nullable','string','max:255'],
            'is_default' => ['nullable','boolean'],
        ]);
        $addr = new Address();
        $addr->user_id = $user->id;
        $addr->name = $data['name'];
        $addr->phone = $data['phone'] ?? null;
        $addr->address_line = $data['address_line'];
        $addr->ward = $data['ward'] ?? null;
        $addr->city = $data['city'] ?? null;
        $addr->is_default = (bool)($data['is_default'] ?? false);
        if ($addr->is_default) {
            Address::where('user_id', $user->id)->update(['is_default' => false]);
        }
        $addr->save();
        return redirect()->route('client.dashboard')->with('status','Đã thêm địa chỉ');
    }

    public function addressDefault(Request $request, int $addressId)
    {
        $user = $request->user();
        if (!$user) { return redirect()->route('login'); }
        $addr = Address::where('user_id', $user->id)->findOrFail($addressId);
        Address::where('user_id', $user->id)->update(['is_default' => false]);
        $addr->is_default = true;
        $addr->save();
        return redirect()->route('client.dashboard')->with('status','Đã đặt địa chỉ mặc định');
    }

    public function addressDestroy(Request $request, int $addressId)
    {
        $user = $request->user();
        if (!$user) { return redirect()->route('login'); }
        $addr = Address::where('user_id', $user->id)->findOrFail($addressId);
        $addr->delete();
        return redirect()->route('client.dashboard')->with('status','Đã xóa địa chỉ');
    }

    public function wishlist(Request $request)
    {
        $user = $request->user();
        if (!$user) { return redirect()->route('login'); }
        $items = Wishlist::where('user_id', $user->id)->with('product')->latest()->get();
        return view('client.dashboard.wishlist', ['user' => $user, 'items' => $items]);
    }

    public function addresses(Request $request)
    {
        $user = $request->user();
        if (!$user) { return redirect()->route('login'); }
        $addresses = Address::where('user_id', $user->id)->orderByDesc('is_default')->latest()->get();
        return view('client.dashboard.addresses', ['user' => $user, 'addresses' => $addresses]);
    }

    public function orders(Request $request)
    {
        $user = $request->user();
        if (!$user) { return redirect()->route('login'); }
        $orders = Order::where('user_id', $user->id)
            ->with(['items.productVariant.product.images' => function($q) {
                $q->orderByDesc('is_primary')->orderBy('position');
            }])
            ->latest()
            ->paginate(12);
        return view('client.dashboard.orders', ['user' => $user, 'orders' => $orders]);
    }

    public function measurements(Request $request)
    {
        $user = $request->user();
        if (!$user) { return redirect()->route('login'); }
        return view('client.dashboard.measurements', ['user' => $user]);
    }
}
