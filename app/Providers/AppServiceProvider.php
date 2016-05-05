<?php

namespace App\Providers;

use App\Services\DateService;
use App\Services\DateServiceImpl;
use App\Services\DisasterEventQueryBuilder;
use App\Services\DisasterEventQueryBuilderImpl;
use App\Services\MedicalFacilityQueryBuilder;
use App\Services\MedicalFacilityQueryBuilderImpl;
use App\Services\RefugeCampQueryBuilder;
use App\Services\RefugeCampQueryBuilderImpl;
use App\Services\VictimQueryBuilder;
use App\Services\VictimQueryBuilderImpl;
use App\Services\VillageQueryBuilder;
use App\Services\VillageQueryBuilderImpl;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(DateService::class,DateServiceImpl::class);
        $this->app->bind(DisasterEventQueryBuilder::class,DisasterEventQueryBuilderImpl::class);
        $this->app->bind(MedicalFacilityQueryBuilder::class,MedicalFacilityQueryBuilderImpl::class);
        $this->app->bind(RefugeCampQueryBuilder::class,RefugeCampQueryBuilderImpl::class);
        $this->app->bind(VictimQueryBuilder::class,VictimQueryBuilderImpl::class);
        $this->app->bind(VillageQueryBuilder::class,VillageQueryBuilderImpl::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
