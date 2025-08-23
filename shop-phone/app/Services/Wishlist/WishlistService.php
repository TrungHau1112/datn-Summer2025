<?php

namespace App\Services\Wishlist;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Repositories\Wishlist\WishlistRepository;

class WishlistService 
{
    protected $wishlistRepository;

    public function __construct(WishlistRepository $wishlistRepository)
    {
        $this->wishlistRepository = $wishlistRepository;
    }

    public function addWishlist($userId, $productId){
        DB::beginTransaction();
        try {
            $create = $this->wishlistRepository->create([
                'product_id' => $productId,
                'user_id' => $userId
            ]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            echo $e->getMessage();die();
            // $this->log($e);
            // return false;
        }
    }

    public function removeWishlist($userId, $productId){
        DB::beginTransaction();
        try {
            $delete = $this->wishlistRepository->deleteManyWhere('user_id', $userId, 'product_id', $productId);
            DB::commit();
            return true;
        } catch (\Exception $e) {
           DB::rollback();
            // echo $e->getMessage();die();
            $this->log($e);
            return false;
        }
    }
}
