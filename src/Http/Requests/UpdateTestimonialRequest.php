<?php

namespace atikullahnasar\testimonial\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTestimonialRequest extends StoreTestimonialRequest
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
        $rules = parent::rules();
        $rules['image'] = 'nullable|image|max:2048';
        return $rules;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'featured' => (int) $this->input('featured', 0),
            'status' => (int) $this->input('status', 0),
        ]);
    }
}
