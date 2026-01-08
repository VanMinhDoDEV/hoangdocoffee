<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['product', 'user']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                  ->orWhere('reviewer_name', 'like', "%{$search}%")
                  ->orWhere('reviewer_email', 'like', "%{$search}%")
                  ->orWhereHas('product', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filters
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('rating') && $request->rating !== 'all') {
            $query->where('rating', $request->rating);
        }

        // Sorting
        $query->orderBy('created_at', 'desc');

        $reviews = $query->paginate(10)->withQueryString();

        // Statistics
        $totalReviews = Review::count();
        $avgRating = Review::avg('rating') ?? 0;
        
        // Rating distribution
        $ratingCounts = Review::selectRaw('rating, count(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();
        
        $distribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = $ratingCounts[$i] ?? 0;
            $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
            $distribution[$i] = [
                'count' => $count,
                'percentage' => round($percentage, 1)
            ];
        }

        // Weekly stats (simplified for now)
        $newReviewsThisWeek = Review::where('created_at', '>=', now()->startOfWeek())->count();
        $newReviewsLastWeek = Review::whereBetween('created_at', [
            now()->subWeek()->startOfWeek(),
            now()->subWeek()->endOfWeek()
        ])->count();
        
        $growth = 0;
        if ($newReviewsLastWeek > 0) {
            $growth = (($newReviewsThisWeek - $newReviewsLastWeek) / $newReviewsLastWeek) * 100;
        }

        return view('admin.reviews.index', compact(
            'reviews', 
            'totalReviews', 
            'avgRating', 
            'distribution', 
            'newReviewsThisWeek',
            'growth'
        ));
    }

    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        
        if ($request->has('status')) {
            $review->update(['status' => $request->status]);
        }

        return back()->with('success', __('messages.review_updated_success'));
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return back()->with('success', __('messages.review_deleted_success'));
    }

    public function bulkAction(Request $request)
    {
        $ids = $request->ids;
        $action = $request->action;

        if (empty($ids) || !is_array($ids)) {
            return back()->with('error', __('messages.no_items_selected'));
        }

        if ($action === 'delete') {
            Review::whereIn('id', $ids)->delete();
            return back()->with('success', __('messages.reviews_deleted_success'));
        } elseif (in_array($action, ['published', 'pending', 'hidden'])) {
            Review::whereIn('id', $ids)->update(['status' => $action]);
            return back()->with('success', __('messages.reviews_status_updated_success'));
        }

        return back();
    }
}
