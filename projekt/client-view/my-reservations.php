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
    <title>Moje Rezerwacje</title>
    <link href="../style.css" rel="stylesheet" />
    <style>
        .res-container { width: 95%; max-width: 1200px; margin: 30px auto; }
        .res-table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .res-table th, .res-table td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        .res-table th { background: #0984e3; color: white; text-transform: uppercase; font-size: 12px; }
        
        .status-pill { padding: 5px 10px; border-radius: 20px; font-size: 11px; font-weight: bold; text-transform: uppercase; }
        .status-waiting { background: #ffeaa7; color: #d35400; }
        .status-priced { background: #dff9fb; color: #0984e3; }
        .status-confirmed { background: #d4edda; color: #155724; }
        
        .price-tag { font-weight: bold; color: #27ae60; font-size: 16px; }
        .no-data { padding: 40px; text-align: center; background: white; border-radius: 8px; }
    </style>
</head>
<body>
<header>
    <ul class="menu-member">
        <li>Witaj, <strong><?php echo $_SESSION["username"]; ?></strong></li>
        <li><a href="enquiry.php">Nowe Zapytanie</a></li>
        <li><a href="../index.php">Menu</a></li>
        <li><a href="../includes/logout.inc.php" class="header-login-a">WYLOGUJ</a></li>
    </ul>
</header>

<section class="res-container">
    <h3>Twoje Zapytania i Rezerwacje</h3>

    <?php if(!empty($myReservations)): ?>
        <table class="res-table">
            <thead>
                <tr>
                    <th>Data Przejazdu</th>
                    <th>Trasa</th>
                    <th>Osób</th>
                    <th>Status</th>
                    <th>Cena</th>
                    <th>Pojazd</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($myReservations as $res): 
                    $dt = new DateTime($res['data_przejazdu']);
                    $statusClass = ($res['status'] == "Oczekuje na wycenę") ? "status-waiting" : "status-priced";
                ?>
                <tr>
                    <td>
                        <strong><?php echo $dt->format('d.m.Y'); ?></strong><br>
                        <small><?php echo $dt->format('H:i'); ?></small>
                    </td>
                    <td>
                        <?php echo $res['miasto_z']; ?> <br> 
                        <span style="color: #636e72;">&rarr; <?php echo $res['miasto_do']; ?></span>
                    </td>
                    <td><?php echo $res['liczba_osob']; ?></td>
                    <td><span class="status-pill <?php echo $statusClass; ?>"><?php echo $res['status']; ?></span></td>
                    <td>
                        <span class="price-tag">
                            <?php echo ($res['cena'] > 0) ? number_format($res['cena'], 2, ',', ' ') . " PLN" : "Wycena w toku"; ?>
                        </span>
                    </td>
                    <td>
                        <?php echo (!empty($res['model'])) ? $res['model']." (".$res['rejestracja'].")" : "Zostanie przypisany"; ?>
                    </td>
                    <th>Akcje</th>

<td>
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
</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="no-data">
            <p>Nie masz jeszcze żadnych zapytań o przejazd.</p>
            <a href="enquiry.php" style="color: #0984e3;">Kliknij tutaj, aby wysłać pierwsze zapytanie.</a>
        </div>
    <?php endif; ?>
</section>
</body>
</html>