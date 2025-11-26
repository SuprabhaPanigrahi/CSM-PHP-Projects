<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Members</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2>Manage Members</h2>

    <!-- Add Member Form -->
    <form id="addMemberForm" class="mb-4">
        <div class="mb-3">
            <input type="text" name="name" class="form-control" placeholder="Member Name" required>
        </div>
        <div class="mb-3">
            <input type="text" name="member_id" class="form-control" placeholder="Member ID" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Member</button>
    </form>

    <!-- Members Table -->
    <table class="table table-bordered" id="membersTable">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Member ID</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<!-- Update Member Modal -->
<div class="modal fade" id="updateMemberModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="updateMemberForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="updateMemberId">
                <div class="mb-3">
                    <input type="text" name="name" id="updateMemberName" class="form-control" placeholder="Member Name" required>
                </div>
                <div class="mb-3">
                    <input type="text" name="member_id" id="updateMemberMemberId" class="form-control" placeholder="Member ID" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Update Member</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {

    function loadMembers() {
        $.get('../process/getMembers.php', function(data) {
            $('#membersTable tbody').html(data);
        });
    }

    loadMembers();

    // Add Member
    $('#addMemberForm').submit(function(e) {
        e.preventDefault();
        $.post('../process/addMember.php', $(this).serialize(), function(response) {
            alert(response);
            $('#addMemberForm')[0].reset();
            loadMembers();
        });
    });

    // Delete Member
    $(document).on('click', '.delete-member', function() {
        let id = $(this).data('id');
        if(confirm('Delete this member?')) {
            $.post('../process/removeMember.php', {id: id}, function(response) {
                alert(response);
                loadMembers();
            });
        }
    });

    // Show Update Modal
    $(document).on('click', '.edit-member', function() {
        let id = $(this).data('id');
        let name = $(this).data('name');
        let member_id = $(this).data('member_id');
        $('#updateMemberId').val(id);
        $('#updateMemberName').val(name);
        $('#updateMemberMemberId').val(member_id);
        new bootstrap.Modal(document.getElementById('updateMemberModal')).show();
    });

    // Update Member
    $('#updateMemberForm').submit(function(e) {
        e.preventDefault();
        $.post('../process/updateMember.php', $(this).serialize(), function(response) {
            alert(response);
            loadMembers();
            bootstrap.Modal.getInstance(document.getElementById('updateMemberModal')).hide();
        });
    });

});
</script>
</body>
</html>
