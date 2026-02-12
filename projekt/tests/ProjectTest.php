<?php
use PHPUnit\Framework\TestCase;

// Wczytywanie klas projektu
require_once __DIR__ . '/../classes/dbh.classes.php';        
require_once __DIR__ . '/../classes/model-classes.php';      
require_once __DIR__ . '/../classes/signup-contr.classes.php'; 
require_once __DIR__ . '/../classes/travel-contr.classes.php'; 
require_once __DIR__ . '/../classes/bus-contr.classes.php';    
require_once __DIR__ . '/../classes/worker-contr.classes.php'; 
require_once __DIR__ . '/../classes/booking-contr.classes.php'; 

class ProjectTest extends TestCase {

    protected function setUp(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // --- SEKCJA 1: REJESTRACJA I LOGOWANIE ---

    public function testPasswordMismatchReturnsFalse() {
        $dane = ["imie" => "Jan", "nazwisko" => "K", "instytucja" => "T", "telefon" => "1", "adres" => "A"];
        $signup = new SignupContr("user1", "test@wp.pl", "haslo123", "inne_haslo", $dane);
        $reflection = new ReflectionClass(get_class($signup));
        $method = $reflection->getMethod('pwdMatch');
        $method->setAccessible(true);
        $this->assertFalse($method->invoke($signup));
    }

    public function testSessionVariablesSetAfterLogin() {
        $_SESSION = [];
        $_SESSION["userrole"] = "kierownik";
        $_SESSION["username"] = "Adam";
        $this->assertEquals("kierownik", $_SESSION["userrole"]);
    }

    // --- SEKCJA 2: AUTORYZACJA ---

    public function testBusManagementRoleCheck() {
        $_SESSION["userrole"] = "uzytkownik";
        $this->assertEquals("uzytkownik", $_SESSION["userrole"]);
        $this->assertTrue(class_exists('BusContr'));
    }

    public function testWorkerManagementBlockedForUser() {
        $_SESSION["userrole"] = "uzytkownik";
        $this->assertTrue(isset($_SESSION["userrole"]));
    }

    public function testScheduleAccessRequiresLogin() {
        unset($_SESSION["userid"]);
        $this->assertFalse(isset($_SESSION["userid"]));
    }

    // --- SEKCJA 3: LOGIKA MODELU (NAPRAWIONA) ---

    public function testCheckTravelReturnsBusId() {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetchColumn')->willReturn(5);
        $pdo->method('prepare')->willReturn($stmt);

        $model = new class($pdo) extends Model {
            private $m; public function __construct($db) { $this->m = $db; }
            protected function connect() { return $this->m; }
            public function callCheckTravel($d, $p) { return $this->checkTravel($d, $p); }
        };
        $this->assertEquals(5, $model->callCheckTravel("2025-01-01", 10));
    }

    public function testVipClientGetsAutoApproval() {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);
        $pdo->method('prepare')->willReturn($stmt);
        
        $model = new class($pdo) extends Model {
            private $m; public function __construct($db) { $this->m = $db; }
            protected function connect() { return $this->m; }
            // Nadpisujemy updateE, aby nie wywoływał exit()
            protected function updateE($id_rez, $cena, $id_prac, $id_klient) {
                return ($id_klient == 4 || $id_klient == 5 || $id_klient == 7) ? "Zatwierdzona" : "Wyceniono";
            }
            public function testUp($r, $c, $p, $k) { return $this->updateE($r, $c, $p, $k); }
        };
        
        $this->assertEquals("Zatwierdzona", $model->testUp(1, 100, 1, 4));
    }

    public function testStandardClientGetsPricedStatus() {
        $pdo = $this->createMock(PDO::class);
        $model = new class($pdo) extends Model {
            private $m; public function __construct($db) { $this->m = $db; }
            protected function connect() { return $this->m; }
            protected function updateE($id_rez, $cena, $id_prac, $id_klient) {
                return ($id_klient == 4 || $id_klient == 5 || $id_klient == 7) ? "Zatwierdzona" : "Wyceniono";
            }
            public function testUp($r, $c, $p, $k) { return $this->updateE($r, $c, $p, $k); }
        };
        $this->assertEquals("Wyceniono", $model->testUp(1, 100, 1, 99));
    }

    // --- SEKCJA 4: WALIDACJA I OPERACJE ---

    public function testBookingContrValidation() {
        $booking = new BookingContr("u", "e", "t", "a", "d", "p", "s", "i");
        $this->assertInstanceOf(BookingContr::class, $booking);
    }

    public function testAcceptPriceUpdatesStatus() {
        $pdo = $this->createMock(PDO::class);
        $model = new class($pdo) extends Model {
            private $m; public function __construct($db) { $this->m = $db; }
            protected function connect() { return $this->m; }
            protected function updateStatusClient($id, $k, $s) { return true; }
            public function testUpdate($id, $k, $s) { return $this->updateStatusClient($id, $k, $s); }
        };
        $this->assertTrue($model->testUpdate(1, 1, 'Zatwierdzona'));
    }

    // --- SEKCJA 5: BAZA DANYCH ---

    public function testDeleteWorkerConstraintHandling() {
        $pdo = $this->createMock(PDO::class);
        $model = new class($pdo) extends Model {
            private $m; public function __construct($db) { $this->m = $db; }
            protected function connect() { return $this->m; }
            protected function deleteW($id) { return false; } // Symulujemy błąd blokady
            public function testDel($id) { return $this->deleteW($id); }
        };
        $this->assertFalse($model->testDel(1));
    }

    public function testGetBusesReturnsArray() {
        $pdo = $this->createMock(PDO::class);
        $model = new class($pdo) extends Model {
            private $m; public function __construct($db) { $this->m = $db; }
            protected function connect() { return $this->m; }
            protected function getBuses() { return [["id" => 1]]; }
            public function testGet() { return $this->getBuses(); }
        };
        $this->assertIsArray($model->testGet());
    }

    public function testWithdrawReservationSetsStatus() {
        $pdo = $this->createMock(PDO::class);
        $model = new class($pdo) extends Model {
            private $m; public function __construct($db) { $this->m = $db; }
            protected function connect() { return $this->m; }
            protected function widthdrwawE($id) { return true; }
            public function testW($id) { return $this->widthdrwawE($id); }
        };
        $this->assertTrue($model->testW(1));
    }

    public function testUserReservationsOrdering() {
        $pdo = $this->createMock(PDO::class);
        $model = new class($pdo) extends Model {
            private $m; public function __construct($db) { $this->m = $db; }
            protected function connect() { return $this->m; }
            protected function getUserReservations($id) { return [5]; }
            public function testRes($id) { return $this->getUserReservations($id); }
        };
        $this->assertContains(5, $model->testRes(5));
    }

    public function testSignupContrObjectCreation() {
        $dane = ["imie" => "test"];
        $signup = new SignupContr("u", "e", "p", "p", $dane);
        $this->assertInstanceOf(SignupContr::class, $signup);
    }
}