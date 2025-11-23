<?php
session_start();
require_once '../models/Book.php';

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
                <?php foreach ($books as $book): 
                    $bookObj = unserialize($book); ?>
                    <div class="border p-3 mb-3 rounded bg-white">
                        <p><strong>Book Name:</strong> <?= htmlspecialchars($bookObj->getBookname()) ?></p>
                        <p><strong>Author:</strong> <?= htmlspecialchars($bookObj->getAuthor()) ?></p>
                        <p><strong>Publisher:</strong> <?= htmlspecialchars($bookObj->getPublisher()) ?></p>
                        <p><strong>Category:</strong> <?= htmlspecialchars($bookObj->getCategory()) ?></p>
                        <p><strong>Available As:</strong> <?= htmlspecialchars($bookObj->getAvailableAs()) ?></p>
                        <p><strong>Price:</strong> $<?= htmlspecialchars($bookObj->getPrice()) ?></p>
                        <p><strong>Review:</strong> <?= htmlspecialchars($bookObj->getReview()) ?></p>
                        <p><strong>Rating:</strong> <?= htmlspecialchars($bookObj->getRating()) ?> ⭐</p>

                        <?php
                        $imagePath = '../uploads/' . $bookObj->getBookImage();
                        if (!empty($bookObj->getBookImage()) && file_exists($imagePath)) {
                            echo '<img src="' . $imagePath . '" alt="Book Image" style="width:120px;">';
                        } else {
                            echo '<img src="https://via.placeholder.com/120x160" alt="No Image">';
                        }
                        ?>
                    </div>
                <?php endforeach; ?>
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
