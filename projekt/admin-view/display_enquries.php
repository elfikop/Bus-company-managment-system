<?php 
session_start();
if(!isset($_SESSION["userid"]) || $_SESSION["userrole"] !== "kierownik") {
    header("location: ../index.php?error=brak_autoryzacji");
    exit();
}

include "../classes/dbh.classes.php";
include "../classes/model-classes.php";
include "../classes/travel-contr.classes.php";

$enquries = new TravelContr();
$lol = $enquries->displayEnquries();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Zarządzania - Agenda</title>
    <link href="../style.css" rel="stylesheet" />
    <style>
        body { background-color: #f4f7f6; color: #333; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .calendar-list { width: 98%; max-width: 1400px; margin: 30px auto; }
        
        .table-header {
            display: flex;
            padding: 12px 20px;
            background: #dfe6e9;
            font-size: 11px;
            text-transform: uppercase;
            font-weight: bold;
            color: #636e72;
            border-radius: 8px 8px 0 0;
        }

        .agenda-row {
            display: flex;
            align-items: center;
            background: white;
            margin-bottom: 5px;
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            transition: 0.2s;
        }
        .agenda-row:hover { background: #f1f2f6; }

        .col-date   { flex: 0 0 110px; }
        .col-route  { flex: 2; padding-right: 15px; }
        .col-client { flex: 2; border-left: 1px solid #eee; padding-left: 15px; }
        .col-status { flex: 1; text-align: center; }
        .col-pricing { flex: 3; display: flex; align-items: center; gap: 10px; justify-content: flex-end; }

        .date-text { font-weight: bold; font-size: 14px; display: block; }
        .time-text { color: #0984e3; font-size: 12px; font-weight: 600; }

        .route-main { font-weight: 600; font-size: 14px; margin-bottom: 4px; }
        .route-sub  { font-size: 12px; color: #636e72; }
       
        .bus-info { font-size: 11px; color: #27ae60; font-weight: bold; margin-top: 5px; }

        .client-name { font-weight: 600; font-size: 14px; display: block; margin-bottom: 2px; }
        .client-sub  { font-size: 12px; color: #636e72; display: block; }

        .status-badge {
            font-size: 10px;
            padding: 4px 10px;
            background: #ffeaa7;
            border-radius: 4px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .input-inline {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .input-inline label { font-size: 9px; color: #b2bec3; text-transform: uppercase; font-weight: bold; }
        .input-inline input {
            padding: 8px;
            border: 1px solid #dcdde1;
            border-radius: 4px;
            width: 100px;
            font-size: 13px;
        }

        .btn-group { display: flex; gap: 5px; align-self: flex-end; }
        .btn-ok { background: #27ae60; color: white; border: none; padding: 8px 18px; border-radius: 4px; cursor: pointer; font-weight: bold; text-transform: uppercase; font-size: 11px; }
        .btn-cancel { background: #fab1a0; color: #d63031; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 11px; }
        
        .btn-ok:hover { background: #219150; }
        .btn-cancel:hover { background: #ff7675; color: white; }

        h3 { margin-bottom: 20px; font-weight: 600; }
    </style>
</head>
<body>
<header>
    <ul class="menu-member">
        <?php if((isset($_SESSION["userid"])) && ($_SESSION["userrole"]=="kierownik")) { ?>
            <li><a href="#">Witaj, <strong><?php echo $_SESSION["username"]; ?></strong></a></li>
            <li><a href="../index.php">Menu</a></li>
            <li><a href="../includes/logout.inc.php" class="header-login-a">WYLOGUJ</a></li>
        <?php } ?>
    

        <?php if(!isset($_SESSION["userid"])){
            header("location: ../../client-view/enquiry.php?error=brak_autoryzacji");
        }?>
    </ul>
</header>

<section class="calendar-list">
    <h3>Panel Zarządzania Przejazdami</h3>

    <div class="table-header">
        <div class="col-date">Termin</div>
        <div class="col-route">Szczegóły trasy</div>
        <div class="col-client">Zamawiający</div>
        <div class="col-status">Status</div>
        <div class="col-pricing" style="justify-content: center;">Wycena i Obsługa</div>
    </div>

    <?php if(!empty($lol)) {
        foreach($lol as $res) { 
            $dt = new DateTime($res['data_przejazdu']);
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
                        Autobus: <?php echo $res['model'] . " (" . $res['rejestracja'] . ")"; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-client">
                <span class="client-name"><?php echo $res['imie_nazwisko']; ?></span>
                <span class="client-sub"><?php echo $res['instytucja']; ?> | TEL: <?php echo $res['telefon']; ?></span>
            </div>

            <div class="col-status">
                <span class="status-badge"><?php echo $res['status']; ?></span>
            </div>

            <div class="col-pricing">
                <form action="../includes/admin/update-reservation.inc.php" method="POST" style="display:contents;">
                    <input type="hidden" name="id_rezerwacji" value="<?php echo $res['id_rezerwacji']; ?>">
                    <input type="hidden" name="id_klienta" value="<?php echo $res['id_konta']; ?>">
                    
                    <div class="input-inline">
                        <label>Cena (PLN)</label>
                        <input type="number" name="cena" step="0.01" value="<?php echo $res['cena']; ?>">
                    </div>

                    <div class="input-inline">
                        <label>ID Kierowcy</label>
                        <input type="number" name="id_pracownika" value="<?php echo $res['id_pracownika']; ?>">
                    </div>

                    <div class="btn-group">
                        <button type="submit0" name="submit0" value="update" class="btn-ok">Zapisz</button>
                        <button type="submit" name="submit" value="cancel" class="btn-cancel" onclick="return confirm('Czy na pewno chcesz odrzucić to zapytanie?')">Odwołaj</button>
                    </div>
                </form>
            </div>
        </div>
    <?php 
        } 
    } else {
        echo "<div style='padding:40px; background:white; text-align:center; border-radius: 0 0 8px 8px; color: #636e72;'>Brak aktywnych zapytań w systemie.</div>";
    }
    ?>
</section>

</body>
</html>