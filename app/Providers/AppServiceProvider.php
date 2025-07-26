<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

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
        // global
        ViewFacade::share('language', 'farsi');

        // view composer
        ViewFacade::composer(['post.index', 'about'], function (View $view) {
            $view->with('title', 'Posts Title');
            //            $view->with([
            //                'key' => 'value',
            //                'key2' => 'value2',
            //                'key3' => 'value3',
            //            ]);
        });

        Paginator::useBootstrapFive();

        //        Gate::define('update-post', function (User $user, Post $post) {
        //            return $user->id === $post->user_id;
        //        });

        Response::macro('success', function (array $data, string $message = '') {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $data,
            ], 200);
        });
        Response::macro('error', function (string $message, int $status = 400) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'data' => [],
            ], $status);
        });
    }
}
