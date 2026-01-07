<?php
class TravelContr extends Model {
    private $NoR; //liczba wierszy
    public $stmt;

    private $id_rezerwacji;
    private $id_konta;
    private $miasto_z;
    private $miasto_do;
    private $data_przejazdu;
    private $liczba_osob;
   // private $id_rezerwacji;
    private $status;
    private $data_utworzenia;
    private $id_autobusu;
    private $id_pracownika;
    private $cena;

    private $nazwisko;
    private $imie;
    private $instytucja;
    private $telefon;

    private $rejestracja;
    private $model;

    

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
    public function updateEnquiry($id_rezerwacji,$cena,$id_pracownika){
        if(!isset($_SESSION["userid"])&&($_SESSION["userrole"]=="kierownik")) {
            header("location: ../index.php?error=not_kierownik");
            exit();
        }
        $tab =$this->updateE($id_rezerwacji,$cena,$id_pracownika);
    }
}