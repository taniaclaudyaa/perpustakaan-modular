<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Modules\Circulation\Events\BookBorrowed;
use Modules\Circulation\Listeners\LogBookBorrowed;
use Modules\Shared\Contracts\BookServiceInterface;
use Modules\Shared\Contracts\MemberServiceInterface;
use Modules\Catalog\Services\BookService;
use Modules\Membership\Services\MemberService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // kalau sudah ada bind lain dari modul sebelumnya, biarkan di sini

        $this->app->bind(BookServiceInterface::class, BookService::class);
        $this->app->bind(MemberServiceInterface::class, MemberService::class);
    }

    public function boot(): void
    {
        Event::listen(BookBorrowed::class, LogBookBorrowed::class);
    }
}