<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Testimonials</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" />

    <!-- Custom Styles for DataTables -->
    <style>
        .dataTables_wrapper .dataTables_filter input {
            @apply border border-gray-300 rounded-md px-3 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500;
        }
        .dataTables_wrapper .dataTables_length select {
            @apply border border-gray-300 rounded-md px-3 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500;
        }
        .dataTables_paginate .paginate_button {
            @apply px-3 py-1 ml-1 rounded border border-gray-300 bg-white text-gray-700 hover:bg-gray-100;
        }
        .dataTables_paginate .paginate_button.current {
            @apply bg-blue-500 text-white hover:bg-blue-600 border-blue-500;
        }
        .dataTables_paginate .paginate_button.disabled {
            @apply opacity-50 cursor-not-allowed;
        }
    </style>
</head>

<body class="bg-gray-100 p-6">

<div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-2xl font-bold text-gray-800">All Testimonials</h3>
        <button id="showAddEditTestimonials" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-300 ease-in-out flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add New Testimonial
        </button>
    </div>

    <table id="testimonials-table" class="display w-full">
        <thead class="bg-gray-50 border-b-2 border-gray-200">
            <tr>
                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Review</th>
                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tag</th>
                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Featured</th>
                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="p-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
            </tr>
        </thead>
    </table>
</div>

<!-- Toast Notification -->
<div id="toast-container" class="fixed top-4 right-4 z-50 transition-opacity duration-300 opacity-0 pointer-events-none">
    <div id="showToast" class="bg-green-500 text-white font-medium py-3 px-5 rounded-lg shadow-lg flex items-center justify-between">
        <span id="toastMessage"></span>
        <button onclick="hideToast()" class="ml-4 text-white hover:text-gray-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="addEditTestimonials" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto w-full z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="relative top-10 mx-auto border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-lg bg-white flex flex-col max-h-[90vh]">

        <div class="flex justify-between items-center p-5 border-b flex-shrink-0">
            <h5 class="text-xl font-bold text-gray-800" id="modalTitle">Create New Testimonial</h5>
            <button type="button" onclick="closeModal('addEditTestimonials')" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-700 mb-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form id="testimonialForm" enctype="multipart/form-data" class="flex flex-col flex-grow  overflow-y-auto">

            <div class="p-5 space-y-4 overflow-y-auto flex-grow">
                <input type="hidden" id="id" name="id">
                <input type="hidden" id="_method" name="_method" value="POST">
                <div>
                    <label for="review" class="block text-gray-700 text-sm font-bold mb-2">Review *</label>
                    <textarea id="review" name="review" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                    <p class="text-red-500 text-xs italic mt-1" id="error-review"></p>
                </div>
                <div>
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name *</label>
                    <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <p class="text-red-500 text-xs italic mt-1" id="error-name"></p>
                </div>
                <div>
                    <label for="tag" class="block text-gray-700 text-sm font-bold mb-2">Tag *</label>
                    <input type="text" id="tag" name="tag" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <p class="text-red-500 text-xs italic mt-1" id="error-tag"></p>
                </div>
                <div>
                    <label for="image" class="block text-gray-700 text-sm font-bold mb-2">Image</label>
                    <input type="file" id="image" name="image" accept="image/*" class="w-full border rounded p-2">
                    <div id="imagePreview" class="mt-2"></div>
                    <p class="text-red-500 text-xs italic mt-1" id="error-image"></p>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="featured" name="featured" value="1" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="featured" class="ml-2 block text-sm text-gray-700">Featured</label>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="status" name="status" value="1" checked class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="status" class="ml-2 block text-sm text-gray-700">Active</label>
                </div>
            </div>

            <div class="flex justify-end gap-3 p-5 border-t flex-shrink-0 bg-gray-50">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300" id="submitBtn">
                    <span class="submit-text">Save Testimonial</span>
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
                <button type="button" onclick="closeModal('addEditTestimonials')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="confirmationModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-2">Are you sure?</h3>
            <p class="text-sm text-gray-500 mb-4">Do you really want to delete this testimonial? This action cannot be undone.</p>
            <div class="flex justify-center gap-2">
                <button type="button" id="confirmDeleteBtn" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300">
                    <span class="delete-text">Yes, delete</span>
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
                <button type="button" onclick="closeModal('confirmationModal')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>

<script>
// --- Global Utility Functions ---
function showToast(message, type = 'success') {
    const toastContainer = $('#toast-container');
    const toastElement = $('#showToast');
    const toastMessage = $('#toastMessage');

    toastMessage.text(message);
    toastElement.removeClass('bg-green-500 bg-red-500').addClass(type === 'success' ? 'bg-green-500' : 'bg-red-500');

    toastContainer.removeClass('opacity-0 pointer-events-none').addClass('opacity-100');

    setTimeout(hideToast, 3000);
}

function hideToast() {
    const toastContainer = $('#toast-container');
    toastContainer.removeClass('opacity-100').addClass('opacity-0');
    setTimeout(() => {
        toastContainer.addClass('pointer-events-none');
    }, 300);
}

