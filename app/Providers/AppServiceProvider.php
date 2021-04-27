<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 打印慢于500ms/0.5s的sql查询
        DB::listen(function ($query) {
            $sql = $query->sql;
            $time = $query->time;
            if ($time > 500) {
                logger('slow_mysql:', ['url' => url()->current(), 'time' => $time . 'ms', 'sql' => $sql, 'ip' => request()->ip()]);
            }
        });
    }
}
