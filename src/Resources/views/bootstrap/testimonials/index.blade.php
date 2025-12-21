<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Testimonials</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<div class="container">
    <div class="d-flex justify-content-between">
        <h3>All Testimonials</h3>

        <button type="button" class="btn btn-primary mb-3" id="showAddEditTestimonials">+ Add New Testimonial</button>
    </div>

    <table id="testimonials-table" class="table table-bordered table-striped">
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

<!-- Add/Edit Testimonial Modal -->
<div class="modal fade" id="addEditTestimonials" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Create New Testimonial</h5>
        <button type="button" class="btn-close" id="closeAddEditTestimonials"></button>
      </div>

      <form id="testimonialForm" enctype="multipart/form-data">
        <input type="hidden" id="id">
        <input type="hidden" id="_method" value="POST">

        <div class="modal-body">

            <div class="mb-3">
                <label for="review" class="form-label">Review *</label>
                <textarea class="form-control" id="review" name="review" rows="3"></textarea>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Name *</label>
                <input type="text" class="form-control" id="name" name="name">
            </div>

            <div class="mb-3">
                <label for="tag" class="form-label">Tag *</label>
                <input type="text" class="form-control" id="tag" name="tag">
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" class="form-control" id="image" name="image">
            </div>

            <div class="form-check">
                <input type="hidden" name="featured" value="0">
                <input type="checkbox" class="form-check-input" id="featured" name="featured" value="1">
                <label class="form-check-label" for="featured">Featured</label>
            </div>

            <div class="form-check mt-2">
                <input type="hidden" name="status" value="0">
                <input type="checkbox" class="form-check-input" id="status" name="status" value="1" checked>
                <label class="form-check-label" for="status">Active</label>
            </div>

        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save Testimonial</button>
            <button type="button" class="btn btn-secondary" id="cancelModal">Cancel</button>
        </div>
      </form>

    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content text-center p-3">
            <p>Are you sure you want to delete this testimonial?</p>
            <button type="button" class="btn btn-danger mt-2 mb-2" id="confirmDeleteBtn">Yes, delete</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {

    const addEditModal = new bootstrap.Modal(document.getElementById('addEditTestimonials'));
    const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));

    let deleteId = null;

    const table = $('#testimonials-table').DataTable({
        ajax: '/beft/testimonials',
        columns: [
            { data: 'review' },
            { data: 'name' },
            { data: 'tag' },
            {
                data: 'image',
                render: img => img ? `<img src="${img}" width="60" height="60" class="rounded" />` : ''
            },
            { data: 'featured'},
            {
                data: 'status',
                render: function(data, type, row) {
                    const statusClass = data == 1 ? 'btn-info' : 'btn-danger';
                    const statusText = data == 1 ? 'Active' : 'Inactive';
                    return `<span class="status-toggle btn btn-sm ${statusClass}" style="cursor:pointer" data-id="${row.id}">${statusText}</span>`;
                }
            },
            {
                data: 'id',
                render: id => `
                <button class="btn btn-sm btn-success edit-btn" data-id="${id}">Edit</button>
                <button class="btn btn-sm btn-danger delete-btn" data-id="${id}">Delete</button>
                `
            }
        ]
    });

    $('#testimonials-table').on('click', '.status-toggle', function() {
        const id = $(this).data('id');
        $.ajax({
            url: `/beft/testimonials/${id}/toggle-status`,
            method: 'GET', // your route is GET
            success: function(res) {
                table.ajax.reload();
            },
            error: function() {
                alert("Something went wrong");
            }
        });
    });

    // Show Add Modal
    $('#showAddEditTestimonials').click(function() {
        $('#testimonialForm')[0].reset();
        $('#_method').val('POST');
        $('#id').val('');
        $('#modalTitle').text("Create New Testimonial");
        addEditModal.show();
    });

    $('#closeAddEditTestimonials, #cancelModal').click(() => addEditModal.hide());

    // Edit Button
    $('#testimonials-table').on('click', '.edit-btn', function() {
        let id = $(this).data('id');

        $.get(`/beft/testimonials/${id}/edit`, function(data) {
            $('#review').val(data.review);
            $('#name').val(data.name);
            $('#tag').val(data.tag);
            $('#featured').prop('checked', data.featured == 1);
            $('#status').prop('checked', data.status == 1);

            $('#id').val(data.id);
            $('#_method').val('PUT');
            $('#modalTitle').text("Edit Testimonial");

            addEditModal.show();
        });
    });

    // Delete Button
    $('#testimonials-table').on('click', '.delete-btn', function() {
        deleteId = $(this).data('id');
        confirmationModal.show();
    });

    $('#confirmDeleteBtn').click(function() {
        $.ajax({
            url: `/beft/testimonials/${deleteId}`,
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: () => {
                table.ajax.reload();
                confirmationModal.hide();
            }
        });
    });

    // Save Form
    $('#testimonialForm').submit(function(e) {
        e.preventDefault();

        let id = $('#id').val();
        let url = id ? `/beft/testimonials/${id}` : '/beft/testimonials';
        let formData = new FormData(this);

        $.ajax({
            url,
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: () => {
                table.ajax.reload();
                addEditModal.hide();
            }
        });
    });

});
</script>

</body>
</html>
