<?php
// Include Composer's autoloader
require_once __DIR__ . '/../vendor/autoload.php';

use phpCms\DbConnect\connect;

// Example usage
$db = connect::getInstance()->getConnection();
?>

    <?php
        $id = $_GET['id'];
        if($id){
            include("../connect.php");
            $sqlEdit = "SELECT * FROM posts WHERE posts_id = $id";
            $result = mysqli_query($db, $sqlEdit);

        }else{
            echo "No post found";
        }
    ?>

    <div class="create-form w-100 mx-auto p-4" style="max-width: 700px;">
        <form action="process.php" method="post">
            <?php
                while($data = mysqli_fetch_array($result)){
                    ?>


            <div class="form-field mb-4">
                <input class="form-control" placeholder="Enter Title:" type="text" name="title" id="" value="<?php echo $data['title'] ?>">
            </div>
            <div class="form-field mb-4">
                <textarea name="summary" class="form-control" id="" cols="30" rows="10" placeholder="Enter Summary:"><?php echo $data['summary'] ?></textarea>
            </div>
            <div class="form-field mb-4">
                <textarea name="content" class="form-control" id="" cols="30" rows="10" placeholder="Enter Post:"><?php echo $data['content'] ?></textarea>
            </div>
            <input  type="hidden" name="date" value="<?php echo date("Y/m/d"); ?>">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="form-field mb-4">
                <input class="btn btn-primary" type="submit" value="Submit" name="update">
            </div>
                <?php }
            ?>
        </form>
    </div>
    </div>
    </body>
    </html>

<?php
include("templates/footer.php");
?>