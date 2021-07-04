<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DVB - Home</title>
</head>
<body>
    <h1>Damp Vulnerable Blog</h1>
    <?php
    if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
        echo '<p>Hello, you are logged in as user <b>' . $_SESSION["username"] . '</b></p>';
        echo '<a href="change-password.php">Change password</a> | ';
        echo '<a href="logout.php">Log out</a> | ';
        echo '<a href="index.php?action=search-posts.php">Search for posts</a> | ';
        echo '<a href="index.php?action=upload-post.php">Upload a post</a>';

        if(isset($_GET['action'])) {
            $whitelist = ['search-posts.php', 'upload-post.php'];
            $path = $_GET['action'];
            if (in_array($path, $whitelist)) {
                include_once ('actions/' . $_GET['action']);
            }
        }
    } else {
        echo '<p>Log in required.</p>';
        echo '<a href="login.php">Log in</a> | ';
        echo '<a href="register.php">Sign up</a>';
    }

    if (isset($_FILES['file'])) {
        $errors = array();
        $file_name = $_FILES['file']['name'];
        $file_size = $_FILES['file']['size'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_type = $_FILES['file']['type'];
        $array = explode('.', $_FILES['file']['name']);
        $file_ext = strtolower(end($array));

        $extensions = array("txt");

        # Validate the extension
        if (in_array($file_ext, $extensions) === false && sizeof($array) == 2) {
            $errors[] = "File extension not allowed.";
        }

        # Validate the size
        if ($file_size > 104857600) {
            $errors[] = "File size must be less than 50 MB.";
        }

        if (empty($errors) == true) {
            $file_name = basename($file_name);
            move_uploaded_file($file_tmp, "uploads/" . $file_name);
            echo "<p>File uploaded.</p>";

            require_once 'config.php';
            $sql = "INSERT INTO posts (title, content) VALUES(:title, :content)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":title", $param_title);
            $stmt->bindParam(":content", $param_content);
            $param_title = $array[0];
            $param_content = trim(file_get_contents('uploads/' . $_FILES['file']['name']));
            try {
                if ($stmt->execute() === true) {
                    echo '<p>Post uploaded</p>';
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
    }
    ?>
</body>
</html>