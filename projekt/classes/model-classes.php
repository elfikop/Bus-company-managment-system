<?php
class Model extends Dbh {


////////////////////// SEKCJA KONT /////////////////////////////////
    protected function setUserFull($uid, $pwd, $email, $dane) {
        $db = $this->connect();
        
        // 1. Tabela konta
        $stmt = $db->prepare('INSERT INTO konta (login, haslo, rola) VALUES (?, ?, ?);');
        $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
        
        if (!$stmt->execute(array($uid, $hashedPwd, 'uzytkownik'))) {
            header("location: ../index.php?error=stmtfailed");
            exit();
        }

        $id_konta = $db->lastInsertId();

        // 2. Tabela uzytkownicy
        $stmt = $db->prepare('INSERT INTO uzytkownicy (id_konta, imie, nazwisko, instytucja, telefon, email, adres) VALUES (?, ?, ?, ?, ?, ?, ?);');
        
        if (!$stmt->execute(array($id_konta, $dane['imie'], $dane['nazwisko'], $dane['instytucja'], $dane['telefon'], $email, $dane['adres']))) {
            header("location: ../index.php?error=stmtfailed_profile");
            exit();
        }
        $stmt = null;
    }

    protected function getUser($uid, $pwd) {
        $stmt = $this->connect()->prepare('SELECT 
                                            konta.*, 
                                            COALESCE(uzytkownicy.imie, pracownicy.imie) AS imie_wyswietlane,
                                            COALESCE(uzytkownicy.id_uzytkownika, pracownicy.id_pracownika) AS id_up
                                            FROM konta 
                                            LEFT JOIN uzytkownicy ON konta.id_konta = uzytkownicy.id_konta 
                                            LEFT JOIN pracownicy ON konta.id_konta = pracownicy.id_konta 
                                             WHERE konta.login = ?;');
        if(!$stmt->execute(array($uid))) {
            header("location: ../index.php?error=stmtfailed");
            exit();
        }

        if($stmt->rowCount() == 0) {
            header("location: ../index.php?error=usernotfound");
            exit();
        }

        $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(password_verify($pwd, $user[0]["haslo"]) == false) {
            header("location: ../index.php?error=wrongpassword");
            exit();
        } else {
            session_start();
            $_SESSION["userid"] = $user[0]["id_konta"];
            $_SESSION["username"] = $user[0]["imie_wyswietlane"];
            $_SESSION["userrole"] = $user[0]["rola"];
            $_SESSION["id_up"] = $user[0]["id_up"];
        }
    }


///////////////// SEKCJA REZERWACJI PRZEJAZDU //////////////////////////////////////////
protected function checkTravel($data_przejazdu, $liczba_osob) {
        $db = $this->connect();

        $stmtautobus = $db->prepare('SELECT a.id_autobusu 
            FROM autobusy a 
            LEFT JOIN rezerwacje r ON a.id_autobusu = r.id_autobusu AND r.data_przejazdu = ? 
            WHERE a.liczba_miejsc >= ? AND r.id_rezerwacji IS NULL 
            ORDER BY a.liczba_miejsc ASC 
            LIMIT 1');

        

        $stmtautobus->execute([$data_przejazdu, $liczba_osob]);
        

        $idAutobusu = $stmtautobus->fetchColumn();
        

        $stmtautobus = null;
        

        if ($idAutobusu) {
           
            return     $idAutobusu;
                
        }
        return false;
    }

    protected function book($imie_nazwisko, $telefon, $instytucja, $miasto_z, $miasto_do, $data_przejazdu, $godzina_powrotu, $liczba_osob, $id_autobusu, $id_pracownika) {
    $id_uzytkownika = $_SESSION["userid"];
    $data_utworzenia = date("Y-m-d");

    $stmt = $this->connect()->prepare('INSERT INTO rezerwacje 
        (id_konta, imie_nazwisko, telefon, instytucja, miasto_z, miasto_do, data_przejazdu, godzina_powrotu, liczba_osob, id_autobusu, id_pracownika, status, cena, data_utworzenia) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);');

    if (!$stmt->execute([
        $id_uzytkownika, 
        $imie_nazwisko,
        $telefon,
        $instytucja,
        $miasto_z, 
        $miasto_do, 
        $data_przejazdu, 
        $godzina_powrotu,
        $liczba_osob, 
        $id_autobusu, 
        null,
        "Oczekuje na wycenę", 
        0.00, 
        $data_utworzenia
    ])) {
        $stmt = null;
        header("location: ../../client-view/enquiry.php?error=stmtfailed");
        exit();
    }

    $stmt = null;
    }
//////////////////////// SEKCJA ZARZADZANIA ZAPYTANIAMI
     protected function getEnquries() {
        $stmt = $this->connect()->prepare('SELECT r.*, a.rejestracja, a.model
                    FROM rezerwacje r LEFT JOIN autobusy a ON r.id_autobusu = a.id_autobusu WHERE r.status != "Odrzucono" ORDER BY r.data_przejazdu ASC;');
        if(!$stmt->execute()) {
            header("location: ../index.php?error=stmtfailed");
            exit();
        }

        if($stmt->rowCount() == 0) {
            header("location: ../index.php?error=no_reservations_found");
            exit();
        }

        $enquries = $stmt->fetchAll(PDO::FETCH_ASSOC);
       return $enquries;
    }
    protected function widthdrwawE($id_rezerwacji) {
    $stmt = $this->connect()->prepare('UPDATE rezerwacje SET status = "Odrzucono" WHERE id_rezerwacji = ?;');
    
    if(!$stmt->execute([$id_rezerwacji])) {
        header("location: ../index.php?error=stmtfailed");
        exit();
    }
    
    if($stmt->rowCount() == 0) {
        header("location: ../index.php?error=rezerwacja_nie_znaleziona");
        exit();
    }
}
protected function updateE($id_rezerwacji, $cena, $id_pracownika, $id_klienta) {
    if (in_array($id_klienta, [4, 5, 7])) {
        $statuszmienny = "Zatwierdzona";
    } else {
        $statuszmienny = "Wyceniono";
    }

    $stmt = $this->connect()->prepare('UPDATE rezerwacje SET status = ?, cena = ?, id_pracownika = ? WHERE id_rezerwacji = ?;');
    
    if(!$stmt->execute([$statuszmienny, $cena, $id_pracownika, $id_rezerwacji])) {
        header("location: ../index.php?error=stmtfailed");
        exit();
    }
}
    protected function deleteE($id) {
    $stmt = $this->connect()->prepare('DELETE FROM rezerwacje WHERE id_rezerwacji = ?;');
    if(!$stmt->execute([$id])) {
        header("location: ../index.php?error=stmtfailed");
        exit();
    }
    if($stmt->rowCount() == 0) {
        header("location: ../index.php?error=pracownik_nie_znaleziony");
        exit();
    }
}


////////////SEKCJA ZARZADZANIA PRACOWNIKOW /////////////////////////////////////
    protected function getWorkers(){
        $stmt = $this->connect()->prepare('SELECT p.*, k.*
                    FROM pracownicy p LEFT JOIN konta k ON p.id_konta = k.id_konta;');
        if(!$stmt->execute()) {
            header("location: ../index.php?error=stmtfailed");
            exit();
        }
        if($stmt->rowCount() == 0) {
            header("location: ../index.php?error=no_reservations_found");
            exit();
        }

        $workers = $stmt->fetchAll(PDO::FETCH_ASSOC);
       return $workers;
    }
    protected function deleteW($id) {
    try {
    
        $stmt = $this->connect()->prepare('DELETE FROM pracownicy WHERE id_konta = ?;');
        if(!$stmt->execute([$id])) {
            header("location: ../../admin-view/manage-workers.php?error=stmtfailed");
            exit();
        }

    
        $stmt = $this->connect()->prepare('DELETE FROM konta WHERE id_konta = ?;');
        if(!$stmt->execute([$id])) {
            header("location: ../../admin-view/manage-workers.php?error=stmtfailed");
            exit();
        }

       
        return true;

    } catch (PDOException $e) {
        if ($e->getCode() == '23000' || strpos($e->getMessage(), '1451') !== false) {
            header("location: ../../admin-view/manage-workers.php?error=pracownik_ma_rezerwacje");
            exit();
        } else {
            header("location: ../../admin-view/manage-workers.php?error=db_error");
            exit();
        }
    }
}
    protected function addW(
    $login,
    $hashedPwd,
    $imie,
    $nazwisko,
    $pesel,
    $telefon,
    $email,
    $adres,
    $stanowisko
) {
    $db = $this->connect();

    try {
        $stmt = $db->prepare(
            'INSERT INTO konta (login, haslo, rola) VALUES (?, ?, ?)'
        );

        if (!$stmt->execute([$login, $hashedPwd, $stanowisko])) {
            throw new Exception('insert_konta_failed');
        }

        $id_konta = $db->lastInsertId();

        // 2️⃣ TABELA PRACOWNICY
        $stmt = $db->prepare(
            'INSERT INTO pracownicy 
            (id_konta, imie, nazwisko, pesel, telefon, email, adres, stanowisko)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );

        if (!$stmt->execute([
            $id_konta,
            $imie,
            $nazwisko,
            $pesel,
            $telefon,
            $email,
            $adres,
            $stanowisko
        ])) {
            throw new Exception('insert_pracownicy_failed');
        }
        return true;

    } catch (Exception $e) {
        $db->rollBack();
        header("location: ../index.php?error=" . $e->getMessage());
        exit();
    }
}
///////////////////////////////////// SEKCJA ZARZĄDZANIA AUTOBUSAMI ////////////////////////////

protected function getBuses() {
    $stmt = $this->connect()->prepare('SELECT * FROM autobusy ORDER BY marka, model;');
    if(!$stmt->execute()) {
        header("location: ../index.php?error=stmtfailed");
        exit();
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

protected function addB($rejestracja, $marka, $model, $liczba_miejsc) {
    $stmt = $this->connect()->prepare('INSERT INTO autobusy (rejestracja, marka, model, liczba_miejsc) VALUES (?, ?, ?, ?);');
    if(!$stmt->execute([$rejestracja, $marka, $model, $liczba_miejsc])) {
        header("location: ../../admin-view/manage-buses.php?error=stmtfailed");
        exit();
    }
}

protected function deleteB($id_autobusu) {
    try {
        $stmt = $this->connect()->prepare('DELETE FROM autobusy WHERE id_autobusu = ?;');
        if(!$stmt->execute([$id_autobusu])) {
            header("location: ../../admin-view/manage-buses.php?error=stmtfailed");
            exit();
        }
    } catch (PDOException $e) {
        // Obsługa błędu klucza obcego (jeśli autobus jest przypisany do rezerwacji)
        if ($e->getCode() == '23000') {
            header("location: ../../admin-view/manage-buses.php?error=autobus_ma_rezerwacje");
            exit();
        }
    }
}
///////////////////////zarzadzanie rezerwacjami uzytkownik 
protected function getUserReservations($id_konta) {
    $stmt = $this->connect()->prepare('
        SELECT r.*, a.rejestracja, a.model 
        FROM rezerwacje r 
        LEFT JOIN autobusy a ON r.id_autobusu = a.id_autobusu 
        WHERE r.id_konta = ? 
        ORDER BY r.data_przejazdu DESC;
    ');

    if(!$stmt->execute([$id_konta])) {
        header("location: ../index.php?error=stmtfailed");
        exit();
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
protected function updateStatusClient($id_rezerwacji, $id_konta, $status) {
    $stmt = $this->connect()->prepare('UPDATE rezerwacje SET status = ? WHERE id_rezerwacji = ? AND id_konta = ?;');
    if(!$stmt->execute([$status, $id_rezerwacji, $id_konta])) {
        header("location: ../../client-view/my-reservations.php?error=stmtfailed");
        exit();
    }
}

protected function deleteReservationClient($id_rezerwacji, $id_konta) {
    $stmt = $this->connect()->prepare('DELETE FROM rezerwacje WHERE id_rezerwacji = ? AND id_konta = ?;');
    if(!$stmt->execute([$id_rezerwacji, $id_konta])) {
        header("location: ../../client-view/my-reservations.php?error=stmtfailed");
        exit();
    }
}
///////////////////////////////pracownik grafik 
// --- SEKCJA GRAFIKU KIEROWCY ---
protected function getWorkerSchedule($id_pracownika) {
    // Pobieramy ID pracownika na podstawie jego ID konta (z sesji)
    $db = $this->connect();
    $stmtId = $db->prepare('SELECT id_pracownika FROM pracownicy WHERE id_konta = ?;');
    $stmtId->execute([$id_pracownika]);
    $worker = $stmtId->fetch(PDO::FETCH_ASSOC);

    if (!$worker) return [];

    $stmt = $db->prepare('
        SELECT r.*, a.rejestracja, a.model, a.marka 
        FROM rezerwacje r 
        LEFT JOIN autobusy a ON r.id_autobusu = a.id_autobusu 
        WHERE r.id_pracownika = ? AND r.status = "Zatwierdzona"
        ORDER BY r.data_przejazdu ASC;
    ');

    if(!$stmt->execute([$worker['id_pracownika']])) {
        $stmt = null;
        header("location: ../index.php?error=stmtfailed");
        exit();
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}

