<?php

use App\Http\Controllers\Client\AccountController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\CommentForbiddenWordController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\AttributeCategoryController;
use App\Http\Controllers\Admin\DiscountCodeController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\PostController as AdminPostController;


use App\Http\Controllers\Admin\CollectionController;
use App\Http\Controllers\Client\WishlistController;

use App\Http\Controllers\Client\AuthController as ClientAuthController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\Client\CollectionController as ClientCollectionController;
use App\Http\Controllers\Client\CommentController as ClientCommentController;
use App\Http\Controllers\Client\CartController as ClientCartController;
use App\Http\Controllers\Client\CheckoutController as ClientCheckoutController;
use App\Http\Controllers\Client\IndexController;
use App\Http\Controllers\Client\ContactController as ClientContactController;
use App\Http\Controllers\Client\CategoryController as ClientCategoryController;
use App\Http\Controllers\Client\PostController as ClientPostController;
use App\Http\Controllers\Ajax\AjaxController as AjaxDashboardController;
use App\Http\Controllers\Ajax\LocationController;
use App\Http\Controllers\Client\VnPayController;
use App\Http\Controllers\Client\MomoController;


Route::get('/san-pham/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::post('/chat', [ChatController::class, 'handleChat'])->name('chat.handle');
use App\Http\Controllers\ChatController;
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show'); // Giả định ProductController
Route::post('/chat', [ChatController::class, 'handleChat'])->name('chat.handle');
// Route::get('/', function () {
//     return view('welcome');
// })->name('home');

Route::get('/account', function () {
    return view('admin.pages.account.application.index');
})->name('home');
Route::middleware(['authenticated', 'preventBackHistory'])->group(function () {
    Route::prefix('admin/dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::get('/ajax/getOrdersAndRevenueByYear', [DashboardController::class, 'getOrdersAndRevenueByYear'])->name('getOrdersByMonth');
        Route::get('/ajax/newCustomersByMonth', [DashboardController::class, 'newCustomersByMonth'])->name('newCustomersByMonth');
    });
    /* USER ROUTE */
    Route::prefix('admin/user')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [UserController::class, 'delete'])->name('delete');
    });
    Route::prefix('admin/staff')->name('staff.')->group(function () {
        Route::get('/', [StaffController::class, 'index'])->name('index');
        Route::get('/create', [StaffController::class, 'create'])->name('create');
        Route::post('/store', [StaffController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [StaffController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [StaffController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [StaffController::class, 'delete'])->name('delete');
    });
    /* PRODUCT ROUTE */
    Route::prefix('admin/post')->name('post.')->group(function () {
        Route::get('/index', [AdminPostController::class, 'index'])->name('index');
        Route::get('/create', [AdminPostController::class, 'create'])->name('create');
        Route::post('/store', [AdminPostController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [AdminPostController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [AdminPostController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [AdminPostController::class, 'delete'])->name('delete');
        Route::get('/trash', [AdminPostController::class, 'trash'])->name('trash');
    });
    /* PRODUCT ROUTE */
    Route::prefix('admin/product')->name('product.')->group(function () {
        Route::get('/index', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/store', [ProductController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [ProductController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [ProductController::class, 'delete'])->name('delete');
        Route::get('/trash', [ProductController::class, 'trash'])->name('trash');
    });

    /* ATTRIBUTE ROUTE */
    Route::prefix('admin/attributeCategory')->name('attributeCategory.')->group(function () {
        Route::get('/index', [AttributeCategoryController::class, 'index'])->name('index');
        Route::get('/create', [AttributeCategoryController::class, 'create'])->name('create');
        Route::post('/store', [AttributeCategoryController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [AttributeCategoryController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [AttributeCategoryController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [AttributeCategoryController::class, 'delete'])->name('delete');
        Route::get('/trash', [AttributeCategoryController::class, 'trash'])->name('trash');
    });

    /* PERMISSION ROUTE */
    Route::prefix('admin/permission')->name('permission.')->group(function () {
        Route::get('/index', [PermissionController::class, 'index'])->name('index');
        Route::get('/create', [PermissionController::class, 'create'])->name('create');
        Route::post('/store', [PermissionController::class, 'store'])->name('store');
        Route::get('/edit', [PermissionController::class, 'edit'])->name('edit');
        Route::put('/edit', [PermissionController::class, 'edit'])->name('edit');
        Route::get('/delete/{id}', [PermissionController::class, 'delete'])->name('delete');
    });

    /* ROLE ROUTE */
    Route::prefix('admin/role')->name('role.')->group(function () {
        // Route::get('/index', [RoleController::class, 'index'])->name('index');
        Route::get('/create', [RoleController::class, 'create'])->name('create');
        Route::post('/store', [RoleController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [RoleController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [RoleController::class, 'delete'])->name('delete');
    });

    /* ORDER ROUTE */
    Route::prefix('admin/order')->name('order.')->group(function () {
        Route::get('dataProduct', [OrderController::class, 'dataProduct'])->name('dataProduct');
        Route::get('getProduct', [OrderController::class, 'getProduct'])->name('getProduct');
        Route::get('index', [OrderController::class, 'index'])->name('index');
        Route::get('create', [OrderController::class, 'create'])->name('create');
        Route::post('store', [OrderController::class, 'store'])->name('store');
        Route::get('edit/{id}', [OrderController::class, 'edit'])->name('edit');
        Route::get('show/{id}', [OrderController::class, 'show'])->name('show');
        Route::put('update/{id}', [OrderController::class, 'update'])->name('update');
        Route::get('delete/{id}', [OrderController::class, 'delete'])->name('delete');
        Route::get('search_customer', [OrderController::class, 'searchCustomer'])->name('searchCustomer');
        Route::put('payment-status/{id}', [OrderController::class, 'updatePaymentStatus'])->name('updatepayment');
        Route::get('dataVariantsProduct/{id}', [OrderController::class, 'dataVariantsProduct'])->name('dataVariantsProduct');
        Route::get('/trash', [OrderController::class, 'trash'])->name('trash');
    });

    /* CATEGORY ROUTE */
    Route::prefix('admin/category')->name('category.')->group(function () {
        Route::get('/index', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/store', [CategoryController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [CategoryController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [CategoryController::class, 'delete'])->name('delete');
        Route::get('/trash', [CategoryController::class, 'trash'])->name('trash');
    });

    Route::prefix('admin/brand')->name('brand.')->group(function () {
        Route::get('/index', [BrandController::class, 'index'])->name('index');
        Route::get('/create', [BrandController::class, 'create'])->name('create');
        Route::post('/store', [BrandController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [BrandController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [BrandController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [BrandController::class, 'delete'])->name('delete');
        Route::get('/trash', [BrandController::class, 'trash'])->name('trash');
    });

    /* DISCOUNT CODE ROUTE */
    Route::prefix('admin/discount')->name('discountCode.')->group(function () {
        Route::get('/index', [DiscountCodeController::class, 'index'])->name('index');
        Route::get('/create', [DiscountCodeController::class, 'create'])->name('create');
        Route::post('/store', [DiscountCodeController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [DiscountCodeController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [DiscountCodeController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [DiscountCodeController::class, 'delete'])->name('delete');
        Route::get('/trash', [DiscountCodeController::class, 'trash'])->name('trash');
    });

    /* COMMENT ROUTE */
    Route::prefix('admin/comment')->name('comment.')->group(function () {
        Route::get('/index', [CommentController::class, 'index'])->name('index');
        Route::get('/create', [CommentController::class, 'create'])->name('create');
        Route::post('/store', [CommentController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [CommentController::class, 'edit'])->name('edit');
        Route::get('/reply/{id}', [CommentController::class, 'reply'])->name('reply');
        Route::put('/update/{id}', [CommentController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [CommentController::class, 'delete'])->name('delete');
    });

    /* REVIEW ROUTE */
    Route::prefix('admin/review')->name('review.')->group(function () {
        Route::get('/index', [ReviewController::class, 'index'])->name('index');
        Route::get('/reply/{id}', [ReviewController::class, 'reply'])->name('reply');
        Route::post('/store/{id}', [ReviewController::class, 'store'])->name('store');
        Route::get('/delete/{id}', [CommentForbiddenWordController::class, 'destroy'])->name('delete');
    });

    /* COLLECTION ROUTE */
    Route::prefix('admin/collection')->name('collection.')->group(function () {
        Route::get('/index', [CollectionController::class, 'index'])->name('index');
        Route::get('/getProductPoint', [CollectionController::class, 'getProductPoint'])->name('getProductPoint');
        Route::get('/create', [CollectionController::class, 'create'])->name('create');
        Route::post('/store', [CollectionController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [CollectionController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [CollectionController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [CollectionController::class, 'delete'])->name('delete');
        Route::get('/trash', [CollectionController::class, 'trash'])->name('trash');
    });

    /* FORBIDDEN WORD ROUTE */
    Route::prefix('admin/forbiddenword')->name('CommentForbiddenWord.')->group(function () {
        Route::get('/index', [CommentForbiddenWordController::class, 'index'])->name('index');
        Route::get('/create', [CommentForbiddenWordController::class, 'create'])->name('create');
        Route::post('/store', [CommentForbiddenWordController::class, 'store'])->name('store');
        Route::get('/delete/{id}', [CommentForbiddenWordController::class, 'delete'])->name('delete');
    });

    // setting route
    Route::prefix('admin/setting')->name('setting.')->group(function () {
        Route::get('/index', [SettingController::class, 'index'])->name('index');
        Route::get('/slide', [SettingController::class, 'slide'])->name('slide');
        Route::post('/slider', [SettingController::class, 'sliderUpdate'])->name('sliderUpdate');
        Route::get('/sliderDelete/{id}', [SettingController::class, 'sliderDelete'])->name('sliderDelete');
        Route::get('/general', [SettingController::class, 'general'])->name('general');
        Route::put('/general', [SettingController::class, 'generalUpdate'])->name('generalUpdate');
    });
    Route::prefix('setting/account')->name('setting.account.')->group(function () {
        Route::get('/{type}', [UserController::class, 'getInformation'])->name('index');
        Route::put('/update-{type}', [UserController::class, 'updateInformation'])->name('update');

    });
});

Route::middleware(['checkPermission'])->group(function () {
    Route::prefix('{model}')->name('{model}.')->group(function () {
        Route::get('/getData', [AjaxDashboardController::class, 'getData'])->name('ajax.dashboard.getData');
        Route::put('/actions', [AjaxDashboardController::class, 'updateMultiple'])->name('ajax.dashboard.changeStatusMultiple');
        Route::delete('/actions', [AjaxDashboardController::class, 'deleteMultiple'])->name('ajax.dashboard.deleteMultiple');
        Route::delete('/deleteItem', [AjaxDashboardController::class, 'deleteItem'])->name('ajax.dashboard.deleteItem');
        Route::put('/quickUpdate', [AjaxDashboardController::class, 'updateQuick'])->name('ajax.dashboard.quickUpdate');
        Route::put('/change/status', [AjaxDashboardController::class, 'updateStatus'])->name('ajax.dashboard.updateStatus');
        Route::put('/restoreItem', [AjaxDashboardController::class, 'restoreItem'])->name('ajax.dashboard.restoreItem');
        Route::delete('/destroyItem', [AjaxDashboardController::class, 'destroyItem'])->name('ajax.dashboard.destroyItem');
    });
});

Route::get('/ajax/getLocation', [LocationController::class, 'getLocation'])->name('ajax.getLocation');
Route::get('getProduct', [AjaxDashboardController::class, 'getProduct'])->name('getProduct');

// get attribute value
Route::get('/getAttribute', [AjaxDashboardController::class, 'getAttribute'])->name('ajax.dashboard.getAttribute');
Route::get('ajax/getAttributeValue', [AjaxDashboardController::class, 'getAttributeValue'])->name('ajax.dashboard.getAttributeValue');
Route::get('ajax/loadAttributeValue', [AjaxDashboardController::class, 'loadAttributeValue'])->name('ajax.dashboard.loadAttributeValue');

Route::get('/admin', [AuthController::class, 'index'])->name('auth.index')->middleware('unauthenticated');
Route::post('/admin', [AuthController::class, 'login'])->name('auth.login');

Route::get('/admin/forget-password', [AuthController::class, 'forget'])->name('auth.admin.forget');
Route::post('/admin/forget-password', [AuthController::class, 'postForgetPass'])->name('auth.admin.postForgetPass');

// Route để hiển thị form thay đổi mật khẩu với token và email
Route::get('/admin/reset-password/{token}/{email}', [AuthController::class, 'resetPassword'])->name('password.reset');

// Route để xử lý thay đổi mật khẩu
Route::post('/admin/reset-password', [AuthController::class, 'postChangePass'])->name('change.password');


Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// =======================================================CLIENT================================================================
// client route
route::middleware(['preventBackHistory'])->group(function () {
    Route::prefix('/')->name('client.')->group(function () {
        // auth route
        Route::prefix('')->middleware('clientLogin')->name('auth.')->group(function () {
            Route::get('dang-nhap', [ClientAuthController::class, 'login'])->name('login');
            Route::post('dang-nhap', [ClientAuthController::class, 'postLogin'])->name('post-login');
            Route::get('dang-ky', [ClientAuthController::class, 'register'])->name('register');
            Route::post('dang-ky', [ClientAuthController::class, 'postRegister'])->name('post-register');
            Route::get('quen-mat-khau', [ClientAuthController::class, 'forget'])->name('forget');
            Route::post('quen-mat-khau', [ClientAuthController::class, 'submitForgetPasswordForm'])->name('post-forget');
            Route::get('doi-mat-khau/{user}/{token}', [ClientAuthController::class, 'change'])->name('change');
            Route::post('doi-mat-khau/{user}/{token}', [ClientAuthController::class, 'submitChangePasswordForm'])->name('post-reset');
            Route::get('xac-nhan-tai-khoan/{email}', [ClientAuthController::class, 'active'])->name('active');
            Route::get('auth/google/redirect', [ClientAuthController::class, 'redirect'])->name('google.redirect');
            Route::get('auth/google/callback', [ClientAuthController::class, 'callback'])->name('google.callback');
        });

        Route::get('dang-xuat', [ClientAuthController::class, 'logout'])->name('auth.logout');

        // index route
        Route::get('/', [IndexController::class, 'home'])->name('home');

        // account route
        Route::prefix('tai-khoan')->middleware('clientAuth')->name('account.')->group(function () {
            Route::get('/', [AccountController::class, 'index'])->name('index');
            Route::post('ajax/edit-acccount', [AccountController::class, 'editAccount'])->name('edit-acccount');
            Route::post('ajax/change-pass-acccount', [AccountController::class, 'changePassAccount'])->name('change-pass-acccount');
            Route::get('ajax/get-order-all', [AccountController::class, 'getOrderAll'])->name('get-order-all');
            Route::get('ajax/get-order-by-status/{status}', [AccountController::class, 'getOrderByStatus'])->name('get-order-by-status');
            Route::get('ajax/get-order-detail', [AccountController::class, 'getOrderDetail'])->name('get-order-detail');
            Route::get('ajax/cancel-order', [AccountController::class, 'cancelOrder'])->name('cancel-order');
            Route::get('ajax/get-review', [AccountController::class, 'getReview'])->name('get-review');
        });

        Route::prefix('danh-muc')->name('category.')->group(function () {
            Route::get('{slug}', [ClientCategoryController::class, 'index'])->name('index');
            Route::get('/ajax/get-product', [ClientCategoryController::class, 'getProduct'])->name('get-product');
        });

        // product route
        Route::prefix('san-pham')->name('product.')->group(function () {
            Route::get('/', [ClientProductController::class, 'index'])->name('index');
            Route::get('/{slug}', [ClientProductController::class, 'detail'])->name('detail');
            Route::post('/so-sanh', [ClientProductController::class, 'compare'])->name('compare');
            Route::post('/ajax/change-quantity', [ClientProductController::class, 'changeQuantity'])->name('change-quantity');
            Route::get('/ajax/get-variant', [ClientProductController::class, 'getVariant'])->name('get-variant');
            Route::get('/ajax/search-product', [ClientProductController::class, 'searchProduct'])->name('get-variant');
            Route::get('/ajax/get-review', [ClientProductController::class, 'getReview'])->name('get-review');
            Route::post('/ajax/add-review', [ClientProductController::class, 'addReview'])->name('add-review');
            Route::get('/ajax/add-compare/{sku}', [ClientProductController::class, 'addCompare'])->name('add-compare');

        });
        // cart route
        Route::prefix('gio-hang')->name('cart.')->group(function () {
            Route::get('/count', [ClientCartController::class, 'count'])->name('count');
            route::middleware('clientAuth')->group(function () {
                Route::get('/', [ClientCartController::class, 'index'])->name('index');
                Route::get('/listCart', [ClientCartController::class, 'listCart'])->name('listCart');
                Route::get('/getProduct', [ClientCartController::class, 'getProduct'])->name('getProduct');
                Route::post('/store', [ClientCartController::class, 'store'])->name('store');
                Route::post('/remove', [ClientCartController::class, 'remove'])->name('remove');
                Route::post('/updateQuantity', [ClientCartController::class, 'updateQuantity'])->name('updateQuantity');
                Route::post('/changeVariant', [ClientCartController::class, 'changeVariant'])->name('changeVariant');
                Route::post('/totalCart', [ClientCartController::class, 'totalCart'])->name('totalCart');
            });
        });
        // checkout route
        Route::prefix('thanh-toan')->middleware('clientAuth')->name('checkout.')->group(function () {
            Route::get('/', [ClientCheckoutController::class, 'index'])->name('index');
            Route::post('/addDiscount', [ClientCheckoutController::class, 'addDiscount'])->name('addDiscount');
            Route::post('/applyDiscount', [ClientCheckoutController::class, 'applyDiscount'])->name('applyDiscount');
            Route::post('/store', [ClientCheckoutController::class, 'store'])->name('store');
            Route::post('/vnpay/pay', [VnPayController::class, 'pay'])->name('vnpay.pay');
            Route::post('/vnpay/pay-again', [VnPayController::class, 'payAgain'])->name('vnpay.pay-again');
            Route::get('/vnpay/return', [VnPayController::class, 'return'])->name('vnpay.return');
            Route::post('/momo/pay', [MomoController::class, 'pay'])->name('momo.pay');
            Route::post('/momo/pay-again', [MomoController::class, 'payAgain'])->name('momo.pay-again');
            Route::get('/momo/return', [MomoController::class, 'return'])->name('momo.return');
            Route::post('/momo/notify', [MomoController::class, 'notify'])->name('momo.notify');
        });
        // collection route
        Route::prefix('bo-suu-tap')->name('collection.')->group(function () {
            Route::get('/', [ClientCollectionController::class, 'index'])->name('index');
            Route::get('{slug}', [ClientCollectionController::class, 'detail'])->name('detail');
            Route::get('/ajax/get-comments/{slug}', [ClientCommentController::class, 'getComments'])->name('get-comment');
            Route::get('/ajax/get-replies/{comment_id}/{limit}', [ClientCommentController::class, 'getReplies'])->name('get-reply');
            Route::post('/ajax/comment/store', [ClientCommentController::class, 'store'])->name('store');
            Route::post('/ajax/comment/update', [ClientCommentController::class, 'update'])->name('update');
            Route::post('/ajax/comment/remove', [ClientCommentController::class, 'remove'])->name('remove');
        });

        // about route
        Route::prefix('gioi-thieu')->name('about.')->group(function () {
            Route::get('/', [IndexController::class, 'about'])->name('index');
        });
        // post route
        Route::prefix('tin-tuc')->name('post.')->group(function () {
            Route::get('/', [ClientPostController::class, 'index'])->name('index');
            Route::get('/{slug}', [ClientPostController::class, 'detail'])->name('detail');
        });
        // about route
        Route::prefix('lien-he')->name('contact.')->group(function () {
            Route::get('/', [ClientContactController::class, 'contact'])->name('index');
            Route::post('/', [ClientContactController::class, 'send'])->name('send');
        });

        // tìm kiếm
        Route::get('/tim-kiem', [IndexController::class, 'search'])->name('search');

        /* WISHLIST */
        Route::prefix('yeu-thich')->name('wishlist.')->group(function () {
            Route::get('/', [WishlistController::class, 'index'])->name('index');
            Route::get('/ajax/action-wishlist', [WishlistController::class, 'action']);
            Route::get('/ajax/count-wishlist', [WishlistController::class, 'count']);
        });
    });
});
