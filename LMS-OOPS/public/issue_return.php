<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Issue / Return Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h2>Issue / Return Books</h2>

        <!-- Issue Book Form -->
        <h4 class="mt-4">Issue Book</h4>
        <form id="issueForm" class="mb-4">
            <div class="mb-3">
                <select name="member_id" class="form-select" required>
                    <option value="">Select Member</option>
                </select>
            </div>
            <div class="mb-3">
                <select name="book_id" class="form-select" required>
                    <option value="">Select Book</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Issue Book</button>
        </form>

        <!-- Return Book Form -->
        <h4 class="mt-4">Return Book</h4>
        <form id="returnForm" class="mb-4">
            <div class="mb-3">
                <select name="member_id" class="form-select" required>
                    <option value="">Select Member</option>
                </select>
            </div>
            <div class="mb-3">
                <select name="book_id" class="form-select" required>
                    <option value="">Select Book</option>
                </select>
            </div>
            <button type="submit" class="btn btn-warning">Return Book</button>
        </form>

        <!-- Issued Books Table -->
        <h4 class="mt-5">Currently Issued Books</h4>
        <table class="table table-bordered" id="issuedBooksTable">
            <thead class="table-dark">
                <tr>
                    <th>Member</th>
                    <th>Book</th>
                    <th>Author</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Populated by AJAX -->
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function () {

            // Load Members and Books into dropdowns
            function loadDropdowns() {
                $.get('../process/getMembers.php?for_dropdown', function (data) {
                    $('#issueForm select[name="member_id"]').html('<option value="">Select Member</option>' + data);
                    $('#returnForm select[name="member_id"]').html('<option value="">Select Member</option>' + data);
                });

                $.get('../process/getBooks.php?for_dropdown', function (data) {
                    $('#issueForm select[name="book_id"]').html('<option value="">Select Book</option>' + data);
                    $('#returnForm select[name="book_id"]').html('<option value="">Select Book</option>' + data);
                });
            }

            // Load Issued Books Table
            function loadIssuedBooks() {
                $.get('../process/getIssuedBooks.php', function (data) {
                    $('#issuedBooksTable tbody').html(data);
                });
            }

            // Initial load
            loadDropdowns();
            loadIssuedBooks();

            // Issue Book
            $('#issueForm').submit(function (e) {
                e.preventDefault();
                $.post('../process/issueBook.php', $(this).serialize(), function (response) {
                    alert(response);
                    $('#issueForm')[0].reset();
                    loadDropdowns();
                    loadIssuedBooks();
                }).fail(function () {
                    alert('Error issuing book.');
                });
            });

            // Return Book
            $('#returnForm').submit(function (e) {
                e.preventDefault();
                $.post('../process/returnBook.php', $(this).serialize(), function (response) {
                    alert(response);
                    $('#returnForm')[0].reset();
                    loadDropdowns();
                    loadIssuedBooks();
                }).fail(function () {
                    alert('Error returning book.');
                });
            });

            // Optional: Delete issued record directly from table
            $(document).on('click', '.return-issued-book', function () {
                let issue_id = $(this).data('id');
                if (confirm('Mark this book as returned?')) {
                    $.post('../process/returnBook.php', { issue_id: issue_id }, function (response) {
                        alert(response);
                        loadDropdowns();
                        loadIssuedBooks();
                    });
                }
            });

        });
    </script>
</body>
</html>
