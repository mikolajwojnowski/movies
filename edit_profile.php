<?php
include('conf.php');
session_start();

if (!isset($_SESSION['user'])) {
    die("Unauthorized access.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['email'];
    $name = trim($_POST['name']);
    $edad = intval($_POST['edad']);
    $ocupacion = trim($_POST['ocupacion']);

    $sql = "UPDATE users SET name = :name, edad = :edad, ocupacion = :ocupacion WHERE id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':edad', $edad, PDO::PARAM_INT);
    $stmt->bindParam(':ocupacion', $ocupacion, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: user_profile.php");
    } else {
        echo "Error updating profile.";
    }
}
