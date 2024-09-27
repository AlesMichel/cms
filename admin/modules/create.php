<?php
include("../../src/DbConnect/connect.php");
include "../../src/Module/module.php";
include("../templates/cmsDefaultPage.class.php");

$out = '<div class="create-form w-100 mx-auto p-4" style="max-width: 700px;">
    <form action="process.php" method="post" enctype="multipart/form-data">
        <div class="form-field">
            
            <h5>Nový modul</h5>
                <input class="form-control" placeholder="Název modulu" type="text" name="moduleName" id="moduleName" required>
                <input class="form-control" placeholder="Název modulové tabulky" type="text" name="tableName" id="tableName" required>
            <div class="form-field mt-4">
                <input class="btn btn-primary" type="submit" value="Vytvořit" name="create">
            </div>

        </div>
    </form>
</div>';



$buildPage = new cmsDefaultPage($out);
$buildPage->buildLayout();