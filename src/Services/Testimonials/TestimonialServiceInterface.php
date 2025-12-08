<?php

namespace  atikullahnasar\testimonial\Services\Testimonials;

interface TestimonialServiceInterface
{
    public function getAll();
    public function paginate();
    public function findById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function toggleStatus(int $id);
    public function uploadImage($image);
}
