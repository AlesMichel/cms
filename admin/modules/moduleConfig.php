<?php

include("../../src/DbConnect/connect.php");
include "../../src/Module/module.php";
include("../templates/cmsDefaultPage.class.php");

$out = '';
$db = \phpCms\DbConnect\connect::getInstance()->getConnection();
$moduleName = $_GET["module_name"];
$_SESSION["current_module_id"] = module::getModuleId($moduleName, $db);
$getTable = module::findModuleByName($moduleName, $db);

$_SESSION['module_name'] = $moduleName;

//print navigaton
$out .= cmsDefaultPage::buildNavTabs($moduleName);

$out .= '<h5>Konfigurace modulu</h5>';

$out .= "<form class='mt-3' method='post' action='process.php' >
            
            
            <input type='hidden' name='action' value='delete' /> 
            
            <button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#staticBackdrop'>Smazat</button>
            
            



       <div class='modal fade modal-sm' id='staticBackdrop' data-bs-backdrop='static' data-bs-keyboard='false' tabindex='-1' aria-labelledby='staticBackdropLabel' aria-hidden='true'>
  <div class='modal-dialog'>
    <div class='modal-content'>
      <div class='modal-header text-center'>
        <h1 class='modal-title fs-5' id='staticBackdropLabel'>Smazat modul?</h1>
        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
      </div>

      <div class='modal-footer justify-content-start'>
        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Zrušit</button>
        <input class='btn btn-danger' type='submit' value='Smazat' />
      </div>
    </div>
  </div>
</div>



        </form>";





$buildPage = new cmsDefaultPage($out);
$buildPage->buildLayout();