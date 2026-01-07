<?php
class WorkerContr extends Model {
    private $imie; 
    private $nazwisko;
    private $pesel;
    private $telefon;
    private $email;
    private $adres;
    private $stanowisko;
    private $login;
    private $pwd;

    public function setA($login, $pwd, $imie, $nazwisko, $pesel, $telefon,$email,$adres,$stanowisko){
        $this->login=$login;
        $this->pwd=$pwd;
        $this->imie=$imie;
        $this->nazwisko=$nazwisko;
        $this->pesel=$pesel;
        $this->telefon=$telefon;
        $this->email=$email;
        $this->adres=$adres;
        $this->stanowisko=$stanowisko;
    }

    public function displayWorkers() {
        //session_start();
        if(!isset($_SESSION["userid"])&&($_SESSION["userrole"]=="kierownik")) {
            header("location: ../index.php?error=not_kierownik");
            exit();
        }
        $tab =$this->getWorkers();
        return $tab;
    }
    public function deleteWorker($id_konta){
        if(!isset($_SESSION["userid"])&&($_SESSION["userrole"]=="kierownik")) {
            header("location: ../index.php?error=not_kierownik");
            exit();
        }
        $tab =$this->deleteW($id_konta);
    }
    public function addWorker(){
        if(!isset($_SESSION["userid"])&&($_SESSION["userrole"]=="kierownik")) {
            header("location: ../index.php?error=not_kierownik");
            exit();
        }
        $this->addW($this->login, $this->pwd, $this->imie, $this->nazwisko, $this->pesel, $this->telefon,$this->email,$this->adres,$this->stanowisko);
    }
}