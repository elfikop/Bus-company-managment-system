<?php 
session_start();
// Sprawdzenie czy zalogowany użytkownik to pracownik lub kierownik
if(!isset($_SESSION["userid"]) || ($_SESSION["userrole"] !== "pracownik" && $_SESSION["userrole"] !== "kierownik")) {
    header("location: ../index.php?error=brak_autoryzacji");
    exit();
}

include "../classes/dbh.classes.php";
include "../classes/model-classes.php";
include "../classes/worker-contr.classes.php";

$workerContr = new WorkerContr();
$schedule = $workerContr->displaySchedule($_SESSION["userid"]);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Mój Grafik - Kierowca</title>
    <link href="../style.css" rel="stylesheet" />
    <style>
        .schedule-container { width: 95%; max-width: 1000px; margin: 30px auto; }
        .job-card { 
            background: white; 
            padding: 20px; 
            margin-bottom: 15px; 
            border-radius: 8px; 
            border-left: 5px solid #0984e3;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .job-info h4 { margin: 0; color: #2d3436; font-size: 18px; }
        .job-date { font-weight: bold; color: #0984e3; }
        .job-bus { font-size: 12px; color: #27ae60; margin-top: 5px; font-weight: bold; }
        .job-details { font-size: 14px; color: #636e72; margin-top: 5px; }
        .no-jobs { text-align: center; padding: 50px; background: white; border-radius: 8px; }
    </style>
</head>
<body>
<header>
    <ul class="menu-member">
        <li>Witaj, <strong><?php echo $_SESSION["username"]; ?></strong></li>
        <li><a href="../index.php">Menu</a></li>
        <li><a href="../includes/logout.inc.php" class="header-login-a">WYLOGUJ</a></li>
    </ul>
</header>

<section class="schedule-container">
    <h3>Twój Grafik Przejazdów</h3>

    <?php if(!empty($schedule)): ?>
        <?php foreach($schedule as $job): 
            $dt = new DateTime($job['data_przejazdu']);
        ?>
            <div class="job-card">
                <div class="job-info">
                    <span class="job-date"><?php echo $dt->format('d.m.Y'); ?> | Godz: <?php echo $dt->format('H:i'); ?></span>
                    <h4><?php echo $job['miasto_z']; ?> &rarr; <?php echo $job['miasto_do']; ?></h4>
                    <div class="job-details">
                        Liczba osób: <?php echo $job['liczba_osob']; ?> 
                        <?php if(!empty($job['godzina_powrotu'])) echo " | Powrót: ".substr($job['godzina_powrotu'], 0, 5); ?>
                    </div>
                    <div class="job-bus">
                        Pojazd: <?php echo $job['marka'] . " " . $job['model'] . " (" . $job['rejestracja'] . ")"; ?>
                    </div>
                </div>
                <div class="job-contact" style="text-align: right; font-size: 13px;">
                    <strong>Klient:</strong> <?php echo $job['imie_nazwisko']; ?><br>
                    <strong>Tel:</strong> <?php echo $job['telefon']; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="no-jobs">
            <p>Obecnie nie masz przypisanych żadnych zatwierdzonych przejazdów.</p>
        </div>
    <?php endif; ?>
</section>
</body>
</html>