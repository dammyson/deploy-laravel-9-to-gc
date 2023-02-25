<?php

namespace App\Services\User;

use App\Models\Product;
use App\Models\Review;
use App\Models\Stage;
use App\Models\User;
use App\Services\BaseServiceInterface;

class GetReviewCreatorByReviewer implements BaseServiceInterface
{
    protected $reviewer;

    public function __construct($reviewer)
    {
        $this->reviewer = $reviewer;
    }

    public function run()
    {
        return $this->processActivity();
    }

    private function processActivity()
    {
        $stage =  Stage::findorfail($this->reviewer->stage_id);
        $review =  Review::findorfail($stage->review_id);
        $product =  Product::findorfail($review->product_id);
        $user =  User::findorfail($review->user_id);

        $content_array = (object)array(
            "creator" =>  $user,
            "product" => $product,
            "stage" =>  $stage,
            "review" =>  $review,
        );
        return  $content_array;
    }
}
