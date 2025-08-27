<?php
session_start();
include '../includes/db.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Login successful
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        header("Location: /dashboard.php"); // or homepage
        exit;
    } else {
        // Login failed
        header("Location: /login.php?error=Invalid credentials");
        exit;
    }
} else {
    header("Location: /login.php");
    exit;
}
