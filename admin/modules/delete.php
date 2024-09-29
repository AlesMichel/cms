<?php
//deletes a module based on name
include("../templates/cmsDefaultPage.class.php");
include("../../src/DbConnect/connect.php");

    //connect to db

    $db = \phpCms\DbConnect\connect::getInstance()->getConnection();
    $out = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        $action = filter_input(INPUT_POST, "action", FILTER_SANITIZE_STRING);
        if($action == "delete"){

        }
    }

$buildPage = new cmsDefaultPage($out);
$buildPage->buildLayout();