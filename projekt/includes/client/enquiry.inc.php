<?php
session_start();
if(isset($_POST["submit-enquiry"])) {
    $imie_nazwisko = $_POST["imie_nazwisko"];
    $telefon = $_POST["numer_telefonu"];
    $instytucja = $_POST["instytucja"];
    $miasto_z = $_POST["miasto_z"];
    $miasto_do = $_POST["miasto_do"];
    $data_przejazdu = $_POST["data_przejazdu"];
    $godzina_powrotu = $_POST["godzina_powrotu"];
    $liczba_osob = $_POST["liczba_osob"];

    include "../../classes/dbh.classes.php";
    include "../../classes/model-classes.php";
    include "../../classes/booking-contr.classes.php";
    
    $booking = new BookingContr($imie_nazwisko, $telefon,  $instytucja, $miasto_z, $miasto_do,  $data_przejazdu,  $godzina_powrotu, $liczba_osob);
    $booking->bookTravel();


    header("location: ../../client-view/enquiry.php?error=pomyslna_rezerwacja");
}