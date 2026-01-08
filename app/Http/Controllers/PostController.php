<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostComment;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::where('status', 'published');

        if ($request->has('q') && $request->q != '') {
            $q = $request->q;
            $query->where(function($query) use ($q) {
                $query->where('title', 'like', '%' . $q . '%')
                      ->orWhere('content', 'like', '%' . $q . '%')
                      ->orWhere('excerpt', 'like', '%' . $q . '%');
            });
        }

        $posts = $query->orderBy('published_at', 'desc')
            ->paginate(9)
            ->appends($request->query());
        
        return view('client.posts.index', compact('posts'));
    }

    public function category(string $slug)
    {
        $category = \App\Models\PostCategory::where('slug', $slug)->firstOrFail();
        $posts = Post::where('status', 'published')
            ->where('category_id', $category->id)
            ->orderBy('published_at', 'desc')
            ->paginate(9)
            ->appends(request()->query());
        return view('client.posts.index', compact('posts', 'category'));
    }

    public function categoryPath(string $path)
    {
        $segments = collect(explode('/', trim($path, '/')))
            ->filter(fn($s) => $s !== '')
            ->values();
        if ($segments->isEmpty()) {
            abort(404);
        }
        $slug = $segments->last();
        $category = \App\Models\PostCategory::where('slug', $slug)->firstOrFail();
        $posts = Post::where('status', 'published')
            ->where('category_id', $category->id)
            ->orderBy('published_at', 'desc')
            ->paginate(9)
            ->appends(request()->query());
        return view('client.posts.index', compact('posts', 'category'));
    }

    public function tag(string $slug)
    {
        $tag = \App\Models\PostTag::where('slug', $slug)->firstOrFail();
        $posts = Post::where('status', 'published')
            ->whereHas('tags', function($q) use ($slug) {
                $q->where('slug', $slug);
            })
            ->orderBy('published_at', 'desc')
            ->paginate(9)
            ->appends(request()->query());
            
        return view('client.posts.index', compact('posts', 'tag'));
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)
            ->where('status', 'published')
            ->with(['author', 'category', 'tags', 'comments' => function($q) {
                $q->where('status', 'approved')
                  ->whereNull('parent_id')
                  ->with(['replies' => function($r) {
                      $r->where('status', 'approved');
                  }]);
            }])
            ->firstOrFail();

        if ($post->category) {
            $categoryPath = $this->buildCategoryPath($post->category);
            return redirect()->route('blog.show.path', ['path' => $categoryPath, 'slug' => $post->slug], 301);
        }

        return $this->renderPost($post);
    }

    private function renderPost($post)
    {
        $post->increment('views');

        $recent_posts = Post::where('status', 'published')
            ->where('id', '!=', $post->id)
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        $tags = \App\Models\PostTag::has('posts')->get();

        $prev_post = Post::where('status', 'published')
            ->where('published_at', '<', $post->published_at)
            ->orderBy('published_at', 'desc')
            ->first();

        $next_post = Post::where('status', 'published')
            ->where('published_at', '>', $post->published_at)
            ->orderBy('published_at', 'asc')
            ->first();

        // TOC Generation
        $toc = [];
        $parsedContent = $post->content;

        if (!empty($parsedContent)) {
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true);
            // Handle UTF-8 correctly
            $dom->loadHTML('<?xml encoding="utf-8" ?><div>' . $parsedContent . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            libxml_clear_errors();

            $xpath = new \DOMXPath($dom);
            $headings = $xpath->query('//h2|//h3|//h4');
            $toc = [];

            foreach ($headings as $heading) {
                $text = $heading->textContent;
                $slug = \Str::slug($text);
                if (empty($slug)) {
                    $slug = 'heading-' . count($toc);
                }
                
                // Ensure unique ID
                $id = $slug;
                $count = 1;
                while (collect($toc)->where('id', $id)->count() > 0) {
                    $id = $slug . '-' . $count++;
                }

                $heading->setAttribute('id', $id);
                $toc[] = [
                    'id' => $id,
                    'text' => $text,
                    'level' => (int)substr($heading->nodeName, 1)
                ];
            }

            $nodesWithStyle = $xpath->query('//*[@style]');
            foreach ($nodesWithStyle as $node) {
                $style = $node->getAttribute('style');
                $parts = array_filter(array_map('trim', explode(';', $style)));
                $kv = [];
                foreach ($parts as $part) {
                    $pair = array_map('trim', explode(':', $part, 2));
                    if (count($pair) === 2) {
                        $key = strtolower($pair[0]);
                        $value = strtolower($pair[1]);
                        if ($key === 'background-color' || $key === 'background') {
                            continue;
                        }
                        if ($key === 'color' && ($value === 'rgb(0, 0, 0)' || $value === '#000' || $value === 'black')) {
                            continue;
                        }
                        $kv[$key] = $pair[1];
                    }
                }
                if (!empty($kv)) {
                    $newStyle = '';
                    foreach ($kv as $k => $v) {
                        $newStyle .= $k . ': ' . $v . ';';
                    }
                    $node->setAttribute('style', $newStyle);
                } else {
                    $node->removeAttribute('style');
                }
            }

            // Save HTML (getting inner content of the wrapper div)
            $container = $dom->getElementsByTagName('div')->item(0);
            if ($container) {
                $parsedContent = '';
                foreach ($container->childNodes as $child) {
                    $parsedContent .= $dom->saveHTML($child);
                }
            }
        }

        return view('client.posts.show', compact('post', 'recent_posts', 'tags', 'prev_post', 'next_post', 'toc', 'parsedContent'));
    }

    private function buildCategoryPath($category)
    {
        $path = [];
        $curr = $category;
        while ($curr) {
            $path[] = $curr->slug;
            $curr = $curr->parent;
        }
        return implode('/', array_reverse($path));
    }

    public function resolveBlogPath($path, $slug = null)
    {
        // 1. Try to see if it matches a Post
        $segments = explode('/', trim($path, '/'));
        $slug = $slug ?: end($segments);
        
        $post = Post::where('slug', $slug)
            ->where('status', 'published')
            ->with(['author', 'category', 'tags', 'comments' => function($q) {
                $q->where('status', 'approved')
                  ->whereNull('parent_id')
                  ->with(['replies' => function($r) {
                      $r->where('status', 'approved');
                  }]);
            }])
            ->first();

        if ($post) {
            if ($post->category) {
                $catPath = $this->buildCategoryPath($post->category);
                if ($path === $catPath) {
                    return $this->renderPost($post);
                }
                return redirect()->route('blog.show.path', ['path' => $catPath, 'slug' => $post->slug], 301);
            } else {
                if ($path === $post->slug) {
                    return $this->renderPost($post);
                }
                return redirect()->route('blog.show', ['slug' => $post->slug], 301);
            }
        }
        
        // 2. Not a Post (or path didn't match), Try Category
        return $this->categoryPath($path);
    }

    public function storeComment(Request $request, $slug)
    {
        $post = Post::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:post_comments,id',
        ]);

        $comment = PostComment::create([
            'post_id' => $post->id,
            'parent_id' => $request->input('parent_id'),
            'user_id' => auth()->id(), // Nullable if guest
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'content' => $request->input('content'),
            'status' => 'pending', // Default to pending
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Bình luận của bạn đã được gửi và đang chờ duyệt.');
    }
}
