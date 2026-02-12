<?php 
session_start();
if(!isset($_SESSION["userid"]) || $_SESSION["userrole"] !== "uzytkownik") {
    header("location: ../index.php?error=brak_autoryzacji");
    exit();
}

include "../classes/dbh.classes.php";
include "../classes/model-classes.php";
include "../classes/travel-contr.classes.php";

$travelContr = new TravelContr();
$myReservations = $travelContr->displayUserReservations($_SESSION["userid"]);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moje Rezerwacje - System</title>
    <link href="../style.css" rel="stylesheet" />
    <link href="my-reservations.css" rel="stylesheet" />
</head>
<body>
<header>
    <ul class="menu-member">
        <li><a Witaj, <strong><?php echo $_SESSION["username"]; ?></strong></a></li>
        <li><a href="../index.php">Menu</a></li>
        <li><a href="../includes/logout.inc.php" class="header-login-a">WYLOGUJ</a></li>
    </ul>
</header>

<section class="calendar-list">
    <h3>Twoje Zapytania i Rezerwacje</h3>
    <div class="table-header">
        <div class="col-date">Termin</div>
        <div class="col-route">Szczegóły trasy</div>
        <div class="col-status">Status</div>
        <div class="col-pricing">Wycena i Akcje</div>
    </div>

    <?php if(!empty($myReservations)): ?>
        <?php foreach($myReservations as $res): 
            $dt = new DateTime($res['data_przejazdu']);
            $statusClass = "status-waiting"; // Oczekuje na wycenę
            if($res['status'] == "Wyceniono")
                 $statusClass = "status-priced";
            if($res['status'] == "Zatwierdzona")
                 $statusClass = "status-confirmed";
        ?>
    <div class="agenda-row">
        <div class="col-date">
            <span class="date-text"><?php echo $dt->format('d.m.Y'); ?></span>
            <span class="time-text">GODZ. <?php echo $dt->format('H:i'); ?></span>
        </div>
        <div class="col-route">
            <div class="route-main"><?php echo $res['miasto_z']; ?> DO <?php echo $res['miasto_do']; ?></div>
                <div class="route-sub">
                    Liczba osób: <?php echo $res['liczba_osob']; ?> 
                    <?php if(!empty($res['godzina_powrotu'])) echo " | Powrót: ".substr($res['godzina_powrotu'], 0, 5); ?>
                </div>
                <?php if(!empty($res['rejestracja'])): ?>
                <div class="bus-info">
                    Przypisany pojazd: <?php echo $res['model'] . " (" . $res['rejestracja'] . ")"; ?>
                </div>
                <?php endif; ?>
            </div>

            <div class="col-status">
                <span class="status-pill <?php echo $statusClass; ?>">
                <?php echo $res['status']; ?>
                </span>
            </div>

            <div class="col-pricing">
                <div class="price-display">
                    <span class="price-label">Koszt przejazdu</span>
                    <span class="price-val">
                    <?php echo ($res['cena'] > 0) ? number_format($res['cena'], 2, ',', ' ') . " PLN" : "W trakcie wyceny"; ?>
                    </span>
                </div>

                <div class="btn-group">
                    <?php if($res['status'] == "Wyceniono"): ?>
                    <form action="../includes/client/ack-price.inc.php" method="POST" style="display:inline;">
                        <input type="hidden" name="id_rezerwacji" value="<?php echo $res['id_rezerwacji']; ?>">
                        <button type="submit" name="submit" class="btn-ok">Akceptuj</button>
                    </form>
                    <?php endif; ?>
                    <form action="../includes/client/cancel.inc.php" method="POST" style="display:inline;">
                    <input type="hidden" name="id_rezerwacji" value="<?php echo $res['id_rezerwacji']; ?>">
                        <button type="submit" name="submit" class="btn-cancel" onclick="return confirm('Czy na pewno chcesz usunąć to zapytanie?')">Usuń</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
        <div style="padding:40px; background:white; text-align:center; border-radius: 0 0 8px 8px; color: #636e72;">
            Nie masz jeszcze żadnych zapytań. <a href="enquiry.php" style="color: #0984e3; font-weight: bold;">Kliknij tutaj, aby wysłać pierwsze zapytanie.</a>
        </div>
        <?php endif; ?>
    </div>
</section>
</body>
</html>