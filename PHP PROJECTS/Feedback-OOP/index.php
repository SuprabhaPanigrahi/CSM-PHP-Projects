<!DOCTYPE html>
<html>

<head>
    <title>Feedback Form</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body>
    <h2>Feedback Form</h2>
    <div id="msg"></div>
    <form id="feedbackForm">
        <input type="text" name="name" placeholder="Your Name"><br>
        <input type="email" name="email" placeholder="Your Email"><br>
        <textarea name="message" placeholder="Your Message"></textarea><br>
        <button type="submit">Submit Feedback</button>
    </form>
    <h3>All Feedback</h3>
    <table border="1" id="feedbackTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    <script>
        $(function() {
            function loadFeedback() {
                $.get('process/process-feedback.php?action=list', function(html) {
                    $('#feedbackTable tbody').html(html);
                });
            }
            loadFeedback();
            $('#feedbackForm').on('submit', function(e) {
                e.preventDefault();
                $.post('process/process-feedback.php?action=save', $(this).serialize(), function(res) {
                    $('#msg').text(res.message);
                    if (res.status === 'success') {
                        $('#feedbackForm')[0].reset();
                        loadFeedback();
                    }
                }, 'json');
            });
        });
    </script>
</body>

</html>