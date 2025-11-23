<?php
session_start();
$books = $_SESSION['books'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h4>Book List</h4>
        </div>
        <div class="card-body">
            <?php if (!empty($books)): ?>
                <table class="table table-bordered table-striped text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Book Name</th>
                            <th>Author</th>
                            <th>Publisher</th>
                            <th>Category</th>
                            <th>Available As</th>
                            <th>Price ($)</th>
                            <th>Review</th>
                            <th>Rating</th>
                            <th>Image</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($books as $book): ?>
                            <tr>
                                <td><?= htmlspecialchars($book['bookname']) ?></td>
                                <td><?= htmlspecialchars($book['author']) ?></td>
                                <td><?= htmlspecialchars($book['publisher']) ?></td>
                                <td><?= htmlspecialchars($book['category']) ?></td>
                                <td><?= htmlspecialchars($book['available_as']) ?></td>
                                <td><?= htmlspecialchars($book['price']) ?></td>
                                <td><?= htmlspecialchars($book['review']) ?></td>
                                <td><?= htmlspecialchars($book['rating']) ?> ⭐</td>
                                <td>
                                    <?php
                                    $imagePath = '../uploads/' . $book['bookImage'];
                                    if (!empty($book['bookImage']) && file_exists($imagePath)) {
                                        echo '<img src="' . $imagePath . '" alt="Book" width="80">';
                                    } else {
                                        echo '<img src="https://via.placeholder.com/80x100" alt="No Image">';
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center text-muted">No books added yet.</p>
            <?php endif; ?>

            <div class="text-center mt-4">
                <a href="bookEntry.php" class="btn btn-success">Add More Books</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
