<?php

namespace Laravel\EasyCRUD;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class EasyCRUDServiceProvider extends ServiceProvider
{
    protected bool $defer = false;

    public function boot(Router $router): void
    {
        $this->publishes([
            __DIR__ . '/../publish/resources/' => base_path('resources'),
        ]);

        $this->publishes([
            __DIR__ . '/../publish/config/easy-crud.php' => config_path('easy-crud.php'),
        ]);

        $this->loadViewsFrom(__DIR__ . '/views', 'easy-crud');

        $menus = [];
        if (File::exists(base_path('resources/easy-crud/menus.json'))) {
            $menus = json_decode(File::get(base_path('resources/easy-crud/menus.json')));
            view()->share('EasyCRUDifyMenus', $menus);
        }
    }

    public function register(): void
    {
        $this->commands(
            'Laravel\EasyCRUD\EasyCRUDCommand',
            'Laravel\EasyCRUD\Commands\CrudCommand',
            'Laravel\EasyCRUD\Commands\CrudControllerCommand',
            'Laravel\EasyCRUD\Commands\CrudModelCommand',
            'Laravel\EasyCRUD\Commands\CrudMigrationCommand',
            'Laravel\EasyCRUD\Commands\CrudViewCommand',
            'Laravel\EasyCRUD\Commands\CrudLangCommand',
            'Laravel\EasyCRUD\Commands\CrudApiCommand',
            'Laravel\EasyCRUD\Commands\CrudApiControllerCommand'
        );


    }
}
