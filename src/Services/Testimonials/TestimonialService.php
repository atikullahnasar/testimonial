<?php

namespace atikullahnasar\testimonial\Services\Testimonials;

use  atikullahnasar\testimonial\Repositories\Testimonials\TestimonialRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class TestimonialService implements TestimonialServiceInterface
{

    public function __construct(protected readonly TestimonialRepositoryInterface $testimonialRepository)
    {
    }

    public function getAll()
    {
        return $this->testimonialRepository->all();
    }

    public function paginate()
    {
        return $this->testimonialRepository->paginate(null, 10, ['*'], [], ['status' => 1]);
    }

    public function findById(int $id)
    {
        return $this->testimonialRepository->find($id);
    }

    public function create(array $data)
    {
        if (isset($data['image'])) {
            $data['image'] = $this->uploadImage($data['image']);
        }
        return $this->testimonialRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        $testimonial = $this->testimonialRepository->find($id);
        if (isset($data['image'])) {
            if ($testimonial->image) {
                Storage::disk('public')->delete($testimonial->image);
                Storage::disk('public')->delete(dirname($testimonial->image) . '/thumb_' . basename($testimonial->image));
            }
            $data['image'] = $this->uploadImage($data['image']);
        } else {
            $data['image'] = $testimonial->image;
        }

        return $this->testimonialRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        $testimonial = $this->testimonialRepository->find($id);
        if ($testimonial->image) {
            Storage::disk('public')->delete($testimonial->image);
            Storage::disk('public')->delete(dirname($testimonial->image) . '/thumb_' . basename($testimonial->image));
        }
        return $this->testimonialRepository->delete($id);
    }

    public function toggleStatus(int $id)
    {
        $testimonial = $this->testimonialRepository->find($id);
        $testimonial->toggleStatus();
        return $testimonial;
    }

    public function uploadImage($image)
    {
        $originalPath = $image->store('testimonials', 'public');

        $filename = basename($originalPath);
        $directory = dirname($originalPath);
        $thumbnailPath = storage_path("app/public/{$directory}/thumb_{$filename}");

        Image::read($image->getRealPath())
            ->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->save($thumbnailPath);

        return $originalPath;
    }
}
