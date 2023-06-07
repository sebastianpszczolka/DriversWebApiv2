<?php

namespace App\Providers;

use App\Repositories\Installation\FilesInstallationStatusRepository;
use App\Repositories\Installation\InstallationStatusRepository;
use App\Repositories\Logs\FilesLogsRepository;
use App\Repositories\Logs\LogsRepository;
use App\Repositories\Storage\RedisStorageRepository;
use App\Repositories\Storage\StorageRepository;
use App\Repositories\Storage\v1\RedisStorageRepositoryApl;
use App\Repositories\Storage\v1\StorageRepositoryApl;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(StorageRepository::class, RedisStorageRepository::class);
        $this->app->bind(LogsRepository::class, FilesLogsRepository::class);
        $this->app->bind(InstallationStatusRepository::class, FilesInstallationStatusRepository::class);
        $this->app->bind(StorageRepositoryApl::class, RedisStorageRepositoryApl::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
//        DB::listen(function ($query) {
//            Log::info(
//                $query->sql,
//                $query->bindings,
//                $query->time
//            );
//        });
        //
    }
}
