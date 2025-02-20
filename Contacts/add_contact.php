<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "contacts_db";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $birthdate = $_POST["birthdate"];
    $workphone = $_POST["workphone"];
    $homephone = $_POST["homephone"];
    $email = $_POST["email"];

    // Call stored procedure
    $stmt = $conn->prepare("CALL AddNewContact(?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $firstname, $lastname, $birthdate, $workphone, $homephone, $email);

    if ($stmt->execute()) {
        echo "<script>alert('New contact added successfully.'); window.location.href='listcontacts.php';</script>";
    } else {
        echo "<script>alert('Error adding contact: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

$conn->close();
?>
