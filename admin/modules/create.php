<?php
include("../templates/header.php");
?>

<div class="create-form w-100 mx-auto p-4" style="max-width: 700px;">
    <form action="process.php" method="post" enctype="multipart/form-data">
        <div class="form-field">
            <label for="moduleName">Module name</label>
            <input class="form-control" placeholder="Enter module name" type="text" name="moduleName" id="moduleName">

            <input class="form-control" placeholder="Enter table name" type="text" name="tableName" id="tableName">



            <div class="form-field mb-4">
                <input class="btn btn-primary" type="submit" value="Submit" name="create">
            </div>

        </div>
    </form>
</div>




<?php
include("../templates/footer.php");
?>
