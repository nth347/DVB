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


    echo '<p>Posts containing ' . htmlspecialchars($_GET['keyword']) . '</p>';

    if ($_GET['keyword']) {
        $stmt  = $pdo->prepare("SELECT * FROM posts WHERE title LIKE :keyword OR content LIKE :keyword");
        $stmt->execute(['keyword' => '%' . $_GET['keyword'] . '%']);
        $rows = $stmt->fetchAll();

        if ($rows !== false && $rows >= 1) {
            echo '<ul>';
            foreach ($rows as $row) {
                echo '<li>' . htmlspecialchars($row['title']) . ' - ' . htmlspecialchars($row['content']) . '</li>';
            }
            echo '</ul>';
        }
        unset($pdo);
    }
    ?>
</form>