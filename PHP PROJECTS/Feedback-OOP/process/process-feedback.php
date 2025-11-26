<?php
require_once '../core/Database.php';

class Feedback
{
    private $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function save($name, $email, $message)
    {
        $sql = "INSERT INTO feedbacks (name, email, message) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $message);
        return $stmt->execute();
    }

    public function all()
    {
        $sql = "SELECT * FROM feedbacks ORDER BY id DESC";
        $result = $this->conn->query($sql);

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }
}

// Initialize DB
$db = new Database();
$conn = $db->getConnection();
$feedback = new Feedback($conn);

$action = $_GET['action'] ?? '';

if ($action === 'save') {
    header('Content-Type: application/json');

    $ok = $feedback->save($_POST['name'], $_POST['email'], $_POST['message']);

    echo json_encode([
        'status' => $ok ? 'success' : 'error',
        'message' => $ok ? 'Saved!' : 'Failed to save'
    ]);

} elseif ($action === 'list') {

    $rows = $feedback->all();

    foreach ($rows as $row) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>" . htmlspecialchars($row['name']) . "</td>
                <td>" . htmlspecialchars($row['email']) . "</td>
                <td>" . htmlspecialchars($row['message']) . "</td>
              </tr>";
    }
}
