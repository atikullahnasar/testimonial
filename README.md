Testimonial Package:
A simple and customizable Laravel package that provides testimonial management with ready-to-use migrations and routes.

Installation & Setup

Follow the steps below to install and configure the package in your Laravel application:

Step 1: Install the Package
composer require atikullahnasar/testimonial:dev-main

Step 2: Publish the Migrations
php artisan vendor:publish --provider="atikullahnasar\testimonial\Provider\TestimonialPackageServiceProvider" --tag=testimonial-migrations

Step 3: Run Migrations
php artisan migrate

Step 4 (Final): Access the Testimonials Page

After installation, visit the following URL to view testimonials:
/beft/testimonials
Example:
http://example.com/beft/testimonials

