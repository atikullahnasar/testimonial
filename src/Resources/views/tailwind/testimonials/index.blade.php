<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Testimonials</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" />

    <!-- CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="bg-gray-100 p-6">

<div class="max-w-7xl mx-auto bg-white p-6 rounded shadow">

    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-semibold">All Testimonials</h3>
        <button id="showAddEditTestimonials" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Add New Testimonial
        </button>
    </div>

    <table id="testimonials-table" class="display w-full">
        <thead>
            <tr>
                <th>Review</th>
                <th>Name</th>
                <th>Tag</th>
                <th>Image</th>
                <th>Featured</th>
                <th>Status</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
    </table>
</div>

<!-- Add/Edit Modal -->
<div id="addEditTestimonials" class="fixed inset-0 bg-black/50 hidden items-center justify-center">
    <div class="bg-white w-full max-w-2xl rounded shadow-lg">
        <div class="flex justify-between items-center px-6 py-4 border-b">
            <h5 class="font-semibold" id="modalTitle">Create New Testimonial</h5>
            <button id="closeAddEditTestimonials" class="text-gray-500 hover:text-black">&times;</button>
        </div>

        <form id="testimonialForm" enctype="multipart/form-data" class="p-6 space-y-4">
            <input type="hidden" id="id">
            <input type="hidden" id="_method" value="POST">

            <div>
                <label class="block font-medium mb-1">Review *</label>
                <textarea id="review" name="review" rows="3" class="w-full border rounded p-2"></textarea>
            </div>

            <div>
                <label class="block font-medium mb-1">Name *</label>
                <input type="text" id="name" name="name" class="w-full border rounded p-2">
            </div>

            <div>
                <label class="block font-medium mb-1">Tag *</label>
                <input type="text" id="tag" name="tag" class="w-full border rounded p-2">
            </div>

            <div>
                <label class="block font-medium mb-1">Image</label>
                <input type="file" id="image" name="image" class="w-full border rounded p-2">
            </div>

            <div class="flex items-center gap-2">
                <input type="hidden" name="featured" value="0">
                <input type="checkbox" id="featured" name="featured" value="1">
                <label for="featured">Featured</label>
            </div>

            <div class="flex items-center gap-2 mt-2">
                <input type="hidden" name="status" value="0">
                <input type="checkbox" id="status" name="status" value="1" checked>
                <label for="status">Active</label>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Save Testimonial
                </button>
                <button type="button" id="cancelModal" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="confirmationModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center">
    <div class="bg-white p-6 rounded shadow text-center w-full max-w-sm">
        <p class="mb-4">Are you sure you want to delete this testimonial?</p>
        <button id="confirmDeleteBtn" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 mb-2">
            Yes, delete
        </button>
        <button id="cancelDelete" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">
            Cancel
        </button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>

<script>
$(document).ready(function() {

    const addEditModal = $('#addEditTestimonials');
    const confirmationModal = $('#confirmationModal');
    let deleteId = null;

    function openModal(modal) {
        modal.removeClass('hidden').addClass('flex');
    }

    function closeModal(modal) {
        modal.addClass('hidden').removeClass('flex');
    }

    const table = $('#testimonials-table').DataTable({
        ajax: '/beft/testimonials',
        columns: [
            { data: 'review' },
            { data: 'name' },
            { data: 'tag' },
            { data: 'image',
              render: img => img ? `<img src="${img}" class="w-16 h-16 rounded"/>` : '' },
            { data: 'featured' },
            { data: 'status',
              render: function(data, type, row) {
                  const cls = data == 1 ? 'bg-blue-500' : 'bg-red-500';
                  const text = data == 1 ? 'Active' : 'Inactive';
                  return `<span class="px-2 py-1 text-white rounded cursor-pointer status-toggle ${cls}" data-id="${row.id}">${text}</span>`;
              }
            },
            { data: 'id',
              render: id => `
                <button class="bg-green-600 text-white px-2 py-1 rounded edit-btn" data-id="${id}">Edit</button>
                <button class="bg-red-600 text-white px-2 py-1 rounded delete-btn" data-id="${id}">Delete</button>
              `
            }
        ]
    });

    // Status toggle
    $('#testimonials-table').on('click', '.status-toggle', function() {
        const id = $(this).data('id');
        $.get(`/beft/testimonials/${id}/toggle-status`, () => table.ajax.reload()).fail(() => alert("Something went wrong"));
    });

    // Show Add Modal
    $('#showAddEditTestimonials').click(() => {
        $('#testimonialForm')[0].reset();
        $('#id').val('');
        $('#_method').val('POST');
        $('#modalTitle').text('Create New Testimonial');
        openModal(addEditModal);
    });

    $('#closeAddEditTestimonials, #cancelModal').click(() => closeModal(addEditModal));
    $('#cancelDelete').click(() => closeModal(confirmationModal));

    // Edit testimonial
    $('#testimonials-table').on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        $.get(`/beft/testimonials/${id}/edit`, function(data) {
            $('#review').val(data.review);
            $('#name').val(data.name);
            $('#tag').val(data.tag);
            $('#featured').prop('checked', data.featured == 1);
            $('#status').prop('checked', data.status == 1);
            $('#id').val(data.id);
            $('#_method').val('PUT');
            $('#modalTitle').text('Edit Testimonial');
            openModal(addEditModal);
        });
    });

    // Delete testimonial
    $('#testimonials-table').on('click', '.delete-btn', function() {
        deleteId = $(this).data('id');
        openModal(confirmationModal);
    });

    $('#confirmDeleteBtn').click(() => {
        $.ajax({
            url: `/beft/testimonials/${deleteId}`,
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: () => { table.ajax.reload(); closeModal(confirmationModal); }
        });
    });

    // Save form
    $('#testimonialForm').submit(function(e) {
        e.preventDefault();
        const id = $('#id').val();
        const url = id ? `/beft/testimonials/${id}` : '/beft/testimonials';
        const formData = new FormData(this);

        $.ajax({
            url,
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: () => { table.ajax.reload(); closeModal(addEditModal); }
        });
    });

});
</script>
</body>
</html>
