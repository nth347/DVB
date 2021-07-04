<?php
if (!isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
    header("location: index.php");
    exit;
}
?>
<p>Search for posts</p>
<form action="index.php" method="get">
    <label for="keyword">Keyword</label>
    <input type="hidden" id="action" name="action" value="search-posts.php">
    <input type="text" id="keyword" name="keyword" value="<?php echo htmlspecialchars($_GET['keyword']); ?>">
    <input type="submit" value="Search">
    <?php
    require_once "config.php";

    $keyword = $_GET['keyword'];

    echo '<p>Posts containing ' . $keyword . '</p>';

    if ($keyword) {
        $sql = 'SELECT * FROM posts WHERE title LIKE ' . "'%$keyword%'" . ' OR content LIKE ' . "'%$keyword%'" . '';
        $rows = $pdo->query($sql);
        unset($pdo);
    }


    if ($rows !== false && $rows >= 1) {
        echo '<ul>';
        foreach ($rows as $row) {
            echo '<li>' . $row['title'] . ' - ' . $row['content'] . '</li>';
        }
        echo '</ul>';
    }
    ?>
</form>