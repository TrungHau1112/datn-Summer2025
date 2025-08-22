<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Wishlist\WishlistService;
use App\Repositories\Wishlist\WishlistRepository;
use App\Models\User;

class WishlistController extends Controller
{
    protected $wishlistService;
    protected $wishlistRepository;

    public function __construct(WishlistService $wishlistService, WishlistRepository $wishlistRepository)
    {
        $this->wishlistService = $wishlistService;
        $this->wishlistRepository = $wishlistRepository;
    }

    public function index(Request $request)
    {
        $title = 'Danh sách yêu thích';
        $config = $this->config();
        $user = User::with('wishlists.product')->find(auth()->user()->id);
        return view('client.pages.wishlist.wish_list', compact(
            'config',
            'user',
            'title',
        ));
    }

    public function action(Request $request)
    {
        $user = auth()->user();
        $productId = $request->input('product_id');
        $wishlist = $user->wishlists()->where('product_id', $productId)->first();
        if ($wishlist) {
            $wishlist->delete();
            $count = $user->wishlists()->count();
            $data = [
                'count' => $count,
            ];
            return successResponse($data, 'Đã xóa sản phẩm ra khỏi mục yêu thích');
        } else {
            $user->wishlists()->create(['product_id' => $productId]);
            $count = $user->wishlists()->count();
            $data = [
                'count' => $count,
            ];
            return successResponse($data, 'Đã thêm sản phẩm vào mục yêu thích');
        }
    }
    public function count(Request $request)
    {
        $user = auth()->user();
        $productId = $request->input('product_id');
        $count = $user->wishlists()->count();
        return successResponse($count, 'Đã thêm sản phẩm vào mục yêu thích');
    }

    public function delete(Request $request)
    {
        $userId = $request->user()->id;
        if ($this->wishlistService->removeWishlist($userId, $request->product_id)) {
            return successResponse(null, 'Đã xóa sản phẩm ra khỏi mục yêu thích');
        }
        return errorResponse();
    }

    private function config()
    {
        return [
            'css' => [
                'client_asset/custom/css/wishlist.css'
            ],
            'js' => [

            ],
            'model' => 'user'
        ];
    }
}
