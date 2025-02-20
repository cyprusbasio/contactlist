<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "contacts_db";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed"]));
}

// Check if ID is set
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Prepare and execute the stored procedure
    $stmt = $conn->prepare("CALL DeleteContact(?)");
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();

    if ($result) {
        echo json_encode(["status" => "success", "message" => "Contact deleted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete contact"]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}

$conn->close();
?>
