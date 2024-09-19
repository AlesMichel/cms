<?php
 $id = $_GET["id"];
 if($id){
     include("../connect.php");
     $sqlDelete = "DELETE FROM posts WHERE posts_id = $id";
     if(mysqli_query($conn, $sqlDelete)){

         session_start();
         $_SESSION["delete"] = "Post deleted succesfully";
         header("Location:index.php");
     }else{
         die("Something went wrong");
     }
    }
    else{
        echo "Post not found";
    }

?>