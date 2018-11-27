<?php

namespace Blog\Http\Controllers\Admin;

use Blog\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Blog\Comment;
use Blog\User;
use Blog\Post;
use Blog\Tag;

class DashboardController extends Controller
{
    public function counts()
    {
        return response()->json([
            'users_count' => User::count(),
            'posts_count' => Post::whereNotNull('published_at')->count(),
            'drafts_count' => Post::whereNull('published_at')->count(),
            'comments_count' => Comment::count(),
            'tags_count' => Tag::count(),
        ]);
    }

    public function users()
    {
        $query = User::selectRaw('
            extract(year from created_at) as year,
            extract(month from created_at) as month,
            count(*) as count
        ')
        ->whereBetween('created_at', [
            Carbon::now()->startOfMonth()->subMonths(11)->toDateString(),
            Carbon::now()->toDateTimeString()
        ])
        ->groupBy([
            'year',
            'month'
        ])
        ->orderBy('year')
        ->orderBy('month')
        ->get();

        $countPerMonth = $this->fillMonthCount($query);

        return response()->json([
            'months' => $countPerMonth->keys(),
            'counts' => $countPerMonth->values()
        ]);
    }

    public function posts()
    {
        $query = Post::groupBy([
            'year',
            'month'
        ])
        ->orderBy('year')
        ->orderBy('month');

        $posts = clone $query;
        $posts = $posts->selectRaw('
            extract(year from published_at) as year,
            extract(month from published_at) as month,
            count(*) as count
        ')
        ->whereBetween('published_at', [
            Carbon::now()->startOfMonth()->subMonths(11)->toDateString(),
            Carbon::now()->toDateTimeString()
        ])
        ->get();

        $drafts = clone $query;
        $drafts = $drafts->selectRaw('
            extract(year from updated_at) as year,
            extract(month from updated_at) as month,
            count(*) as count
        ')
        ->whereNull('published_at')
        ->whereBetween('updated_at', [
            Carbon::now()->startOfMonth()->subMonths(11)->toDateString(),
            Carbon::now()->toDateTimeString()
        ])
        ->get();

        $postCountPerMonth = $this->fillMonthCount($posts);
        $draftCountPerMonth = $this->fillMonthCount($drafts);

        return response()->json([
            'months' => $postCountPerMonth->keys(),
            'posts' => $postCountPerMonth->values(),
            'drafts' => $draftCountPerMonth->values()
        ]);
    }

    public function comments($locale)
    {
        $query = Post::select([
            "title_$locale",
        ])
        ->withCount('comments')
        ->orderByDesc('comments_count')
        ->limit(10)
        ->get();

        $comments = $query->mapWithKeys(function ($item) use ($locale) {
            return [
                $item["title_$locale"] => $item['comments_count'],
            ];
        });

        return response()->json([
            'posts' => $comments->keys(),
            'counts' => $comments->values()
        ]);
    }

    public function tags($locale)
    {
        $query = Tag::select([
            "name_$locale",
        ])
        ->withCount('publications')
        ->withCount('drafts')
        ->orderByDesc('publications_count')
        ->orderByDesc('drafts_count')
        ->limit(10)
        ->get();

        $tags = $query->mapWithKeys(function ($item) use ($locale) {
            return [
                $item["name_$locale"] => [
                    $item['publications_count'],
                    $item['drafts_count'],
                ],
            ];
        });


        return response()->json([
            'tags' => $tags->keys(),
            'counts' => $tags->values()
        ]);
    }

    protected function getMonths()
    {
        return collect()
            ->times(12)
            ->sortBy(function ($month) {
                return ($month + (12 - Carbon::now()->month - 1)) % 12;
            })
            ->values();
    }

    protected function fillMonthCount($collection)
    {
        return $this->getMonths()
            ->mapWithKeys(function ($item) use ($collection) {
                $counts = $collection->mapWithKeys(function ($item) {
                    return [$item['month'] => $item['count']];
                });

                if ($counts[$item] ?? false) {
                    return [$item => $counts[$item]];
                }
                return [$item => 0];
            });
    }
}
