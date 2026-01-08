<?php

namespace App\Http\Controllers;

use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostCategoryController extends Controller
{
    public function index()
    {
        $allCategories = PostCategory::withCount('posts')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
            
        return view('admin.posts.categories', compact('allCategories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:post_categories,slug'],
            'parent_id' => ['nullable', 'integer', 'exists:post_categories,id'],
            'description' => ['nullable', 'string'],
        ]);

        $slug = $data['slug'] ?? Str::slug($data['name']);
        
        // If slug was auto-generated, ensure it is unique
        if (empty($data['slug'])) {
            $originalSlug = $slug;
            $count = 1;
            while (PostCategory::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
        }
        
        PostCategory::create([
            'name' => $data['name'],
            'slug' => $slug,
            'parent_id' => $data['parent_id'] ?? null,
            'description' => $data['description'] ?? null,
            'sort_order' => 0,
            'is_active' => true,
        ]);

        return redirect()->route('admin.posts.categories')->with('status', 'Tạo chuyên mục thành công');
    }

    public function update(Request $request, $id)
    {
        $category = PostCategory::findOrFail((int)$id);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:post_categories,slug,' . $category->id],
            'parent_id' => ['nullable', 'integer', 'exists:post_categories,id'],
            'description' => ['nullable', 'string'],
        ]);

        $slug = $data['slug'] ?? Str::slug($data['name']);

        // If slug was auto-generated, ensure it is unique
        if (empty($data['slug'])) {
            $originalSlug = $slug;
            $count = 1;
            while (PostCategory::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
        }

        $category->update([
            'name' => $data['name'],
            'slug' => $slug,
            'parent_id' => $data['parent_id'] ?? null,
            'description' => $data['description'] ?? null,
        ]);

        return redirect()->route('admin.posts.categories')->with('status', 'Cập nhật chuyên mục thành công');
    }

    public function destroy($id)
    {
        $category = PostCategory::findOrFail((int)$id);
        // Optional: Check if has posts or children before delete? 
        // For now, let's just delete. Model relationships might handle nulling or cascading.
        // Post migration has onDelete('set null') for category_id, so it's safe.
        
        $category->delete();
        return redirect()->route('admin.posts.categories')->with('status', 'Đã xóa chuyên mục');
    }
}
