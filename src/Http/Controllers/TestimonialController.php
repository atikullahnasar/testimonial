<?php

namespace atikullahnasar\testimonial\Http\Controllers;

use App\Http\Controllers\Controller;
use atikullahnasar\testimonial\Http\Requests\StoreTestimonialRequest;
use atikullahnasar\testimonial\Http\Requests\UpdateTestimonialRequest;
use atikullahnasar\testimonial\Models\Testimonial;
use atikullahnasar\testimonial\Services\Testimonials\TestimonialServiceInterface;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TestimonialController extends Controller
{
    public function __construct(
        private readonly TestimonialServiceInterface $testimonialService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $data = $this->testimonialService->getAll();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($item) {
                    return ''; // Actions rendered by JS
                })
                ->editColumn('status', function ($item) {
                    return $item->status;
                })
                ->editColumn('featured', function ($item) {
                    return $item->featured == 1 ? 'Yes' : 'No';
                })
                ->addColumn('thumbnail_image', function ($item) {
                    return $item->thumbnail_image; // Explicitly add the appended attribute
                })
                ->make(true);
        }

        return view('testimonial::testimonials.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTestimonialRequest $request)
    {
        $data = $request->validated();
        $this->testimonialService->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Testimonial created successfully!'
        ], 201);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return $this->testimonialService->findById($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTestimonialRequest $request, $testimonial)
    {
        $data = $request->validated();
        $this->testimonialService->update($testimonial, $data);

        return response()->json([
            'success' => true,
            'message' => 'Testimonial updated successfully!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy( $testimonial)
    {
        $this->testimonialService->delete($testimonial);
        return response()->json(['success' => true, 'message' => 'Testimonial deleted successfully!']);
    }

    public function toggleStatus($testimonial)
    {
        $this->testimonialService->toggleStatus($testimonial);
        return response()->json(['success' => true, 'message' => 'Testimonial status toggled successfully!']);
    }
}
