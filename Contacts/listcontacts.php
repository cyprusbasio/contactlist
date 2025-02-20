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

// Retrieve and display contacts using ListAllContacts stored procedure
$result = $conn->query("CALL ListAllContacts()");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 20px;
            text-align: center;
        }
        h2 {
            color: #333;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .popup, .overlay {
            display: none;
            position: fixed;
        }
        .overlay {
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }
        .popup {
            left: 50%; top: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 500px;
        }
        .popup input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .popup button {
            width: 100%;
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 4px;
            margin-top: 10px;
        }
        .popup button:hover {
            background-color: #0056b3;
        }
        .add-contact {
            display: block;
            margin: 20px auto;
            color: #007BFF;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
        }
        .add-contact:hover {
            text-decoration: underline;
        }
        .action-btn {
            padding: 5px 10px;
            margin: 2px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        .edit-btn {
            background-color: #28a745;
            color: white;
        }
        .delete-btn {
            background-color: #dc3545;
            color: white;
        }
        .edit-btn:hover {
            background-color: #218838;
        }
        .delete-btn:hover {
            background-color: #c82333;
        }
    </style>
    <script>
        function openPopup() {
            document.getElementById("popup").style.display = "block";
            document.getElementById("overlay").style.display = "block";
        }
        function closePopup() {
            document.getElementById("popup").style.display = "none";
            document.getElementById("overlay").style.display = "none";
        }
        function deleteContact(id) {
            if (confirm("Are you sure you want to delete this contact?")) {
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "delete_contact.php?id=" + id, true);
                xhr.onload = function () {
                    if (xhr.status == 200) {
                        alert("Contact deleted successfully!");
                        window.location.reload();
                    } else {
                        alert("Error deleting contact.");
                    }
                };
                xhr.send();
            }
        }
    </script>
</head>
<body>
    <h2>Contact List</h2>
    <span class="add-contact" onclick="openPopup()">Add New Contact</span>

    <table>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Birthdate</th>
            <th>Work Phone</th>
            <th>Home Phone</th>
            <th>Email</th>
            <th>Created By ID</th>
            <th>Created Date</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['firstname']); ?></td>
                <td><?php echo htmlspecialchars($row['lastname']); ?></td>
                <td><?php echo htmlspecialchars($row['birthdate']); ?></td>
                <td><?php echo htmlspecialchars($row['workphone']); ?></td>
                <td><?php echo htmlspecialchars($row['homephone']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['createdByID']); ?></td>
                <td><?php echo htmlspecialchars($row['createdDate']); ?></td>
                <td>
                    <button class="action-btn edit-btn" onclick="window.location.href='edit_contact.php?id=<?php echo $row['id']; ?>'">Edit</button>
                    <button class="action-btn delete-btn" onclick="deleteContact(<?php echo $row['id']; ?>)">Delete</button>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <!-- Popup Form -->
    <div id="overlay" class="overlay" onclick="closePopup()"></div>
    <div id="popup" class="popup">
        <h2>Add New Contact</h2>
        <form action="add_contact.php" method="POST">
            <input type="text" name="firstname" placeholder="First Name" required>
            <input type="text" name="lastname" placeholder="Last Name" required>
            <input type="date" name="birthdate" required>
            <input type="text" name="workphone" placeholder="Work Phone">
            <input type="text" name="homephone" placeholder="Home Phone">
            <input type="email" name="email" placeholder="Email" required>
            <button type="submit">Add Contact</button>
        </form>
        <button onclick="closePopup()">Cancel</button>
    </div>

</body>
</html>

<?php
$conn->close();
?>
