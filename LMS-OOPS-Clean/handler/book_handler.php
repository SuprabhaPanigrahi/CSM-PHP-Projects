<?php
require_once '../models/BookGateway.php';
require_once '../core/validator.php';

$action = $_POST['action'] ?? '';

switch ($action) {

    case "add":
        $title = Validator::clean($_POST['title']);
        $author = Validator::clean($_POST['author']);
        $availability = $_POST['availability'];

        $stmt = $conn->prepare("INSERT INTO books (title, author, availability) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $author, $availability);

        echo $stmt->execute() ? "Book added" : "Error adding book";
        break;


    case "update":
        echo BookGateway::update($_POST['id'], $_POST['title'], $_POST['author'], $_POST['availability'])
            ? "Updated"
            : "Error";
        break;

    case "delete":
        echo BookGateway::delete($_POST['id']) ? "Deleted" : "Error";
        break;

    case "get":
        $books = BookGateway::getAll();
        foreach ($books as $b) {
            echo "<tr>
                <td>{$b['id']}</td>
                <td>{$b['title']}</td>
                <td>{$b['author']}</td>
                <td>{$b['availability']}</td>
                <td>
                    <button class='btn btn-warning btn-sm edit-book'
                        data-id='{$b['id']}'
                        data-title='{$b['title']}'
                        data-author='{$b['author']}'
                        data-availability='{$b['availability']}'>Edit</button>
                    <button class='btn btn-danger btn-sm delete-book' data-id='{$b['id']}'>Delete</button>
                </td>
            </tr>";
        }
        break;

    case "dropdown":
        echo "<option value=''>Select Book</option>";
        foreach (BookGateway::getAvailable() as $b) {
            echo "<option value='{$b['id']}'>{$b['title']} ({$b['author']})</option>";
        }
        break;
}
