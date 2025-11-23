<?php
require_once '../core/database.php';

// Get issued books with member name and book title
$sql = "SELECT ib.id as issue_id, m.name as member_name, b.title as book_title, b.author
        FROM issued_books ib
        JOIN members m ON ib.member_id = m.member_id
        JOIN books b ON ib.book_id = b.id
        ORDER BY ib.id DESC";

$result = $conn->query($sql);

if(!$result){
    die("Query failed: " . $conn->error);
}

foreach($result as $row){
    echo "<tr>
            <td>{$row['member_name']}</td>
            <td>{$row['book_title']}</td>
            <td>{$row['author']}</td>
            <td>
                <button class='btn btn-sm btn-warning return-book' data-id='{$row['issue_id']}'>Return</button>
            </td>
          </tr>";
}
?>
