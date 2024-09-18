<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<header class="p-4 bg-dark text-center">
    <h1>Simple block</h1>
</header>
<div class="post-list">
    <?php

    include("connect.php");


    $sqlSelect = "SELECT posts_id, date FROM posts"; // Add other columns you need
    $stmt = $pdo->prepare($sqlSelect);
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($posts as $data) {
        ?>
        <div class="row">
            <div class="col">
                <?php echo $data["date"]; ?>
            </div>
            <a href="view.php?id=<?php echo $data['posts_id']; ?>">VIEW MORE</a>
        </div>
        <?php
    }
    ?>

    <a href="admin/index.php">Admin panel</a>
</div>
</body>
</html>
