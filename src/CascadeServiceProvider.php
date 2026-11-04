<?php

namespace Fayne\Cascade;

use Illuminate\Support\ServiceProvider;

class CascadeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/filament-cascade.php', 'filament-cascade');
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'filament-cascade');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'filament-cascade');
        $this->publishes([__DIR__ . '/../config/filament-cascade.php' => config_path('filament-cascade.php')], 'filament-cascade-config');
    }
}
