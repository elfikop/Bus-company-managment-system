<?php
session_start();
if(isset($_POST["submit"])) {
    $id_autobusu = $_POST["id_autobusu"];

    include "../../classes/dbh.classes.php";
    include "../../classes/model-classes.php";
    include "../../classes/bus-contr.classes.php";

    $busContr = new BusContr();
    $busContr->deleteBus($id_autobusu);

    header("location: ../../admin-view/manage-buses.php?error=none");
}