function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

 $(document).ready(function() {
    // --- Cache DOM Elements ---
    const testimonialForm = $('#testimonialForm');
    const addEditModal = $('#addEditTestimonials');
    const confirmationModal = $('#confirmationModal');
    const submitBtn = $('#submitBtn');
    const confirmDeleteBtn = $('#confirmDeleteBtn');
    let deleteId = null;

    // --- Setup AJAX ---
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // --- Initialize DataTables ---
    const table = $('#testimonials-table').DataTable({
        ajax: '/beft/testimonials',
        columns: [
            { data: 'review', name: 'review' },
            { data: 'name', name: 'name' },
            { data: 'tag', name: 'tag' },
            { data: 'image', name: 'image',
              render: img => img ? `<img src="${img}" class="w-16 h-16 rounded object-cover shadow-sm"/>` : '<span class="text-gray-400">No Image</span>' },
            { data: 'featured', name: 'featured',
              render: d => d ? '<span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Featured</span>' : '<span class="text-gray-400">-</span>'
            },
            { data: 'status', name: 'status',
              render: function(data, type, row) {
                  const cls = data == 1 ? 'bg-blue-500 hover:bg-blue-600' : 'bg-red-500 hover:bg-red-600';
                  const text = data == 1 ? 'Active' : 'Inactive';
                  return `<span class="px-2 py-1 text-white rounded-full text-xs cursor-pointer status-toggle ${cls}" data-id="${row.id}">${text}</span>`;
              }
            },
            { data: 'id', name: 'id',
              render: id => `
                <div class="flex justify-center gap-2">
                    <button class="bg-green-500 hover:bg-green-600 text-white font-bold py-1 px-3 rounded text-sm edit-btn" data-id="${id}">Edit</button>
                    <button class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded text-sm delete-btn" data-id="${id}">Delete</button>
                </div>
              `
            }
        ]
    });

    // --- Local Utility Functions ---
    function setLoadingState(button, loading = true) {
        const textSpan = button.find('span').first();
        const spinner = button.find('svg');
        if (loading) {
            button.prop('disabled', true);
            textSpan.addClass('hidden');
            spinner.removeClass('hidden');
        } else {
            button.prop('disabled', false);
            textSpan.removeClass('hidden');
            spinner.addClass('hidden');
        }
    }

    function resetForm() {
        testimonialForm[0].reset();
        $('#id').val('');
        $('#_method').val('POST');
        $('#modalTitle').text('Create New Testimonial');
        $('#imagePreview').html('');
        testimonialForm.find('p[id^="error-"]').text('');
        testimonialForm.find('input, select, textarea').removeClass('border-red-500');
    }

    // --- Event Handlers ---

    // Status toggle
    $('#testimonials-table').on('click', '.status-toggle', function() {
        const id = $(this).data('id');
        $.get(`/beft/testimonials/${id}/toggle-status`, () => table.ajax.reload())
            .fail(() => showToast("Something went wrong while toggling status.", 'error'));
    });

    // Show Add Modal
    $('#showAddEditTestimonials').on('click', function() {
        resetForm();
        openModal('addEditTestimonials');
    });

    // Edit testimonial
    $('#testimonials-table').on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        resetForm();
        $('#modalTitle').text('Edit Testimonial');

        $.get(`/beft/testimonials/${id}/edit`, function(data) {
            $('#review').val(data.review);
            $('#name').val(data.name);
            $('#tag').val(data.tag);
            $('#featured').prop('checked', data.featured == 1);
            $('#status').prop('checked', data.status == 1);
            $('#id').val(data.id);
            $('#_method').val('PUT');
            if (data.image) {
                $('#imagePreview').html(`<img src="${data.image}" class="h-20 w-20 rounded object-cover shadow-sm mt-2"><p class="text-xs text-gray-500">Current Image</p>`);
            }
            openModal('addEditTestimonials');
        }).fail(() => {
            showToast('Error fetching testimonial data.', 'error');
        });
    });

    // Delete testimonial
    $('#testimonials-table').on('click', '.delete-btn', function() {
        deleteId = $(this).data('id');
        openModal('confirmationModal');
    });

    $('#confirmDeleteBtn').on('click', function() {
        setLoadingState(confirmDeleteBtn, true);
        $.ajax({
            url: `/beft/testimonials/${deleteId}`,
            method: 'DELETE',
            success: function(data) {
                table.ajax.reload();
                closeModal('confirmationModal');
                showToast(data.message || 'Testimonial deleted successfully.', 'success');
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || 'An error occurred while deleting the testimonial.';
                showToast(message, 'error');
            },
            complete: function() {
                setLoadingState(confirmDeleteBtn, false);
            }
        });
    });

    // Form submission
    testimonialForm.on('submit', function(e) {
        e.preventDefault();
        setLoadingState(submitBtn, true);

        const id = $('#id').val();
        const url = id ? `/beft/testimonials/${id}` : '/beft/testimonials';
        const formData = new FormData(this);

        $.ajax({
            url: url,
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                table.ajax.reload();
                closeModal('addEditTestimonials');
                showToast(data.message || 'Testimonial saved successfully.', 'success');
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    testimonialForm.find('input, select, textarea').removeClass('border-red-500');
                    testimonialForm.find('p[id^="error-"]').text('');

                    Object.keys(errors).forEach(function(field) {
                        const input = $(`[name="${field}"]`);
                        input.addClass('border-red-500');
                        $(`#error-${field}`).text(errors[field][0]);
                    });
                } else {
                    const message = xhr.responseJSON?.message || 'An error occurred.';
                    showToast(message, 'error');
                }
            },
            complete: function() {
                setLoadingState(submitBtn, false);
            }
        });
    });

});
</script>
</body>
</html>