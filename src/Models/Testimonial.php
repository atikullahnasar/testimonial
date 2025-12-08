<?php

namespace atikullahnasar\testimonial\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;
    protected $table = 'beft_testimonials';

    protected $fillable = [
        'review',
        'name',
        'tag',
        'image',
        'featured',
        'status',
    ];

    protected $appends = ['thumbnail_image'];

    public function getThumbnailImageAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        $directory = dirname($this->image);
        $filename = basename($this->image);
        $thumbnail = "{$directory}/thumb_{$filename}";

        return asset("storage/{$thumbnail}");
    }

    public function toggleStatus(): bool
    {
        $this->status = !$this->status;
        return $this->save();
    }
}
