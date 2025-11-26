<!DOCTYPE html>
<html>
<head>
<title>Manage Members</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</head>

<body>
<div class="container mt-4">
    <h3>Manage Members</h3>

    <!-- ADD MEMBER -->
    <form id="addMemberForm" class="row mt-3">
        <div class="col-md-4">
            <input type="text" name="name" placeholder="Member Name" class="form-control" required>
        </div>

        <div class="col-md-4">
            <input type="text" name="member_id" placeholder="Member ID" class="form-control" required>
        </div>

        <div class="col-md-4">
            <button class="btn btn-success w-100">Add Member</button>
        </div>
    </form>

    <table class="table mt-4 table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Member Name</th>
                <th>Member ID</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="memberTable"></tbody>
    </table>
</div>


<!-- EDIT MEMBER MODAL -->
<div class="modal fade" id="editMemberModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <form id="editMemberForm">
        <div class="modal-header">
          <h5 class="modal-title">Edit Member</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <input type="hidden" name="id" id="edit_id">

          <div class="mb-3">
            <label>Member Name</label>
            <input type="text" name="name" id="edit_name" class="form-control" required>
          </div>

          <div class="mb-3">
            <label>Member ID</label>
            <input type="text" name="member_id" id="edit_member_id" class="form-control" required>
          </div>

        </div>

        <div class="modal-footer">
          <button class="btn btn-primary w-100">Update</button>
        </div>

      </form>

    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>

// Load Members
function loadMembers(){
    $.post('../handler/member_handler.php', {action:'get'}, function(data){
        $("#memberTable").html(data);
    });
}
loadMembers();


// ADD MEMBER
$("#addMemberForm").submit(function(e){
    e.preventDefault();

    $.post('../handler/member_handler.php',
        $(this).serialize()+"&action=add",
        function(res){
            alert(res);
            loadMembers();
            $("#addMemberForm")[0].reset();
        }
    );
});


// OPEN EDIT MODAL
$(document).on("click", ".edit-member", function(){

    $("#edit_id").val($(this).data("id"));
    $("#edit_name").val($(this).data("name"));
    $("#edit_member_id").val($(this).data("member_id"));

    new bootstrap.Modal(document.getElementById('editMemberModal')).show();
});


// UPDATE MEMBER
$("#editMemberForm").submit(function(e){
    e.preventDefault();

    $.post('../handler/member_handler.php',
        $(this).serialize()+"&action=update",
        function(res){
            alert(res);
            loadMembers();
            $("#editMemberModal").modal("hide");
        }
    );
});


// DELETE MEMBER
$(document).on("click",".delete-member", function(){
    if(!confirm("Delete this member?")) return;

    $.post('../handler/member_handler.php',
        { id:$(this).data("id"), action:"delete" },
        function(res){
            alert(res);
            loadMembers();
        }
    );
});

</script>

</body>
</html>
