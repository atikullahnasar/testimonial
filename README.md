Step-1: composer require atikullahnasar/testimonial:dev-main
Step 2: php artisan vendor:publish --provider="atikullahnasar\testimonial\Provider\TestimonialPackageServiceProvider" --tag=testimonial-migrations
Step 3: php artisan migrate
