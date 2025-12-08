<?php

namespace atikullahnasar\testimonial\Repositories\Testimonials;

use atikullahnasar\testimonial\Models\Testimonial;
use atikullahnasar\testimonial\Repositories\BaseRepository;

class TestimonialRepository extends BaseRepository implements TestimonialRepositoryInterface
{
    public function __construct(Testimonial $model)
    {
        parent::__construct($model);
    }
}
