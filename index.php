<?php

session_start();
//debug reason only//
// print_r($_SESSION['user']);
// print_r($_SESSION['id']);
// in assiociative array we store user email and user_id for session handling 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Movie Catalog</title>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a href="index.php" class="logo">Movie Catalog</a>

        <!-- Welcome Message -->
        <div class="welcome">
            <?php if (isset($_SESSION['user'])): ?>
                <p class="username">Welcome, <?= htmlspecialchars($_SESSION['user']); ?>!</p>
            <?php endif; ?>
        </div>

        <!-- Authentication Links -->
        <div class="auth-links">
            <?php if (isset($_SESSION['user'])): ?>
                <a href="user_profile.php">Profile</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Log In</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container1">
        <h1>Explore Our Movie Catalog</h1>

        <!-- Search Box -->
        <div class="search-box">
            <input type="text" id="search-input" placeholder="Search movies by title..." oninput="searchMovies()">
        </div>

        <!-- Sorting Dropdown -->
        <div class="sorting">
            <label for="sort-by">Sort by:</label>
            <select id="sort-by" onchange="sortMovies()">
                <option value="name">Name A-Z</option>
                <option value="name-desc">Name Z-A</option>
                <option value="rating">Average Rating</option>
            </select>
        </div>

        <!-- Movie Catalog -->
        <div class="catalog" id="catalog">
            <!-- JavaScript will dynamically load movie cards here -->
        </div>

        <!-- Loading indicator -->
        <div id="loading" style="display: none;">Loading more movies...</div>
    </div>

    <!-- Recommendations Button -->
     <!-- idk, its not visible anyway, delete it  -->
    <?php if (isset($_SESSION['user'])): ?>
        <div class="recommendations">
            <form action="generate_recommendations.php" method="post">
                <button type="submit">Generate Recommendations</button>
            </form>
        </div>
    <?php endif; ?>

    <script type="module" src="script.js"></script>
</body>
</html>
