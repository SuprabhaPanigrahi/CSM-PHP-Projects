<!DOCTYPE html>
<html>
<head>
<title>Issue / Return Books</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</head>

<body>
<div class="container mt-5">

<h3>Issue Book</h3>
<form id="issueForm" class="row">
    <div class="col-md-4">
        <select name="member_id" class="form-select" required></select>
    </div>
    <div class="col-md-4">
        <select name="book_id" class="form-select" required></select>
    </div>
    <div class="col-md-4">
        <button class="btn btn-success w-100">Issue</button>
    </div>
</form>

<h3 class="mt-5">Return Book</h3>
<form id="returnForm" class="row">
    <div class="col-md-4">
        <select name="member_id" class="form-select" required></select>
    </div>
    <div class="col-md-4">
        <select name="book_id" class="form-select" required></select>
    </div>
    <div class="col-md-4">
        <button class="btn btn-warning w-100">Return</button>
    </div>
</form>

<h3 class="mt-5">Issued Books</h3>
<table class="table table-bordered">
    <thead>
        <tr><th>Member ID</th><th>Book</th><th>Author</th></tr>
    </thead>
    <tbody id="issuedTable"></tbody>
</table>

</div>

<script>
function loadDrops(){
    $.post('../handler/member_handler.php',{action:'dropdown'}, s=>{
        $("select[name='member_id']").html(s);
    });

    $.post('../handler/book_handler.php',{action:'dropdown'}, s=>{
        $("select[name='book_id']").html(s);
    });
}

function loadIssued(){
    $.post('../handler/issue_handler.php',{action:'get'}, function(data){
        $("#issuedTable").html(data);
    });
}

loadDrops();
loadIssued();

$("#issueForm").submit(function(e){
    e.preventDefault();
    $.post('../handler/issue_handler.php', $(this).serialize()+"&action=issue", function(res){
        alert(res);
        loadDrops();
        loadIssued();
    });
});

$("#returnForm").submit(function(e){
    e.preventDefault();
    $.post('../handler/issue_handler.php', $(this).serialize()+"&action=return", function(res){
        alert(res);
        loadDrops();
        loadIssued();
    });
});
</script>

</body>
</html>
