<?php
require_once '../core/database.php';
require_once '../models/Book.php';

$books = Book::getBooks($conn);

if(isset($_GET['for_dropdown'])){
    foreach($books as $b){
        echo "<option value='{$b['id']}'>{$b['title']} ({$b['author']})</option>";
    }
    exit;
}

// Optional: return table for other views
foreach($books as $b){
    echo "<tr>
            <td>{$b['id']}</td>
            <td>{$b['title']}</td>
            <td>{$b['author']}</td>
          </tr>";
}
?>
