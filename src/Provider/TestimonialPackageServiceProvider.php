<?php

namespace atikullahnasar\testimonial\Provider;

use atikullahnasar\testimonial\Repositories\Testimonials\TestimonialRepository;
use atikullahnasar\testimonial\Repositories\Testimonials\TestimonialRepositoryInterface;
use atikullahnasar\testimonial\Services\Testimonials\TestimonialService;
use atikullahnasar\testimonial\Services\Testimonials\TestimonialServiceInterface;
use Illuminate\Support\ServiceProvider;

class TestimonialPackageServiceProvider extends ServiceProvider
{

    public function boot()
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'testimonial');

        // Publish migrations
        $this->publishes([__DIR__ . '/../Database/migrations' => database_path('migrations'),
        ], 'testimonial-migrations');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');
    }

    public function register()
    {
        $this->app->bind(TestimonialRepositoryInterface::class, TestimonialRepository::class);
        $this->app->bind(TestimonialServiceInterface::class, TestimonialService::class);
    }
}
