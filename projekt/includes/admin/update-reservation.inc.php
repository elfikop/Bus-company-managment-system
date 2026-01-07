<?php
if(isset($_POST["submit0"])) {
    $id_rezerwacji= $_POST["id_rezerwacji"];
    $cena= $_POST["cena"];
     $id_pracownika= $_POST["id_pracownika"];
    include "../../classes/dbh.classes.php";
    include "../../classes/model-classes.php";
    include "../../classes/travel-contr.classes.php";

    $workerdel = new TravelContr();
    $workerdel->updateEnquiry($id_rezerwacji,$cena,$id_pracownika);

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