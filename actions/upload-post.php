<?php
if (!isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
    header("location: index.php");
    exit;
}
?>
<p>Upload a post</p>
<form action="index.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" id="action" name="action" value="upload-post.php">
    <input type="file" name="file"/>
    <input type="submit" value="Upload"/>
</form>
