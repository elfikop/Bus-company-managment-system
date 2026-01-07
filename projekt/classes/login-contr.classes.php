 <?php
class LoginContr extends Model {
    private $uid;
    private $pwd;

    public function __construct($uid, $pwd) {
        $this->uid = $uid;
        $this->pwd = $pwd;
    }

    public function loginUser() {
        if(empty($this->uid) || empty($this->pwd)) {
            header("location: ../index.php?error=emptyinput");
            exit();
        }
        $this->getUser($this->uid, $this->pwd);
    }
}