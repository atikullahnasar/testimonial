<?php

use atikullahnasar\testimonial\Http\Controllers\TestimonialController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->prefix('beft')->group(function () {
    Route::resource('testimonials', TestimonialController::class);
    Route::post('testimonials/{testimonial}', [TestimonialController::class, 'update'])->name('testimonials.update');

    Route::get('testimonials/{testimonial}/toggle-status', [TestimonialController::class, 'toggleStatus'])->name('testimonials.toggle-status');
});