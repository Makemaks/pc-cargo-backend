<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\ClientRepositoryInterface;
use App\Repositories\Contracts\JobRepositoryInterface;
use App\Repositories\Contracts\JobCostRepositoryInterface;
use App\Repositories\Contracts\JobRevenueRepositoryInterface;
use App\Repositories\Contracts\JobAdjustmentRepositoryInterface;
use App\Repositories\Contracts\JobTransportRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\JobTransportRepository;
use App\Repositories\JobAdjustmentRepository;
use App\Repositories\JobRevenueRepository;
use App\Repositories\JobCostRepository;
use App\Repositories\ClientRepository;
use App\Repositories\JobRepository;
use App\Repositories\PaymentRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            ClientRepositoryInterface::class,
            ClientRepository::class
        );

        $this->app->bind(
            JobRepositoryInterface::class,
            JobRepository::class
        );

        $this->app->bind(
            JobCostRepositoryInterface::class,
            JobCostRepository::class
        );

        $this->app->bind(
            JobRevenueRepositoryInterface::class,
            JobRevenueRepository::class
        );

        $this->app->bind(
            JobAdjustmentRepositoryInterface::class,
            JobAdjustmentRepository::class
        );

        $this->app->bind(
            JobTransportRepositoryInterface::class,
            JobTransportRepository::class
        );

        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        $this->app->bind(
            PaymentRepositoryInterface::class,
            PaymentRepository::class
        );
    }
}
