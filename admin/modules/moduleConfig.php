<?php

include("../../src/DbConnect/connect.php");
include "../../src/Module/module.php";
include("../templates/cmsDefaultPage.class.php");

$out = '';
$db = \phpCms\DbConnect\connect::getInstance()->getConnection();
$moduleName = $_GET["module_name"];
$_SESSION["current_module_id"] = module::getModuleId($moduleName, $db);
$getTable = module::findModuleByName($moduleName, $db);

//print navigaton
$out .= cmsDefaultPage::buildNavTabs($moduleName);

$out .= "Konfigurace modulu";

$buildPage = new cmsDefaultPage($out);
$buildPage->buildLayout();