<?php
session_start();

if (!isset($_SESSION["userid"]) || $_SESSION["userrole"] !== "kierownik") {
    header("location: ../index.php?error=brak_autoryzacji");
    exit();
}

include "../classes/dbh.classes.php";
include "../classes/model-classes.php";
include "../classes/worker-contr.classes.php";

$workerContr = new WorkerContr();
$workers = $workerContr->displayWorkers();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<title>Pracownicy</title>
<link href="../style.css" rel="stylesheet">
<link href="manage-workers.css" rel="stylesheet">

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

<h3>Lista pracowników</h3>

<table>
<thead>
<tr>
    <th>ID</th>
    <th>Login</th>
    <th>Rola</th>
    <th>Imię</th>
    <th>Nazwisko</th>
    <th>PESEL</th>
    <th>Telefon</th>
    <th>Email</th>
    <th>Adres</th>
    <th>Akcje</th>
</tr>
</thead>

<tbody>

<?php foreach ($workers as $w): ?>
<tr>
    <td><?= $w['id_pracownika'] ?></td>
    <td><?= $w['login'] ?></td>
    <td><?= $w['rola'] ?></td>
    <td><?= $w['imie'] ?></td>
    <td><?= $w['nazwisko'] ?></td>
    <td><?= $w['pesel'] ?></td>
    <td><?= $w['telefon'] ?></td>
    <td><?= $w['email'] ?></td>
    <td><?= $w['adres'] ?></td>
    <td>
        <form action="../includes/admin/delete-worker.inc.php" method="post">
            <input type="hidden" name="id_konta" value="<?= $w['id_konta'] ?>">
            <button class="btn btn-del" name="submit">
                Usuń
            </button>
        </form>
    </td>
</tr>
<?php endforeach; ?>

<tr>
<form action="../includes/admin/add-worker.inc.php" method="post">
    <td>—</td>
    <td><input name="login" required></td>
    <td>
        <select name="stanowisko">
            <option value="pracownik">pracownik</option>
            <option value="kierownik">kierownik</option>
        </select>
    </td>
    <td><input name="imie" required></td>
    <td><input name="nazwisko" required></td>
    <td><input name="pesel" required></td>
    <td><input name="telefon"></td>
    <td><input name="email"></td>
    <td><input name="adres"></td>
    <td>
        <input type="password" name="pwd" placeholder="Hasło" required>
        <button class="btn btn-add" name="submit">Dodaj</button>
    </td>
</form>
</tr>

</tbody>
</table>

</body>
</html>
