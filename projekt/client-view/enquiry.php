<?php 
    session_start();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Zapytanie o przejazd</title>
    <link href="../style.css" rel="stylesheet" />
    <link href="enquiry.css" rel="stylesheet">
</head>
<body>
    <header>
        <ul class="menu-member">
            <?php if((isset($_SESSION["userid"])) && ($_SESSION["userrole"]=="kierownik")) { ?>
            <li><a href="#">Witaj, <strong><?php echo $_SESSION["username"]; ?></strong></a></li>
            <li><a href="../index.php">Menu</a></li>
            <li><a href="../includes/logout.inc.php" class="header-login-a">WYLOGUJ</a></li>
            <?php } ?>
            <?php if((isset($_SESSION["userid"])) && ($_SESSION["userrole"]=="uzytkownik")) { ?>
            <li><a href="#">Witaj, <strong><?php echo $_SESSION["username"]; ?></strong></a></li>
            <li><a href="../index.php">Menu</a></li>
            <li><a href="../includes/logout.inc.php" class="header-login-a">WYLOGUJ</a></li>
            <?php } ?>
            <?php if(!isset($_SESSION["userid"])){
            header("location: ../../client-view/enquiry.php?error=brak_autoryzacji"); }?>
        </ul>
    </header>
    <section class="wrapper">
        <div class="user-panel-card">
            <div class="user-panel-header">
                <h4>Utwórz nowe zapytanie o przejazd</h4>
                <p>Wypełnij formularz, aby otrzymać wycenę od naszego kierownika.</p>
            </div>
            <form action="../includes/client/enquiry.inc.php" method="post" class="enquiry-form">
                <div class="form-grid">
                    <input type="text" name="imie_nazwisko" placeholder="Imię i Nazwisko" required>
                    <input type="tel" name="numer_telefonu" placeholder="Numer telefonu" required>    
                    <input type="text" name="instytucja" placeholder="Instytucja / Firma" required>
                    <input type="number" name="liczba_osob" placeholder="Liczba osób" required min="1">
                    <input type="text" name="miasto_z" placeholder="Adres Początkowy" required>
                    <input type="text" name="miasto_do" placeholder="Adres Docelowy" required>
                    <div class="input-group">
                        <label style="font-size: 0.8em; color: #666;">Data i godzina wyjazdu:</label>
                        <input type="datetime-local" name="data_przejazdu" required min="<?php echo date('Y-m-d\TH:i'); ?>">
                    </div>
                    <div class="input-group">
                        <label style="font-size: 0.8em; color: #666;">Planowana godzina powrotu:</label>
                        <input type="time" name="godzina_powrotu" required>
                    </div>
                </div>
                <button type="submit" name="submit-enquiry">Wyślij zapytanie</button>
            </form>
            <hr style="margin: 30px 0; opacity: 0.2;">
            <small>* Dokładna dostępność zostanie potwierdzona po analizie zapytania przez kierownika.</small> <br>
            <small>* W przypadku wyjazdów kilkudniowych, należy dokonać rezerwacji telefonicznie.</small>
        </div>
        <?php
            if (isset($_GET["error"])) {
                if ($_GET["error"] == "pomyslna_rezerwacja") {
                    echo '<div class="alert-success">Zapytanie zostało wysłane pomyślnie! Nasz kierownik wkrótce dokona wyceny.</div>';
                }
                elseif ($_GET["error"] == "nie_pomyslna_rezerwacja") {
                    echo '<div class="alert-error">Niestety w wybranym terminie nie mamy wolnych przejazdów.</div>';
                }
            }
        ?>
    </section>
</body>
</html>