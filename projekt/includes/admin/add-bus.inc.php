<?php
session_start();
if(isset($_POST["submit"])) {
    $rejestracja    = $_POST["rejestracja"];
    $marka          = $_POST["marka"];
    $model          = $_POST["model"];
    $liczba_miejsc  = $_POST["liczba_miejsc"];

    include "../../classes/dbh.classes.php";
    include "../../classes/model-classes.php";
    include "../../classes/bus-contr.classes.php";

    $busContr = new BusContr();
    $busContr->addBus($rejestracja, $marka, $model, $liczba_miejsc);

    header("location: ../../admin-view/manage-buses.php?error=none");
}