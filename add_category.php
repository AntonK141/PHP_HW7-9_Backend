<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$conn = new mysqli('localhost', 'root', '', 'hw2_category_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $photo = $_FILES['photo'];

    $target_dir = "uploads/";
    $file_extension = pathinfo($photo["name"], PATHINFO_EXTENSION);
    $unique_file_name = uniqid('category_photo_', true) . '.' . $file_extension;
    $target_file = $target_dir . $unique_file_name;

    // Validate the file type (for example, only allow images)
    $allowed_file_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array(strtolower($file_extension), $allowed_file_types)) {
        die("Error: Only image files (jpg, jpeg, png, gif) are allowed.");
    }

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (move_uploaded_file($photo["tmp_name"], $target_file)) {
        $sql = "INSERT INTO categories (name, photo) VALUES ('$name', '$target_file')";
        if ($conn->query($sql) === TRUE) {
            echo "New category added successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Error: There was an error uploading your file.";
    }
}

$conn->close();
?>


