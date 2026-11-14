<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer('layouts.navbar_pembeli', function ($view) {
            if (auth()->check()) {
                $userId = auth()->id();
                $cartCount = \App\Models\Pembeli\Keranjang_pembeli::where('user_id', $userId)->count();
                $wishlistCount = \App\Models\Pembeli\Wishlist_pembeli::where('user_id', $userId)->count();
                $chatUnreadCount = \App\Models\Message::whereHas('conversation', function ($q) use ($userId) {
                        $q->where('buyer_id', $userId);
                    })
                    ->where('sender_id', '!=', $userId)
                    ->whereNull('read_at')
                    ->count();

                $view->with([
                    'cartCount' => $cartCount,
                    'wishlistCount' => $wishlistCount,
                    'chatUnreadCount' => $chatUnreadCount,
                ]);
            }
        });

        \Illuminate\Support\Facades\View::composer('SellerView.layouts.sidebar_seller', function ($view) {
            if (auth()->check()) {
                $sellerId = auth()->id();
                $sellerChatUnreadTotal = \App\Models\Message::whereHas('conversation', function ($q) use ($sellerId) {
                        $q->where('seller_id', $sellerId);
                    })
                    ->where('sender_id', '!=', $sellerId)
                    ->whereNull('read_at')
                    ->count();

                $view->with('sellerChatUnreadTotal', $sellerChatUnreadTotal);
            }
        });
    }
}
