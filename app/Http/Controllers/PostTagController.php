<?php

namespace App\Http\Controllers;

use App\Models\PostTag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostTagController extends Controller
{
    public function index(Request $request)
    {
        $query = PostTag::withCount('posts');

        if ($request->has('q')) {
            $search = $request->q;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
        }

        $tags = $query->latest()->paginate(20);
        
        $totalTags = PostTag::count();
        $mostUsedTag = PostTag::withCount('posts')->orderByDesc('posts_count')->first();
        $popularTags = PostTag::withCount('posts')->orderByDesc('posts_count')->take(10)->get();

        return view('admin.posts.tags', compact('tags', 'totalTags', 'mostUsedTag', 'popularTags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:post_tags,name',
            'slug' => 'nullable|string|max:255|unique:post_tags,slug',
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);

        PostTag::create([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return redirect()->route('admin.posts.tags')->with('status', 'Đã tạo thẻ thành công');
    }

    public function update(Request $request, $id)
    {
        $tag = PostTag::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:post_tags,name,'.$tag->id,
            'slug' => 'nullable|string|max:255|unique:post_tags,slug,'.$tag->id,
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);

        $tag->update([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return redirect()->route('admin.posts.tags')->with('status', 'Đã cập nhật thẻ');
    }

    public function destroy($id)
    {
        $tag = PostTag::findOrFail($id);
        $tag->delete();
        
        return redirect()->route('admin.posts.tags')->with('status', 'Đã xóa thẻ');
    }
}
