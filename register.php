<?php
include("conf.php");

// Initialize variables
$registrationSuccess = false;
$errorMessage = ""; // Variable to store error messages

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST["submit"])) {
    try {
        // Get form data
        $name = $_POST["name"];
        $email = $_POST["email"];
        $pwd = $_POST["passwd"];
        $passwd = sha1($pwd); // Hash the password
        $age = $_POST["age"];
        $sex = $_POST["sex"];
        $occupation = $_POST["occupation"];

        // Handle file upload
        if (isset($_FILES['pic']) && $_FILES['pic']['error'] == 0) {
            $targetDir = "uploads/";
            $fileName = basename($_FILES['pic']['name']);
            $targetFilePath = $targetDir . $fileName;
            move_uploaded_file($_FILES['pic']['tmp_name'], $targetFilePath);
            $pic = $targetFilePath;
        } else {
            $pic = null;
        }

        // Check if the email already exists
        $stmt = $pdo->prepare("SELECT email FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Email is already used
            $errorMessage = "This email is already used.";
        } else {
            // Insert new user data into the database
            $stmt2 = $pdo->prepare("INSERT INTO `users`(`name`, `email`, `edad`, `sex`, `ocupacion`, `pic`, `passwd`) 
                                    VALUES (:name, :email, :edad, :sex, :ocupacion, :pic, :passwd)");

            $stmt2->bindParam(':name', $name);
            $stmt2->bindParam(':email', $email);
            $stmt2->bindParam(':edad', $age);
            $stmt2->bindParam(':sex', $sex);
            $stmt2->bindParam(':ocupacion', $occupation);
            $stmt2->bindParam(':pic', $pic);
            $stmt2->bindParam(':passwd', $passwd);

            if ($stmt2->execute()) {
                $registrationSuccess = true;
            } else {
                $errorMessage = "Failed to register. Please try again.";
            }
        }
    } catch (Exception $e) {
        $errorMessage = "Error: " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Register</title>
</head>

<body>
    <div class="container">
        <div class="box form-box">
            <?php if ($registrationSuccess): ?>
                <!-- Success Message -->
                <header>Registration Successful!</header>
                <div class="message success">
                    <p>Your registration was successful.</p>
                </div>
                <div class="buttons">
                    <button onclick="location.href='register.php'">Go Back to Form</button>
                    <button onclick="location.href='login.html'">Go to Login</button>
                </div>
            <?php else: ?>
                <!-- Registration Form -->
                <header>Sign Up</header>
                
                <!-- Display Error Message Here -->
                <?php if (!empty($errorMessage)): ?>
                    <div class="message error">
                        <p><?php echo $errorMessage; ?></p>
                    </div>
                <?php endif; ?>

                <form action="" method="post" enctype="multipart/form-data">
                    <div class="field input">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" required>
                    </div>
                    <div class="field input">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" required>
                    </div>
                    <div class="field input">
                        <label for="passwd">Password</label>
                        <input type="password" name="passwd" id="passwd" required>
                    </div>
                    <div class="field input">
                        <label for="age">Age</label>
                        <input type="number" name="age" id="age" min="13" max="120">
                    </div>
                    <div class="field input">
                        <label>Sex</label>
                        <div class="gender-group">
                            <label>
                                <input type="radio" name="sex" value="M" required> Male
                            </label>
                            <label>
                                <input type="radio" name="sex" value="F" required> Female
                            </label>
                        </div>
                    </div>
                    <div class="field input">
                        <label for="occupation">Occupation</label>
                        <select name="occupation" id="occupation" required>
                            <option value="" disabled selected>Choose your occupation</option>
                            <option value="administrator">administrator</option>
                            <option value="artist">artist</option>
                            <option value="doctor">doctor</option>
                            <option value="educator">educator</option>
                            <option value="engineer">engineer</option>
                            <option value="entertainment">entertainment</option>
                            <option value="executive">executive</option>
                            <option value="healthcare">healthcare</option>
                            <option value="homemaker">homemaker</option>
                            <option value="lawyer">lawyer</option>
                            <option value="librarian">librarian</option>
                            <option value="marketing">marketing</option>
                            <option value="none">none</option>
                            <option value="other">other</option>
                            <option value="programmer">programmer</option>
                            <option value="retired">retired</option>
                            <option value="salesman">salesman</option>
                            <option value="scientist">scientist</option>
                            <option value="student">student</option>
                            <option value="technician">technician</option>
                            <option value="writer">writer</option>
                        </select>
                    </div>
                    <div class="field input file">
                        <label for="pic">Profile Picture <small>(optional)</small></label>
                        <!-- Custom-styled file input label -->
                        <label for="pic" class="file-label">Choose a file</label>
                        <span class="file-name" id="file-name">No file chosen</span>
                        <input type="file" name="pic" id="pic" accept="image/*" onchange="updateFileName()">
                    </div>



                    <div class="field">
                        <input type="submit" name="submit" value="Register" class="btn">
                    </div>
                    <div class="links">
                        Already a member? <a href="login.php"> Log In</a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
