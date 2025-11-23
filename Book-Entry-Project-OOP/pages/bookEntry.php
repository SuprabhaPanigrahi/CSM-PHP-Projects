<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Book</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-white">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h4>Add Book Details</h4>
        </div>
        <div class="card-body">
            <form action="../process/process-book.php" method="post" enctype="multipart/form-data">

                <div class="mb-3">
                    <label class="form-label">Book Name</label>
                    <input type="text" name="bookname" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Author</label>
                    <input type="text" name="author" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Publisher</label>
                    <input type="text" name="publisher" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select" required>
                        <option value="" disabled selected>Select Category</option>
                        <option>Fiction</option>
                        <option>Non-Fiction</option>
                        <option>Science</option>
                        <option>Technology</option>
                        <option>Biography</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label d-block">Available As</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="available_as[]" value="Softcopy">
                        <label class="form-check-label">Softcopy</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="available_as[]" value="Hardcopy">
                        <label class="form-check-label">Hardcopy</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Price ($)</label>
                    <input type="number" name="price" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Review</label>
                    <textarea name="review" class="form-control" rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Book Image</label>
                    <input type="file" name="bookImage" class="form-control" accept="image/*">
                </div>

                <div class="mb-3">
                    <label class="form-label d-block">Rating</label>
                    <input type="number" name="rating" min="1" max="5" class="form-control w-25" placeholder="1-5">
                </div>

                <div class="text-center">
                    <button class="btn btn-success px-4">Submit</button>
                </div>

            </form>
        </div>
    </div>
</div>

</body>
</html>
