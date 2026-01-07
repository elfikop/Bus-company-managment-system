<?php
if(isset($_POST["submit"])) {
    $id_konta= $_POST["id_konta"];
    include "../../classes/dbh.classes.php";
    include "../../classes/model-classes.php";
    include "../../classes/worker-contr.classes.php";
    session_start();
    $workerdel = new WorkerContr();
    $workerdel->deleteWorker($id_konta);

    header("location: ../../admin-view/manage-workers.php?error=none");
}