<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Issued Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2>Issued Books</h2>
    <table class="table table-bordered" id="issuedBooksTable">
        <thead class="table-dark">
        <tr>
            <th>Member ID</th>
            <th>Book Title</th>
            <th>Author</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script>
$(document).ready(function(){
    function loadIssuedBooks(){
        $.ajax({
            url: '../process/getIssuedBooks.php',
            method: 'GET',
            success: function(data){
                $('#issuedBooksTable tbody').html(data);
            }
        });
    }

    loadIssuedBooks();
});
</script>
</body>
</html>
