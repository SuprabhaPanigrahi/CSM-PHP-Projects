<?php
require_once '../models/IssueGateway.php';

$action = $_POST['action'];

switch($action){

    case "issue":
        echo IssueGateway::issue($_POST['member_id'], $_POST['book_id'])
            ? "Book Issued"
            : "Error issuing";
        break;

    case "return":
        echo IssueGateway::returnBook($_POST['member_id'], $_POST['book_id'])
            ? "Book Returned"
            : "Error returning";
        break;

    case "get":
        foreach(IssueGateway::getIssued() as $i){
            echo "<tr>
                <td>{$i['member_id']}</td>
                <td>{$i['title']}</td>
                <td>{$i['author']}</td>
            </tr>";
        }
        break;
}
