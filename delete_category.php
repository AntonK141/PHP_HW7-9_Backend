<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header('HTTP/1.1 200 OK');
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'hw2_category_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    parse_str(file_get_contents("php://input"), $data);
    $id = $data['id'];

    $sql = "SELECT photo FROM categories WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $photo_path = $row['photo'];

        $sql = "DELETE FROM categories WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            if (file_exists($photo_path)) {
                unlink($photo_path);
            }
            echo "Category deleted successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Error: Category not found.";
    }
}

$conn->close();
?>



