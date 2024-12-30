<?php
// login.php
// session_start();
include("conf.php"); // Include the database connection file.

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST["submit"])) {
    try {
        //get data for further validation
        $email = $_POST["email"];
        $passwd = sha1($_POST["passwd"]);

        // $stmt = $pdo->prepare("SELECT passwd FROM users WHERE email =:email");
        // $stmt->bindParam(':email', $email);
        // $stmt->execute();

        // $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // //check the credentials 

        // if($passwd == $result["passwd"])
        // {
        //     print_r("sukces kurwa");

        // }else{
        //     print_r("zjebales");
        // }

        // Check the credentials
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND passwd = :passwd");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':passwd', $passwd);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Login successful

            //ogarnij co to te sesje i na huj to ale ogulem ok
            //i jak to pozniej uzyc 
            session_start();
            $_SESSION['user'] = $email; // Store user data in the session

            // Redirect to home page
            header("Location: index.php");
            exit(); // Stop further script execution after the redirect
        } else {
            echo "<div class='message'>Invalid email or password.</div>";
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

?>


<!-- // if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     $username = $_POST['username'];
//     $password = sha1($_POST['password']); // Password hashing

//     $stmt = $pdo->prepare("SELECT * FROM users WHERE name = :username AND passwd = :password");
//     $stmt->execute(['username' => $username, 'password' => $password]);
//     $user = $stmt->fetch();

//     if ($user) {
//         $_SESSION['user_id'] = $user['id'];
//         header("Location: home.php"); // Redirect to home page after login
//     } else {
//         echo "Invalid username or password!";
//     }
// }

?> -->


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
                    Don't have an account? <a href="register.html">Sign Up Now</a>
                </div>

            </form>
        </div>




        <script src="script.js"></script>
</body>

</html>