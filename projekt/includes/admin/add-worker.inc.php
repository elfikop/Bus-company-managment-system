<?php
if(isset($_POST["submit"])) {
    $login      = $_POST["login"];
    $pwd        = $_POST["pwd"];
    $imie       = $_POST["imie"];
    $nazwisko   = $_POST["nazwisko"];
    $pesel      = $_POST["pesel"];
    $telefon    = $_POST["telefon"];
    $email      = $_POST["email"];
    $adres      = $_POST["adres"];
    $stanowisko = $_POST["stanowisko"];

// hashowanie hasła
    $pwdHash = password_hash($pwd, PASSWORD_DEFAULT);

    include "../../classes/dbh.classes.php";
    include "../../classes/model-classes.php";
    include "../../classes/worker-contr.classes.php";

    $workeradd = new WorkerContr();
    $workeradd->setA( $login,$pwdHash,$imie,$nazwisko,$pesel,$telefon,$email,$adres,$stanowisko);
    $workeradd->addWorker();

    header("location: ../../index.php?error=none");
}