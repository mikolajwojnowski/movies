<?php
include('conf.php');
session_start();

if (!isset($_GET['movie_id'])) {
    die("Movie ID not provided.");
}

$movie_id = intval($_GET['movie_id']);

// Fetch movie details
$sql = "SELECT * FROM movie WHERE id = :movie_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':movie_id', $movie_id, PDO::PARAM_INT);
$stmt->execute();
$movie = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$movie) {
    die("Movie not found.");
}

// Fetch genres
$sql_genres = "SELECT g.name FROM moviegenre mg JOIN genre g ON mg.genre = g.id WHERE mg.movie_id = :movie_id";
$stmt_genres = $pdo->prepare($sql_genres);
$stmt_genres->bindParam(':movie_id', $movie_id, PDO::PARAM_INT);
$stmt_genres->execute();
$genres = $stmt_genres->fetchAll(PDO::FETCH_COLUMN);

// Fetch comments
$sql_comments = "SELECT mc.comment, u.name FROM moviecomments mc JOIN users u ON mc.user_id = u.id WHERE mc.movie_id = :movie_id";
$stmt_comments = $pdo->prepare($sql_comments);
$stmt_comments->bindParam(':movie_id', $movie_id, PDO::PARAM_INT);
$stmt_comments->execute();
$comments = $stmt_comments->fetchAll(PDO::FETCH_ASSOC);

// Fetch user-specific data
$user_rating = null;
if (isset($_SESSION['user'])) {

    $email = $_SESSION['user']; //user in session stores email - to change later 
    $user_id = $_SESSION['id']; //user_id from session 



    $sql_user_rating = "SELECT score FROM user_score WHERE id_user = :user_id AND id_movie = :movie_id";
    $stmt_user_rating = $pdo->prepare($sql_user_rating);
    $stmt_user_rating->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_user_rating->bindParam(':movie_id', $movie_id, PDO::PARAM_INT);
    $stmt_user_rating->execute();
    $user_rating = $stmt_user_rating->fetchColumn();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles-details.css">
    <title><?= htmlspecialchars($movie['title']); ?></title>
</head>
<body>
    <div class="back">
        <a href="index.php"><button class="button">Back</button></a>
    </div>
    <div class="container">
        <h1><?= htmlspecialchars($movie['title']); ?></h1>
        <img src="images/<?= htmlspecialchars($movie['url_pic']); ?>" alt="<?= htmlspecialchars($movie['title']); ?>">
        <p><?= htmlspecialchars($movie['desc']); ?></p>
        <p>Release Date: <?= htmlspecialchars($movie['date']); ?></p>
        <p>Genres: <?= htmlspecialchars(implode(', ', $genres)); ?></p>

        <h2>Average Rating</h2>
        <p>Rating: <?= $user_rating ? htmlspecialchars($user_rating) : 'No rating yet'; ?></p>

        <?php if (isset($_SESSION['user'])): ?>
            <form action="rate_movie.php" method="post">
                <input type="hidden" name="movie_id" value="<?= $movie_id; ?>">
                <label for="rating">Your Rating:</label>
                <input type="number" name="rating" id="rating" min="1" max="5" required>
                <button type="submit">Submit Rating</button>
            </form>
        <?php endif; ?>

        <h2>Comments</h2>
        <ul>
            <?php foreach ($comments as $comment): ?>
                <li>
                    <strong><?= htmlspecialchars($comment['name']); ?>:</strong>
                    <?= htmlspecialchars($comment['comment']); ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if (isset($_SESSION['user'])): ?>
            <form action="comment_movie.php" method="post">
                <input type="hidden" name="movie_id" value="<?= $movie_id; ?>">
                <textarea name="comment" placeholder="Add your comment..." required></textarea>
                <button type="submit">Submit Comment</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
