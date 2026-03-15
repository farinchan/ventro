<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $adminVerticalMenuJson = file_get_contents(base_path('resources/menu/adminVerticalMenu.json'));
        $adminVerticalMenuData = json_decode($adminVerticalMenuJson);
        $verticalMenuJson = file_get_contents(base_path('resources/menu/verticalMenu.json'));
        $verticalMenuData = json_decode($verticalMenuJson);
        $horizontalMenuJson = file_get_contents(base_path('resources/menu/horizontalMenu.json'));
        $horizontalMenuData = json_decode($horizontalMenuJson);

        // Share all menu data to all views.
        $this->app->make('view')->share('menuData', [
            'adminVerticalMenu' => $adminVerticalMenuData,
            'verticalMenu' => $verticalMenuData,
            'horizontalMenu' => $horizontalMenuData,
        ]);
    }
}
