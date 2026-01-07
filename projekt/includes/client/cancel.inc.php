<?php
session_start();
if(isset($_POST["submit"]) && isset($_SESSION["userid"])) {
    $id_rezerwacji = $_POST["id_rezerwacji"];
    $id_konta = $_SESSION["userid"];

    include "../../classes/dbh.classes.php";
    include "../../classes/model-classes.php";
    include "../../classes/travel-contr.classes.php";

    $travel = new TravelContr();
    $travel->cancelReservation($id_rezerwacji, $id_konta);

    header("location: ../../client-view/my-reservations.php?error=deleted");
}