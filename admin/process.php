<?php
session_start();

include("../connect.php");

if (isset($_POST["create"])) {
    $title = $_POST["title"];
    $summary = $_POST["summary"];
    $content = $_POST["content"];
    $date = $_POST["date"];

    // Insert the post
    $sqlInsert = "INSERT INTO posts(date, title, summary, content) VALUES (?, ?, ?, ?)";
    $stmtInsert = $pdo->prepare($sqlInsert);

    try {
        $stmtInsert->execute([$date, $title, $summary, $content]);
        $postId = $pdo->lastInsertId();

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $name = $_FILES['image']['name'];
            $data = file_get_contents($_FILES['image']['tmp_name']);

            // Insert the image data
            $sqlImageInsert = "INSERT INTO images (id, name, data) VALUES (?, ?, ?)";
            $stmtImageInsert = $pdo->prepare($sqlImageInsert);

            $stmtImageInsert->bindParam(1, $postId, PDO::PARAM_INT);
            $stmtImageInsert->bindParam(2, $name, PDO::PARAM_STR);
            $stmtImageInsert->bindParam(3, $data, PDO::PARAM_LOB);

            $stmtImageInsert->execute();
        }

        header("Location:index.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
}

if (isset($_POST["update"])) {
    $title = $_POST["title"];
    $summary = $_POST["summary"];
    $content = $_POST["content"];
    $date = $_POST["date"];
    $id = $_POST["id"];

    $sqlUpdate = "UPDATE posts SET title = ?, summary = ?, content = ?, date = ? WHERE posts_id = ?";
    $stmtUpdate = $pdo->prepare($sqlUpdate);

    try {
        $stmtUpdate->execute([$title, $summary, $content, $date, $id]);
        $_SESSION["update"] = "Post added successfully";
        header("Location:index.php");
        exit();
    } catch (PDOException $e) {
        die("Data not updated: " . $e->getMessage());
    }
}
?>
