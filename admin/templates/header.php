<?php
session_start();
if(!isset($_SESSION["user"])){
    header("Location:login.php");
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashborard</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>



<body>
<div class="dashboard d-flex">
    <div class="sidebar bg-dark vh-100">
        <h1 class="bg-primary p-4"><a href="./index.php" class="text-light text-decoration-none">Administrace</a></h1>
        <div class="menu-list p-4 mt-5">
            <div class="row menu g-3">
                <a href="./create.php" class="text-light text-decoration-none col-12 btn btn-primary"><strong>Přidat záznam <i class="bi bi-bookmark-plus"></i></strong></a>
                <a href="logout.php" class="btn btn-info col-12 fw-bold">Odhlásit</a>
                <a class="btn btn-info col-12 fw-bold" href="modules/index.php">Modules</a>
            </div>
        </div>
    </div>