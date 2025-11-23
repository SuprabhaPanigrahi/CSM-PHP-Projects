<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h2>Manage Books</h2>

        <!-- Add Book Form -->
        <form id="addBookForm" class="mb-4">
            <div class="mb-3">
                <input type="text" name="title" class="form-control" placeholder="Book Title" required>
            </div>
            <div class="mb-3">
                <input type="text" name="author" class="form-control" placeholder="Author" required>
            </div>
            <div class="mb-3">
                <label class="form-label me-3">Availability:</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="availability" id="availableAdd" value="available" checked>
                    <label class="form-check-label" for="availableAdd">Available</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="availability" id="notAvailableAdd" value="unavailable">
                    <label class="form-check-label" for="notAvailableAdd">Not Available</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Add Book</button>
        </form>

        <!-- Books Table -->
        <table class="table table-bordered" id="booksTable">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Availability</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Books will be loaded here via AJAX -->
            </tbody>
        </table>
    </div>

    <!-- Update Book Modal -->
    <div class="modal fade" id="updateBookModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="updateBookForm" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Book</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="updateBookId">
                    <div class="mb-3">
                        <input type="text" name="title" id="updateBookTitle" class="form-control" placeholder="Book Title" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="author" id="updateBookAuthor" class="form-control" placeholder="Author" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label me-3">Availability:</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="availability" id="availableUpdate" value="available">
                            <label class="form-check-label" for="availableUpdate">Available</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="availability" id="notAvailableUpdate" value="unavailable">
                            <label class="form-check-label" for="notAvailableUpdate">Not Available</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update Book</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {

            // Load books into table
            function loadBooks() {
                $.ajax({
                    url: '../process/getBooks.php',
                    method: 'GET',
                    success: function(data) {
                        $('#booksTable tbody').html(data);
                    }
                });
            }

            loadBooks();

            // Add Book
            $('#addBookForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: '../process/addBook.php',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        alert(response);
                        $('#addBookForm')[0].reset();
                        loadBooks();
                    }
                });
            });

            // Delete Book
            $(document).on('click', '.delete-book', function() {
                let id = $(this).data('id');
                if (confirm('Delete this book?')) {
                    $.ajax({
                        url: '../process/removeBook.php',
                        method: 'POST',
                        data: { id: id },
                        success: function(response) {
                            alert(response);
                            loadBooks();
                        }
                    });
                }
            });

            // Show Update Modal
            $(document).on('click', '.edit-book', function() {
                let id = $(this).data('id');
                let title = $(this).data('title');
                let author = $(this).data('author');
                let availability = $(this).data('availability');

                $('#updateBookId').val(id);
                $('#updateBookTitle').val(title);
                $('#updateBookAuthor').val(author);

                if (availability === 'available') {
                    $('#availableUpdate').prop('checked', true);
                } else {
                    $('#notAvailableUpdate').prop('checked', true);
                }

                new bootstrap.Modal(document.getElementById('updateBookModal')).show();
            });

            // Update Book
            $('#updateBookForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: '../process/updateBook.php',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        alert(response);
                        loadBooks();
                        bootstrap.Modal.getInstance(document.getElementById('updateBookModal')).hide();
                    }
                });
            });

        });
    </script>
</body>

</html>
