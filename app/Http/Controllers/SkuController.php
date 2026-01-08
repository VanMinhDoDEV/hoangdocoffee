<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SkuController extends Controller
{
    public function index(Request $request)
    {
        $attributes = \App\Models\ProductAttribute::orderBy('name')->get();
        $attributeId = (int)($request->query('attribute_id') ?? 0);
        $currentAttribute = $attributeId ? \App\Models\ProductAttribute::find($attributeId) : ($attributes->first() ?: null);
        $values = $currentAttribute ? \App\Models\ProductAttributeValue::where('attribute_id', $currentAttribute->id)->orderBy('value')->get() : collect();
        return view('admin.attributes.index', [
            'attributes' => $attributes,
            'currentAttribute' => $currentAttribute,
            'values' => $values,
        ]);
    }

    public function store(Request $request)
    {
        $action = $request->input('action');
        if ($action === 'create_attribute') {
            $data = $request->validate([
                'name' => ['required','string','max:100','unique:product_attributes,name'],
                'type' => ['nullable','string','in:text,color'],
            ]);
            $created = \App\Models\ProductAttribute::create([
                'name' => $data['name'],
                'type' => $data['type'] ?? 'text',
            ]);
            return response()->json(['status' => 'ok', 'attribute' => $created], 201);
        }

        if ($action === 'delete_attribute') {
            $data = $request->validate([
                'attribute_id' => ['required','integer','exists:product_attributes,id'],
            ]);
            \App\Models\ProductAttribute::where('id', $data['attribute_id'])->delete();
            return response()->json(['status' => 'ok']);
        }

        if ($action === 'update_attribute') {
            $data = $request->validate([
                'attribute_id' => ['required','integer','exists:product_attributes,id'],
                'name' => ['nullable','string','max:100'],
                'type' => ['nullable','string','in:text,color'],
            ]);
            $attr = \App\Models\ProductAttribute::findOrFail($data['attribute_id']);
            $payload = [];
            if (isset($data['name'])) { $payload['name'] = $data['name']; }
            if (isset($data['type'])) { $payload['type'] = $data['type']; }
            if ($payload) { $attr->update($payload); }
            return response()->json(['status' => 'ok', 'attribute' => $attr]);
        }

        if ($action === 'create_attribute_value') {
            $data = $request->validate([
                'attribute_id' => ['required','integer','exists:product_attributes,id'],
                'value' => ['required','string','max:255'],
            ]);
            $val = \App\Models\ProductAttributeValue::firstOrCreate([
                'attribute_id' => $data['attribute_id'],
                'value' => $data['value'],
            ], [
                'slug' => Str::slug($data['value']),
            ]);
            return response()->json(['status' => 'ok', 'value' => $val], 201);
        }

        if ($action === 'delete_attribute_value') {
            $data = $request->validate([
                'attribute_value_id' => ['required','integer','exists:product_attribute_values,id'],
            ]);
            \App\Models\ProductAttributeValue::where('id', $data['attribute_value_id'])->delete();
            return response()->json(['status' => 'ok']);
        }

        return response()->json(['status' => 'error', 'message' => 'Không có hành động hợp lệ'], 422);
    }
}
