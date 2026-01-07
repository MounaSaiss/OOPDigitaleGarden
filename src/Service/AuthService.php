<?php

class AuthService
{
    private UserRepository $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function register(string $username, string $email, string $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $user = new User(
            $username,
            $email,
            $hashedPassword,
        );

        return $this->userRepo->insert($user);
    }

    public function login(string $email, string $password)
    {
        $userData = $this->userRepo->findByEmail($email);
        if (!$userData) {
            return false;
        }
        if ($userData['statut'] !== 'improve') {
            return false;
        }

        if (!password_verify($password, $userData['password'])) {
            return false;
        }

        session_start();

        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['username'] = $userData['username'];
        $_SESSION['email'] = $userData['email'];
        $_SESSION['role'] = $userData['role'];
        $_SESSION['statut'] = $userData['statut'];
        $_SESSION['dateInscription'] = $userData['dateInscription'] ?? null;
        $_SESSION['logged_in'] = true;
        return true;
    }

    public static function logout(): void
    {
        session_start();
        session_unset();
        session_destroy();
    }
    public static function isAuthenticated(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    public static function isAdmin(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['role']) && $_SESSION['role'] === 'Admin';
    }
}
