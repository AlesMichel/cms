<?php


// Include Composer's autoloader
require_once __DIR__ . '/../vendor/autoload.php';

use phpCms\DbConnect\connect;

// Example usage
$db = connect::getInstance()->getConnection();
?>


<?php include("templates/header.php"); ?>

<div class="posts-list w-100 p-5">
    <?php
    if (isset($_SESSION["create"])) {
        ?>
        <div class="alert alert-success">
            <?php echo $_SESSION["create"]; ?>
        </div>
        <?php
        unset($_SESSION["create"]);
    }
    ?>
    <?php
    if (isset($_SESSION["update"])) {
        ?>
        <div class="alert alert-success">
            <?php echo $_SESSION["update"]; ?>
        </div>
        <?php
        unset($_SESSION["update"]);
    }
    ?>
    <?php
    if (isset($_SESSION["delete"])) {
        ?>
        <div class="alert alert-success">
            <?php echo $_SESSION["delete"]; ?>
        </div>
        <?php
        unset($_SESSION["delete"]);
    }
    ?>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th style="width: 15%">Publication Date</th>
            <th style="width: 15%">Title</th>
            <th style="width: 45%">Article</th>
            <th style="width: 25%">Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sqlSelect = "SELECT * FROM posts";
        $stmtSelect = $db->query($sqlSelect);

        while ($data = $stmtSelect->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <tr>
                <td><?php echo htmlspecialchars($data["date"], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($data["title"], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($data["summary"], ENT_QUOTES, 'UTF-8'); ?></td>
                <td>
                    <a class="btn btn-info" href="view.php?id=<?php echo htmlspecialchars($data["posts_id"], ENT_QUOTES, 'UTF-8'); ?>">View</a>
                    <a class="btn btn-warning" href="edit.php?id=<?php echo htmlspecialchars($data["posts_id"], ENT_QUOTES, 'UTF-8'); ?>">Edit</a>
                    <a class="btn btn-danger" href="delete.php?id=<?php echo htmlspecialchars($data["posts_id"], ENT_QUOTES, 'UTF-8'); ?>">Delete</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<?php include("templates/footer.php"); ?>

</body>
</html>
