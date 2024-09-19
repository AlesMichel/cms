<?php
include("templates/header.php");
?>


        <div class="create-form w-100 mx-auto p-4" style="max-width: 700px;">
            <form action="process.php" method="post" enctype="multipart/form-data">
                <div class="form-field mb-4">
                    <input class="form-control" placeholder="Enter Title:" type="text" name="title" id="">
                </div>
                <div class="form-field mb-4">
                    <textarea name="summary" class="form-control" id="" cols="30" rows="10" placeholder="Enter Summary:"></textarea>
                </div>
                <div class="form-field mb-4">
                    <textarea name="content" class="form-control" id="" cols="30" rows="10" placeholder="Enter Post:"></textarea>
                </div>
                <input  type="hidden" name="date" value="<?php echo date("Y/m/d"); ?>">

                <input type="file" name="image" />

                <div class="form-field mb-4">
                    <input class="btn btn-primary" type="submit" value="Submit" name="create">
                </div>

            </form>
        </div>
    </div>


<?php
include("templates/footer.php");
?>