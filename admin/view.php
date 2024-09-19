<?php
include("templates/header.php");

$id = $_GET["id"];
if ($id) {
    include("../connect.php");

    // Fetch post details
    $sqlSelectPost = "SELECT * FROM posts WHERE posts_id = ?";
    $stmtPost = $pdo->prepare($sqlSelectPost);
    $stmtPost->bindParam(1, $id);
    $stmtPost->execute();
    $post = $stmtPost->fetch(PDO::FETCH_ASSOC);

    if ($post) {
        // Fetch image details
        $sqlSelectImage = "SELECT id, name, data FROM images WHERE id=?";
        $stmtImage = $pdo->prepare($sqlSelectImage);
        $stmtImage->bindParam(1, $id);
        $stmtImage->execute();
        $imgSrc = $stmtImage->fetch(PDO::FETCH_ASSOC);
        ?>
        <div class="post w-100 bg-light p-5">
            <h1><?php echo $post['title'] ?></h1>
            <p><?php echo $post['date'] ?></p>
            <p><?php echo $post['content'] ?></p>

            <?php
            if ($imgSrc) {
                echo '<img class="img-fluid" style="max-width: 100px;" src="data:image/jpeg;base64,' . base64_encode($imgSrc['data']) . '" />';
            }
            ?>
        </div>
        <?php
    } else {
        echo "Post not found";
    }
} else {
    echo "Post not found";
}
?>