<!DOCTYPE html>
<html>

<head>
    <title>Students</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <h3 class="text-center mb-4">Student List</h3>

        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Course</th>
                </tr>
            </thead>
            <tbody id="studentTableBody">
                <tr>
                    <td colspan="4">Loading...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        $(document).ready(function() {

            $.ajax({
                url: '/students/fetch',
                type: 'GET',
                success: function(data) {

                    let rows = '';

                    if (data.length > 0) {
                        $.each(data, function(index, student) {
                            rows += `
                        <tr>
                            <td>${student.id}</td>
                            <td>${student.name}</td>
                            <td>${student.email}</td>
                            <td>${student.course}</td>
                        </tr>
                    `;
                        });
                    } else {
                        rows = `<tr><td colspan="4">No data found</td></tr>`;
                    }

                    $('#studentTableBody').html(rows);
                },
                error: function() {
                    alert('Failed to fetch data');
                }
            });

        });
    </script>

</body>

</html>