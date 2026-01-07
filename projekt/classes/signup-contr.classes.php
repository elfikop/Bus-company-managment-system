<?php
class SignupContr extends Model {
    private $uid;
    private $email;
    private $pwd;
    private $pwdRepeat;
    private $dane;

    public function __construct($uid, $email, $pwd, $pwdRepeat, $dane) {
        $this->uid = $uid;
        $this->email = $email;
        $this->pwd = $pwd;
        $this->pwdRepeat = $pwdRepeat;
        $this->dane = $dane;
    }

    public function signupUser() {
        if ($this->pwdMatch() == false) {
            header("location: ../index.php?error=passwordmatch");
            exit();
        }
        $this->setUserFull($this->uid, $this->pwd, $this->email, $this->dane);
    }

    private function pwdMatch() {
        return $this->pwd === $this->pwdRepeat;
    }
}