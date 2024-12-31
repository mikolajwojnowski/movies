<?php
include('conf.php');
session_start();

if (!isset($_SESSION['user'])) {
    die("You must be logged in to rate a movie.");
}

$email = $_SESSION['user'];
$sql_user_id = "SELECT id FROM users WHERE email = :email";
$stmt_user_id = $pdo->prepare($sql_user_id);
$stmt_user_id->bindParam(':email', $email, PDO::PARAM_STR);
$stmt_user_id->execute();
$user_id = $stmt_user_id->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movie_id = intval($_POST['movie_id']);
    $rating = intval($_POST['rating']);

    $sql = "INSERT INTO user_score (id_user, id_movie, score, time) 
            VALUES (:user_id, :movie_id, :rating, NOW()) 
            ON DUPLICATE KEY UPDATE score = :rating, time = NOW()";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':movie_id', $movie_id, PDO::PARAM_INT);
    $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: movie_details.php?movie_id=$movie_id");
    } else {
        echo "Error updating rating.";
    }
}
?>
