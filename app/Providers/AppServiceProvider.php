<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        $global = array(
            'forget_punch_num' => 0,
            'login_type'       => 'Guest',
            'all_user'         => [],
        );

        session($global);
    }
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
