<?php
class BusContr extends Model {

    public function displayBuses() {
        if(!isset($_SESSION["userid"]) || $_SESSION["userrole"] !== "kierownik") {
            header("location: ../index.php?error=not_kierownik");
            exit();
        }
        return $this->getBuses();
    }

    public function addBus($rejestracja, $marka, $model, $liczba_miejsc) {
        if(!isset($_SESSION["userid"]) || $_SESSION["userrole"] !== "kierownik") {
            header("location: ../index.php?error=not_kierownik");
            exit();
        }
        $this->addB($rejestracja, $marka, $model, $liczba_miejsc);
    }

    public function deleteBus($id_autobusu) {
        if(!isset($_SESSION["userid"]) || $_SESSION["userrole"] !== "kierownik") {
            header("location: ../index.php?error=not_kierownik");
            exit();
        }
        $this->deleteB($id_autobusu);
    }
}