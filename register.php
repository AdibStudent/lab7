<?php
$conn = new mysqli("localhost", "root", "", "lab7wb");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $accessLevel = $_POST['accessLevel'];

    // Only 4 fields inserted, no email
    $stmt = $conn->prepare("INSERT INTO users (matric, name, password, accessLevel) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $matric, $name, $password, $accessLevel);

    if ($stmt->execute()) {
        echo "Registration successful.<br><a href='login.html'>Go to Login</a>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
