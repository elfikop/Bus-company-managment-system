<?php
if(isset($_POST["submit"])) {
    $uid = $_POST["uid"];
    $pwd = $_POST["pwd"];

    include "../classes/dbh.classes.php";
    include "../classes/model-classes.php";
    include "../classes/login-contr.classes.php";
    
    $login = new LoginContr($uid, $pwd);
    $login->loginUser();

    header("location: ../index.php?error=none");
}