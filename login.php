<?php
include("conf.php"); // Include the database connection file.

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST["submit"])) {
    try {
        $email = $_POST["email"];
        $passwd = sha1($_POST["passwd"]);

        // Check the credentials
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND passwd = :passwd");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':passwd', $passwd);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch as associative array
        $user_id = $result['id']; // Access the user_id from the result
        


        

        if ($stmt->rowCount() > 0) {
            // Login successful
            session_start();
            $_SESSION['user'] = $email; // Store user data in the session
            $_SESSION['id'] = $user_id;

            // Display the loader and redirect using JavaScript
            echo "
                <!DOCTYPE html>
                <html lang='en'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <link rel='stylesheet' href='styles.css'>
                    <title>Logging In...</title>
                </head>
                <body>
                    <div class='loader'></div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            setTimeout(function() {
                                window.location.href = 'index.php';
                            }, 3000); // 2-second delay
                        });
                    </script>
                </body>
                </html>
            ";
            exit(); // Stop further script execution
        } else {
            echo "<div class='message'>Invalid email or password.</div>";
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">

    <title>Login</title>
</head>

<body>
    <div class="container">
        <div class="box form-box">
            <header>Login</header>
            <form action="" method="post">
                <div class="field input">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="passwd" id="passwd" required>
                </div>
                <div class="field">
                    <input type="submit" name="submit" value="Login" class="btn">
                </div>
                <div class="links">
                    Don't have an account? <a href="register.php">Sign Up Now</a>
                </div>

            </form>
        </div>




        <script src="script.js"></script>
</body>

</html>