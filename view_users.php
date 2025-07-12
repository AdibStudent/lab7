<?php
session_start();
$conn = new mysqli("localhost", "root", "", "lab7wb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// DELETE logic
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: view_users.php");
    exit();
}

// UPDATE logic
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $accessLevel = $_POST['accessLevel'];

    $stmt = $conn->prepare("UPDATE users SET matric=?, name=?, accessLevel=? WHERE id=?");
    $stmt->bind_param("sssi", $matric, $name, $accessLevel, $id);
    $stmt->execute();
    header("Location: view_users.php");
    exit();
}

// Fetch all users
$result = $conn->query("SELECT * FROM users");

// Fetch one user if updating
$editMode = false;
if (isset($_GET['edit'])) {
    $editMode = true;
    $id = $_GET['edit'];
    $editUser = $conn->query("SELECT * FROM users WHERE id = $id")->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Users</title>
</head>
<body>
    <h2>User List</h2>

    <!-- Edit form -->
    <?php if ($editMode): ?>
        <h3>Update User</h3>
        <form method="POST">
            <input type="hidden" name="id" value="<?= $editUser['id'] ?>">
            Matric: <input type="text" name="matric" value="<?= $editUser['matric'] ?>"><br><br>
            Name: <input type="text" name="name" value="<?= $editUser['name'] ?>"><br><br>
            Access Level:
            <select name="accessLevel">
                <option value="Student" <?= $editUser['accessLevel'] == 'Student' ? 'selected' : '' ?>>Student</option>
                <option value="Admin" <?= $editUser['accessLevel'] == 'Admin' ? 'selected' : '' ?>>Admin</option>
            </select><br><br>
            <input type="submit" name="update" value="Update">
        </form>
    <?php endif; ?>

    <!-- Users table -->
    <table border="1" cellpadding="10">
        <tr>
            <th>Matric</th>
            <th>Name</th>
            <th>Access Level</th>
            <th>Action</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['matric']) ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['accessLevel']) ?></td>
                <td>
                    <a href="?edit=<?= $row['id'] ?>">Edit</a> |
                    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php $conn->close(); ?>
