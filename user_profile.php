<?php
include('conf.php');
session_start();
// print_r($_SESSION['user']);


if (!isset($_SESSION['user'])) {
    die("You must be logged in to view your profile.");
}

$email = $_SESSION['user'];

// Fetch user details
$sql_user = "SELECT * FROM users WHERE email = :email";
$stmt_user = $pdo->prepare($sql_user);
$stmt_user->bindParam(':email', $email, PDO::PARAM_STR);
$stmt_user->execute();
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);


if (!$user) {
    die("User not found.");
}

// FIX - TODO RECCOMENDATIONS 
// Fetch recommendations
$sql_recommendations = "SELECT m.title, r.rec_score 
                        FROM recs r 
                        JOIN movie m ON r.movie_id = m.id 
                        WHERE r.user_id = :user_id 
                        ORDER BY r.rec_score DESC LIMIT 10";
$stmt_recommendations = $pdo->prepare($sql_recommendations);
$stmt_recommendations->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
$stmt_recommendations->execute();
$recommendations = $stmt_recommendations->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles-profile.css">
    <title>User Profile</title>
</head>
<body>
    <div class="back">
        <a href="index.php"><button class="button">Back</button></a>
    </div>

    <div class="container">

        <!-- User Profile Information -->
        <h1>Welcome, <?= htmlspecialchars($user['name']); ?>!</h1>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
        <p><strong>Age:</strong> <?= htmlspecialchars($user['edad']); ?></p>
        <p><strong>Occupation:</strong> <?= htmlspecialchars($user['ocupacion']); ?></p>
        <p><strong>Gender:</strong> <?= htmlspecialchars($user['sex']); ?></p>


        <!-- Edit Profile Form -->
        <h2>Edit Your Profile</h2>
        <form action="edit_profile.php" method="post" class="form1">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($user['name']); ?>" required>
            <label for="edad">Age:</label>
            <input type="number" name="edad" id="edad" value="<?= htmlspecialchars($user['edad']); ?>" required>

            <label for="ocupacion">Occupation:</label>
            <select name="ocupacion" id="ocupacion" required>
                <option value="<?= htmlspecialchars($user['ocupacion']); ?>" selected><?= htmlspecialchars($user['ocupacion']); ?></option>
                <!-- Add more occupations -->
                <option value="student">Student</option>
                <option value="programmer">Programmer</option>
                <option value="artist">Artist</option>
                <option value="engineer">Engineer</option>
                <!-- Add other options as per your database -->
            </select>

            <button type="submit">Update Profile</button>
        </form>

        <!-- Recommendations Section -->
         <!-- TODO  -->
        <h2>Your Recommendations</h2>
        <?php if (count($recommendations) > 0): ?>
            <ul>
                <?php foreach ($recommendations as $rec): ?>
                    <li><?= htmlspecialchars($rec['title']); ?> - Score: <?= htmlspecialchars($rec['rec_score']); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No recommendations yet. Click the button below to generate recommendations.</p>
        <?php endif; ?>

        <!-- Generate Recommendations -->
        <form action="generate_recommendations.php" method="post">
            <button type="submit">Generate Recommendations</button>
        </form>
    </div>
</body>
</html>
