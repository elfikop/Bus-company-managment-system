<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Rezerwacji Przejazdów</title>
    <link href="style.css" rel="stylesheet" />
</head>
<body>
<header>
    <ul class="menu-member">
        <?php if(isset($_SESSION["userid"])) { ?>
            <li><a href="#">Witaj, <strong><?php echo $_SESSION["username"]; ?></strong></a></li>
            <li><a href="includes/logout.inc.php" class="header-login-a">WYLOGUJ</a></li>
        <?php } else { ?>
            <li><a href="#">REJESTRACJA</a></li>
            <li><a href="#" class="header-login-a">LOGOWANIE</a></li>
        <?php } ?>
    </ul>
</header>

<section class="index-login">
    <div class="wrapper">
        <?php if((isset($_SESSION["userid"]))&&($_SESSION["userrole"]=="kierownik")) { ?>
            <div class="user-panel-card">
                <div class="user-panel-header">
                    <h4>Panel Zarządzania</h4>
                    <p>Wybierz jedną z opcji poniżej:</p>
                </div>
                <div class="user-menu-grid">
                    <a href="admin-view/manage-workers.php" class="menu-item"><span class="menu-text">Zarządzanioe Pracownikami</span></a>
                    <a href="admin-view/manage-buses.php" class="menu-item"><span class="menu-text">Zarządzanioe Pojazdami</span></a>
                    <a href="admin-view/display_enquries.php" class="menu-item"><span class="menu-text">Rezerwacje</span></a>
                    <a href="client-view/enquiry.php" class="menu-item"><span class="menu-text">Rezerwacja Przejazdu</span></a>
                    <a href="worker-view/my-schelude.php" class="menu-item"><span class="menu-text">Mój Grafik (Kierowca)</span></a>
                </div>
                <a href="includes/logout.inc.php" class="logout-link-btn">Wyloguj się bezpiecznie</a>
            </div>
        <?php }
        elseif((isset($_SESSION["userid"]))&&($_SESSION["userrole"]=="pracownik")) { ?>
            <div class="user-panel-card">
                <div class="user-panel-header">
                    <h4>Grafik Pracownika</h4>
                    <p>Wybierz jedną z opcji poniżej:</p>
                </div>
                <div class="user-menu-grid">
                    <a href="worker-view/my-schelude.php" class="menu-item"><span class="menu-text">Wyświetl Grafik</span></a>
                </div>
                <a href="includes/logout.inc.php" class="logout-link-btn">Wyloguj się bezpiecznie</a>
            </div>
        <?php }
        elseif((isset($_SESSION["userid"]))&&($_SESSION["userrole"]=="uzytkownik")) { ?>
            <div class="user-panel-card">
                <div class="user-panel-header">
                    <h4>Panel Uzytkownika</h4>
                    <p>Wybierz jedną z opcji poniżej:</p>
                </div>
                <div class="user-menu-grid">
                    <a href="client-view/enquiry.php" class="menu-item"><span class="menu-text">Utwórz Zapytanie</span></a>
                    <a href="client-view/my-reservations.php" class="menu-item"><span class="menu-text">Moje Rezerwacje</span></a>
                </div>
                <a href="includes/logout.inc.php" class="logout-link-btn">Wyloguj się bezpiecznie</a>
            </div>
        <?php }
        elseif(!isset($_SESSION["userid"])) { ?>
            <div class="index-login-signup">
                <h4>Rejestracja</h4>
                <form action="includes/signup.inc.php" method="post">
                    <input type="text" name="uid" placeholder="Login (użytkownik)" required>
                    <input type="email" name="email" placeholder="E-mail" required>
                    <input type="password" name="pwd" placeholder="Hasło" required>
                    <input type="password" name="pwdrepeat" placeholder="Powtórz hasło" required>
                    <hr style="margin: 10px 0; opacity: 0.1;">
                    <input type="text" name="imie" placeholder="Imię" required>
                    <input type="text" name="nazwisko" placeholder="Nazwisko" required>
                    <input type="text" name="instytucja" placeholder="Instytucja" required>
                    <input type="text" name="telefon" placeholder="Telefon" required>
                    <input type="text" name="adres" placeholder="Adres zamieszkania" required>
                    <button type="submit" name="submit">Stwórz konto</button>
                </form>
            </div>
            <div class="index-login-login">
                <h4>Logowanie</h4>
                <form action="includes/login.inc.php" method="post">
                    <input type="text" name="uid" placeholder="Login" required>
                    <input type="password" name="pwd" placeholder="Hasło" required>
                    <button type="submit" name="submit">Zaloguj się</button>
                </form>
            </div>
        <?php } ?>
    </div>
</section>
</body>
</html>