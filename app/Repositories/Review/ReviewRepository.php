<?php

namespace App\Repositories\Review;

use App\Repositories\BaseRepository;
use App\Models\Review;

class ReviewRepository extends BaseRepository
{

    protected $model;

    public function __construct(
        Review $model
    ) {
        $this->model = $model;
    }

    public function getReviews($productId)
    {
        return $this->model->where('product_id', $productId)->where('parent_id', null)->with('children')->get();
    }

    public function getRatingDetails($productId)
    {
        $ratingsQuery = $this->model->where('product_id', $productId)->where('parent_id', null)->get();
        $totalRatings = $ratingsQuery->count();
        if ($totalRatings === 0) {
            return [
                'percentages' => [
                    5 => 0,
                    4 => 0,
                    3 => 0,
                    2 => 0,
                    1 => 0,
                ],
                'average' => 0,
                'totalReviews' => $totalRatings,
            ];
        }
        $starsCount = $ratingsQuery->groupBy('rating')->map->count()->toArray();
        $percentages = [];
        for ($i = 5; $i >= 1; $i--) {
            $percentages[$i] = round(isset($starsCount[$i]) ? ($starsCount[$i] / $totalRatings) * 100 : 0, 1);
        }
        $sumRatings = $ratingsQuery->sum('rating');
        $averageRating = $totalRatings > 0 ? $sumRatings / $totalRatings : 0;
        return [
            'percentages' => $percentages,
            'average' => round($averageRating, 1),
            'totalReviews' => $totalRatings,
        ];
    }

    public function getReviewByProductAndUser($productId, $userId)
    {
        return $this->model
            ->where('product_id', $productId)
            ->where('user_id', $userId)
            ->with('children');
    }

}
