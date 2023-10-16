<?php

namespace Laravel\EasyCRUD;

use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class EasyCRUDCommand extends Command
{
    protected $signature = 'crud:start';

    protected $description = 'Install the Easy CRUD.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        try {
            $this->call('migrate');
        } catch (QueryException $e) {
            $this->error($e->getMessage());
            exit();
        }

        $this->info("Publishing the assets");
        $this->call('vendor:publish', ['--provider' => 'Laravel\EasyCRUD\EasyCRUDServiceProvider', '--force' => true]);

        $this->info("Dumping the composer autoload");
        (new Process(['composer dump-autoload']))->run();

        $this->info("Migrating the database tables into your application");
        $this->call('migrate');

        $this->info("Adding the routes");
        $routeFile = base_path('routes/web.php');

        $routes =<<<EOD
        Route::get('easy-crudify', ['uses'   => '\Laravel\EasyCRUD\Controllers\ProcessController@getCRUDify']);
        Route::post('easy-crudify', ['uses'  => '\Laravel\EasyCRUD\Controllers\ProcessController@postCRUDify'])->name('easy-crudify');
        EOD;

        File::append($routeFile, "\n" . $routes);
        $this->info("Successfully installed Easy CRUD");
    }
}
