<?php

namespace App\Providers;

use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
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
        View::composer('panels.penjahit-sidebar', function ($view) {
            if (Auth::check() && Auth::user()->hasRole('penjahit')) {
                $unreadChatCount = Conversation::where('penjahit_id', Auth::id())
                    ->withCount(['messages' => function ($q) {
                        $q->whereNull('read_at')->where('sender_id', '!=', Auth::id());
                    }])->get()->sum('messages_count');
                $view->with('unreadChatCount', $unreadChatCount);
            }
        });
    }
}
