<?php
include_once '../includes/db.php';

$message = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $display_name = trim($_POST['display_name']);
    if($username && $display_name){
        $stmt = $conn->prepare("INSERT INTO judges (username, display_name) VALUES(?, ?)");
        $stmt->bind_param('ss', $username, $display_name);
        if($stmt->execute()) {
            $message = "Judge added successfully.";
        } else {
            $message = "Error: " . conn-> error;
        }
    } else {
        $message = "Please fill in all fields.";
    }
}

//Fetch all judges
$result = $conn->query("SELECT * FROM judges ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Admin Panel - Add Judges</title>
        <link rel="stylesheet" href="../css/style.css"/>
    </head>

    <body>
<div class="container">
    <h2>Admin Panel - Add Judges</h2>

    <?php if ($message): ?>
        <p><strong><?=htmlspecialchars($message)?></strong></p>
    <?php endif; ?>

    <form method="POST">
        <label>Judge Username:</label><br>
        <input type="text" name="username" required /><br><br>

        <label>Judge Display Name:</label><br>
        <input type="text" name="display_name" required /><br><br>

        <button type="submit">Add Judge</button>
    </form>

    <h3>Existing Judges</h3>
    <table>
        <thead>
        <tr><th>ID</th><th>Username</th><th>Display Name</th></tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['ID'] ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['display_name']) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>