<?php

namespace App\Providers;

use App\Domains\Repositories\AiAnalysisLogRepository;
use App\Domains\UseCases\ImageAnalyseUserCase;
use App\Infrastructure\Repositories\IAiAnalysisLogRepository;
use App\Infrastructure\UseCases\IImageAnalyseUserCase;
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
        // Repositories
        $this->app->bind(IAiAnalysisLogRepository::class, AiAnalysisLogRepository::class);

        // UseCases
        $this->app->bind(IImageAnalyseUserCase::class, ImageAnalyseUserCase::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
