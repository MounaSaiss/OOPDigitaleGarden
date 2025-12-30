<?php
require_once 'OOPDIGITALEGARDEN/config/database.php';

$db = new DatabaseConnection();
$pdo = $db->getConnection();
class AuthService
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function register()
    {
        $userName = trim($_POST['username']);
        $email = trim($_POST['email']);
        $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password, roleid)
             VALUES (?, ?, ?, ?)"
        );

        if ($stmt->execute([$userName, $email, $pass, 2])) {
            session_start();
            $_SESSION['username'] = $userName;
            header("Location: /Digital-Garden/digital-garden/dashboard.php");
            exit;
        }
    }

    public function login()
    {
        $stmt = $this->pdo->prepare("SELECT username, password, dateInscription, roleid
             FROM users WHERE email = ?"
        );
        $stmt->execute([$_POST['email']]);
        $user = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$user || !password_verify($_POST['password'], $user->password)) {
            echo "Email ou mot de passe incorrect";
            return;
        }

        session_start();
        $_SESSION['username'] = $user->username;
        $_SESSION['dateInscription'] = $user->dateInscription;
        $_SESSION['roleid'] = $user->roleid;

        header("Location: /Digital-Garden/digital-garden/dashboard.php");
        exit;
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();

        header("Location: /Digital-Garden/digital-garden/login.php");
        exit;
    }
}


$auth = new AuthService($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth->register();
    $auth->login();
}