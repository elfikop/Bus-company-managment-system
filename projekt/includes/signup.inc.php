<?php
if(isset($_POST["submit"])) {
    $uid = $_POST["uid"];
    $email = $_POST["email"];
    $pwd = $_POST["pwd"];
    $pwdRepeat = $_POST["pwdrepeat"];
    
    $daneOsobowe = [
        "imie" => $_POST["imie"],
        "nazwisko" => $_POST["nazwisko"],
        "instytucja" => $_POST["instytucja"],
        "telefon" => $_POST["telefon"],
        "adres" => $_POST["adres"]
    ];

    include "../classes/dbh.classes.php";
    include "../classes/model-classes.php";
    include "../classes/signup-contr.classes.php";
    
    $signup = new SignupContr($uid, $email, $pwd, $pwdRepeat, $daneOsobowe);
    $signup->signupUser();

    header("location: ../index.php?error=none");
}