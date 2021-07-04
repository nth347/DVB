<?php
require_once "config.php";

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))) {
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else {
        $sql = "SELECT id FROM users WHERE username = :username";

        if($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":username", $param_username);
            $param_username = trim($_POST["username"]);

            if($stmt->execute()) {
                if($stmt->rowCount() == 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "<p>Oops! Something went wrong. Please try again later.</p>";
            }
            unset($stmt);
        }
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
        $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";

        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":username", $param_username);
            $stmt->bindParam(":password", $param_password);

            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT);

            if ($stmt->execute()) {
                // Redirect to login page
                header("location: login.php");
            } else {
                echo "<p>Oops! Something went wrong. Please try again later.</p>";
            }
            unset($stmt);
        }
    }
    unset($pdo);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DVB - Signup</title>
</head>
<body>
    <h1>Damp Vulnerable Blog - Sigup</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="username">Username</label><br>
        <input type="text" id="username" name="username" value="<?php echo $username; ?>">
        <span><?php echo $username_err; ?></span><br>

        <label for="password">Password</label><br>
        <input type="password" id="password" name="password" value="<?php echo $password; ?>">
        <span><?php echo $password_err; ?></span><br>

        <label for="confirm_password">Confirm password</label><br>
        <input type="password" id="confirm_password" name="confirm_password" value="<?php echo $confirm_password; ?>">
        <span><?php echo $confirm_password_err; ?></span>

        <input type="submit" value="Sign up">
        <input type="reset" value="Clear">
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </form>
</body>
</html>