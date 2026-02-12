<?php
class TravelContr extends Model {
    private $NoR; //liczba wierszy
    public $stmt;

    public function displayEnquries() {
        //session_start();
        if(!isset($_SESSION["userid"])&&($_SESSION["userrole"]=="kierownik")) {
            header("location: ../index.php?error=not_kierownik");
            exit();
        }
        $tab =$this->getEnquries();
        return $tab;
    }
    public function displayUserReservations($id_konta) {
    return $this->getUserReservations($id_konta);
    }
    public function widthdrwawEnqyiry($id_rezerwacji){
        if(!isset($_SESSION["userid"])&&($_SESSION["userrole"]=="kierownik")) {
            header("location: ../index.php?error=not_kierownik");
            exit();
        }
        $tab =$this->widthdrwawE($id_rezerwacji);
    }
    public function updateEnquiry($id_rezerwacji,$cena,$id_pracownika,$id_konta){
        if(!isset($_SESSION["userid"])&&($_SESSION["userrole"]=="kierownik")) {
            header("location: ../index.php?error=not_kierownik");
            exit();
        }
        $tab =$this->updateE($id_rezerwacji,$cena,$id_pracownika,$id_konta);
    }
    public function acceptPrice($id_rezerwacji, $id_konta) {
    
    $this->updateStatusClient($id_rezerwacji, $id_konta, 'Zatwierdzona');
}

public function cancelReservation($id_rezerwacji, $id_konta) {
    $this->deleteReservationClient($id_rezerwacji, $id_konta);
}
}