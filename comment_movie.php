<?php
include('conf.php');

session_start();

if (!isset($_SESSION['user'])) {
    die("You must be logged in to comment on a movie.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['id'];
    $movie_id = intval($_POST['movie_id']);
    $comment = trim($_POST['comment']);

    $sql = "INSERT INTO moviecomments (movie_id, user_id, comment) VALUES (:movie_id, :user_id, :comment)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':movie_id', $movie_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);

    if ($stmt->execute()) {
        header("Location: movie_details.php?movie_id=$movie_id");
    } else {
        echo "Error adding comment.";
    }
}
