<?php
session_start();
if(isset($_POST["submit0"])) {
    $id_rezerwacji= $_POST["id_rezerwacji"];
    $cena= $_POST["cena"];
     $id_pracownika= $_POST["id_pracownika"];
     $id_klienta = $_POST["id_klienta"];
    include "../../classes/dbh.classes.php";
    include "../../classes/model-classes.php";
    include "../../classes/travel-contr.classes.php";

    $workerdel = new TravelContr();
    $workerdel->updateEnquiry($id_rezerwacji,$cena,$id_pracownika,$id_klienta);

    header("location: ../../admin-view/display_enquries.php?error=none");
}
if(isset($_POST["submit"])) {
    $id_rezerwacji= $_POST["id_rezerwacji"];
    include "../../classes/dbh.classes.php";
    include "../../classes/model-classes.php";
    include "../../classes/travel-contr.classes.php";

    $enqdel = new TravelContr();
    $enqdel->widthdrwawEnqyiry($id_rezerwacji);

    header("location: ../../admin-view/display_enquries.php?error=none");
}
if(isset($_POST["submit2"]) && isset($_SESSION["userid"])) {
    $id_rezerwacji = $_POST["id_rezerwacji"];
    $id_konta = $_SESSION["userid"];

    include "../../classes/dbh.classes.php";
    include "../../classes/model-classes.php";
    include "../../classes/travel-contr.classes.php";

    $travel = new TravelContr();
    $travel->cancelReservation($id_rezerwacji, $id_konta);

    header("location: ../../admin-view/display_enquries.php?error=deletedr");
}