<?php
class BookingContr extends Model {
    private $imie_nazwisko;
    private $telefon;
    private $instytucja;
    private $miasto_z;
    private $miasto_do;
    private $data_przejazdu;
    private $godzina_powrotu;
    private $liczba_osob;

    public function __construct($imie_nazwisko, $telefon, $instytucja, $miasto_z, $miasto_do, $data_przejazdu, $godzina_powrotu, $liczba_osob) {
        $this->imie_nazwisko = $imie_nazwisko;
        $this->telefon = $telefon;
        $this->instytucja = $instytucja;
        $this->miasto_z = $miasto_z;
        $this->miasto_do = $miasto_do;
        $this->data_przejazdu = $data_przejazdu;
        $this->godzina_powrotu = $godzina_powrotu;
        $this->liczba_osob = $liczba_osob;
    }

    public function bookTravel() {
        
        if(empty($this->imie_nazwisko) || empty($this->telefon) || empty($this->miasto_z) || 
           empty($this->miasto_do) || empty($this->data_przejazdu) || empty($this->liczba_osob)) {
            header("location: ../../client-view/enquiry.php?error=emptyinput");
            exit();
        }
        $id_autobusu = $this->checkTravel($this->data_przejazdu, $this->liczba_osob);

        if($id_autobusu != false) {
            
            $this->book(
                $this->imie_nazwisko, 
                $this->telefon, 
                $this->instytucja, 
                $this->miasto_z, 
                $this->miasto_do, 
                $this->data_przejazdu, 
                $this->godzina_powrotu, 
                $this->liczba_osob, 
                $id_autobusu, 
                null
            );
        }
        else {
            header("location: ../../client-view/enquiry.php?error=nie_pomyslna_rezerwacja");
            exit();
        }
    }
}