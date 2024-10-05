<?php

require_once(__DIR__."/../../DbConnect/connect.php");
require_once(__DIR__."/ComponentsFetch.php");
use \cms\DbConnect\connect;
use components\ComponentsFetch\ComponentsFetch;

$db = \cms\DbConnect\connect::getInstance()->getConnection();



session_start();

// loadComponentFields.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['component_id'])) {

        $componentId = $_POST['component_id'];
        $_SESSION['component_id'] = $componentId;
        $currentModule = $_SESSION['current_module_id'];
        echo '<form method="POST" action="./process.php">';
        echo ComponentsFetch::createComponent($componentId, $currentModule);
        echo '<button class="btn btn-primary mt-1" type="submit">Vytvořit</button>';
        echo '<input type="hidden" name="action" value="create">';
        echo '</form>';

}}
