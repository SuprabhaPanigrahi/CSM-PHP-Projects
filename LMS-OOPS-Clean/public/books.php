<!DOCTYPE html>
<html>
<head>
<title>Manage Books</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</head>

<body>
<div class="container mt-4">
<h3>Manage Books</h3>

<!-- ADD BOOK FORM -->
<form id="addBookForm" class="row mt-3">
    <div class="col-md-3"><input type="text" name="title" placeholder="Title" class="form-control" required></div>
    <div class="col-md-3"><input type="text" name="author" placeholder="Author" class="form-control" required></div>

    <!-- AVAILABILITY RADIO -->
    <div class="col-md-3">
        <label class="form-label">Availability</label><br>
        <input type="radio" name="availability" value="available" checked> Available
        <input type="radio" name="availability" value="unavailable" class="ms-3"> Unavailable
    </div>

    <div class="col-md-3"><button class="btn btn-success w-100">Add Book</button></div>
</form>

<table class="table mt-4 table-bordered">
    <thead>
        <tr>
            <th>ID</th><th>Title</th><th>Author</th><th>Status</th><th>Action</th>
        </tr>
    </thead>
    <tbody id="bookTable"></tbody>
</table>
</div>

<!-- EDIT MODAL -->
<div class="modal fade" id="editBookModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <form id="editBookForm">
        <div class="modal-header">
          <h5 class="modal-title">Edit Book</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <input type="hidden" name="id" id="edit_id">

          <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" id="edit_title" class="form-control" required>
          </div>

          <div class="mb-3">
            <label>Author</label>
            <input type="text" name="author" id="edit_author" class="form-control" required>
          </div>

          <!-- RADIO BUTTONS -->
          <div class="mb-3">
            <label>Availability</label><br>
            <label><input type="radio" name="availability" value="available" id="avail_available"> Available</label>
            <label class="ms-3"><input type="radio" name="availability" value="unavailable" id="avail_unavailable"> Unavailable</label>
          </div>

        </div>

        <div class="modal-footer">
          <button class="btn btn-primary">Update</button>
        </div>

      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Load all books
function loadBooks(){
    $.post('../handler/book_handler.php', {action:'get'}, function(data){
        $("#bookTable").html(data);
    });
}
loadBooks();

// ADD BOOK
$("#addBookForm").submit(function(e){
    e.preventDefault();
    $.post('../handler/book_handler.php', 
        $(this).serialize()+"&action=add", 
        function(res){
            alert(res);
            loadBooks();
            $("#addBookForm")[0].reset();
        }
    );
});

// OPEN EDIT MODAL
$(document).on("click", ".edit-book", function(){
    $("#edit_id").val($(this).data("id"));
    $("#edit_title").val($(this).data("title"));
    $("#edit_author").val($(this).data("author"));

    // set availability radio
    if($(this).data("availability") === "available") {
        $("#avail_available").prop("checked", true);
    } else {
        $("#avail_unavailable").prop("checked", true);
    }

    new bootstrap.Modal(document.getElementById('editBookModal')).show();
});

// UPDATE BOOK (AJAX)
$("#editBookForm").submit(function(e){
    e.preventDefault();

    $.post('../handler/book_handler.php', 
        $(this).serialize()+"&action=update", 
        function(res){
            alert(res);
            loadBooks();
            $("#editBookModal").modal('hide');
        }
    );
});

// DELETE BOOK
$(document).on("click",".delete-book", function(){
    $.post('../handler/book_handler.php',
        { id:$(this).data("id"), action:"delete" },
        function(res){
            alert(res);
            loadBooks();
        }
    );
});
</script>

</body>
</html>
