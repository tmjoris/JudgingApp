<?php
include_once '../includes/db.php';

$message = '';
session_start();
$judge_username = $_SESSION['judge_username']; // Set this during login

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_username = $_POST['user_username'] ?? '';
    $points = (int)($_POST['points'] ?? 0);

    if ($user_username && $points >= 1 && $points <= 100) {
        // Check if score already exists
        $stmt = $conn->prepare("SELECT id FROM scores WHERE user_name = ? AND judge_name = ?");
        $stmt->bind_param("ss", $user_username, $judge_username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Update existing score
            $stmt->close();
            $update = $conn->prepare("UPDATE scores SET points = ? WHERE user_name = ? AND judge_name = ?");
            $update->bind_param("iss", $points, $user_username, $judge_username);
            $update->execute();
            $update->close();
            $message = "Score updated.";
        } else {
            // Insert new score
            $stmt->close();
            $insert = $conn->prepare("INSERT INTO scores (user_name, judge_name, points) VALUES (?, ?, ?)");
            $insert->bind_param("ssi", $user_username, $judge_username, $points);
            $insert->execute();
            $insert->close();
            $message = "Score added.";
        }
    } else {
        $message = "Invalid input. Points must be between 1 and 100.";
    }
}

// Fetch all users
$users = $conn->query("SELECT username, display_name FROM users ORDER BY display_name ASC");

// Get judge display name
$judge_stmt = $conn->prepare("SELECT display_name FROM judges WHERE username = ?");
$judge_stmt->bind_param("s", $judge_username);
$judge_stmt->execute();
$judge_stmt->bind_result($judge_name);
$judge_stmt->fetch();
$judge_stmt->close();
?>
