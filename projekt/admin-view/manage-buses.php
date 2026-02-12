<?php 
session_start();
if(!isset($_SESSION["userid"]) || $_SESSION["userrole"] !== "kierownik") {
    header("location: ../index.php?error=brak_autoryzacji");
    exit();
}

include "../classes/dbh.classes.php";
include "../classes/model-classes.php";
include "../classes/bus-contr.classes.php";

$busContr = new BusContr();
$buses = $busContr->displayBuses();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Zarządzanie Pojazdami</title>
    <link href="../style.css" rel="stylesheet" />
    <link href="manage-buses.css" rel="stylesheet"/>
</head>
<body>
<header>
    <ul class="menu-member">
        <li><a href="#">Witaj, <strong><?php echo $_SESSION["username"]; ?></strong></a></li>
        <li><a href="../index.php">Menu</a></li>
        <li><a href="../includes/logout.inc.php" class="header-login-a">WYLOGUJ</a></li>
    </ul>
</header>

<section style="padding: 20px;">
    <h3>Zarządzanie Flotą Pojazdów</h3>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Rejestracja</th>
                <th>Marka</th>
                <th>Model</th>
                <th>Liczba miejsc</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($buses as $bus): ?>
            <tr>
                <td><?= $bus['id_autobusu'] ?></td>
                <td><?= $bus['rejestracja'] ?></td>
                <td><?= $bus['marka'] ?></td>
                <td><?= $bus['model'] ?></td>
                <td><?= $bus['liczba_miejsc'] ?></td>
                <td>
                    <form action="../includes/admin/delete-bus.inc.php" method="POST">
                        <input type="hidden" name="id_autobusu" value="<?= $bus['id_autobusu'] ?>">
                        <button type="submit" name="submit" class="btn btn-del" onclick="return confirm('Usunąć pojazd?')">Usuń</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            
            <tr>
                <form action="../includes/admin/add-bus.inc.php" method="POST">
                    <td>—</td>
                    <td><input type="text" name="rejestracja" required placeholder="Rejestracja"></td>
                    <td><input type="text" name="marka" required placeholder="Marka"></td>
                    <td><input type="text" name="model" placeholder="Model"></td>
                    <td><input type="number" name="liczba_miejsc" required min="1"></td>
                    <td><button type="submit" name="submit" class="btn btn-add">Dodaj</button></td>
                </form>
            </tr>
        </tbody>
    </table>
</section>
</body>
</html>