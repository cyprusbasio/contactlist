<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "contacts_db";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if ID is set
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid contact ID.");
}

$id = $_GET['id'];

// Fetch contact details
$stmt = $conn->prepare("SELECT * FROM contacts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$contact = $result->fetch_assoc();

if (!$contact) {
    die("Contact not found.");
}

$stmt->close();

// Handle update form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $birthdate = $_POST["birthdate"];
    $workphone = $_POST["workphone"];
    $homephone = $_POST["homephone"];
    $email = $_POST["email"];

    // Update using stored procedure
    $update_stmt = $conn->prepare("CALL EditContacts(?, ?, ?, ?, ?, ?, ?)");
    $update_stmt->bind_param("issssss", $id, $firstname, $lastname, $birthdate, $workphone, $homephone, $email);

    if ($update_stmt->execute()) {
        header("Location: listcontacts.php");
        exit();
    } else {
        echo "Error updating contact: " . $update_stmt->error;
    }
    $update_stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Contact</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 500px;
            text-align: center;
        }
        h2 {
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-top: 10px;
            font-weight: bold;
            text-align: left;
        }
        input {
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            width: 95%;
        }
        button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 12px;
            margin-top: 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .back-link {
            display: block;
            margin-top: 15px;
            text-decoration: none;
            color: #007BFF;
            font-size: 14px;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Contact</h2>
        <form method="POST">
            <label>First Name:</label>
            <input type="text" name="firstname" value="<?php echo htmlspecialchars($contact['firstname']); ?>" required>

            <label>Last Name:</label>
            <input type="text" name="lastname" value="<?php echo htmlspecialchars($contact['lastname']); ?>" required>

            <label>Birthdate:</label>
            <input type="date" name="birthdate" value="<?php echo htmlspecialchars($contact['birthdate']); ?>" required>

            <label>Work Phone:</label>
            <input type="text" name="workphone" value="<?php echo htmlspecialchars($contact['workphone']); ?>">

            <label>Home Phone:</label>
            <input type="text" name="homephone" value="<?php echo htmlspecialchars($contact['homephone']); ?>">

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($contact['email']); ?>" required>

            <button type="submit">Update Contact</button>
        </form>
        <a class="back-link" href="listcontacts.php">Back to Contact List</a>
    </div>
</body>
</html>
