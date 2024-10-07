<?php

//IDK how is this working but is working
require_once __DIR__ . '/../admin/config.php';
require_once ABS_PATH . "/config.php";
class buildPage {
    private String $out;
    public function __construct($out) {

        $this->out = $out;
    }

    private function buildHead(): string
    {
        return '<head>
   <meta charset="UTF-8">
   <meta name="viewport"
         content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Dashborard</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
   </head>';
    }


    public function buildLayout(){
        echo '<!doctype html>
              <html lang="en" data-bs-theme="dark">';
        echo $this->buildHead();
        echo '<div class="container my-5">';

        echo $this->out;

        echo '</div>';
        echo $this->buildFooter();
        echo '</html>';
    }

    //build boostrap nav tab menu
    //for module page
    //has urls with module actions
    //takes module name as argument

    private function buildFooter()
    {

        //get scripts
        return '<script src="'.ABS_URL.'/templates/defaultPage.js"></script>';
    }
}