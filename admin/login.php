<?php
if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    if($username == "admin" && $password == "pass"){
        session_start();
        $_SESSION["user"] = "admin";
        header("Location:modules/index.php");
    }

}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
</head>
<body>
        <div class="container">
            <div class="login-form">
                <form action="login.php" method="post">
                    <div class="form-field">
                        <input type="text" name="username" id="" placeholder="Username">
                        
                    </div>
                    
                    <div class="form-field">
                        <input type="password" name="password" id="" placeholder="Password">
                    </div>
                    <div class="from-field mb-4">
                        <input type="submit" value="Login" name="login">
                    </div>
                </form>
            </div>
        </div>
</body>
</html>