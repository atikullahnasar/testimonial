<?php

namespace atikullahnasar\testimonial\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTestimonialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'review' => 'required|string|min:4',
            'name' => 'required|string|max:255',
            'tag' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'featured' => 'nullable|integer',
            'status' => 'nullable|integer',
        ];
    }
    protected function prepareForValidation(): void
    {
        $this->merge([
            'featured' => (int) $this->input('featured', 0),
            'status' => (int) $this->input('status', 0),
        ]);
    }
}
