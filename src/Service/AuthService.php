<?php

class AuthService
{
    private UserRepository $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function register(string $username, string $email, string $password): bool
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $user = new User(
            $username,
            $email,
            $hashedPassword,
            'waiting',
            'Garden'
        );

        return $this->userRepo->insert($user);
    }

    public function login(string $email, string $password): bool
    {
        $data = $this->userRepo->findByEmail($email);

        if (!$data) {
            return false;
        }

        // if ($data['statut'] !== 'improve') {
        //     return false;
        // }

        if (!password_verify($password, $data['password'])) {
            return false;
        }

        session_start();

        $_SESSION['user_id'] = $data['id'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['role'] = $data['role'];
        $_SESSION['dateInscription'] = $data['dateInscription'];

        return true;
    }

    public static function logout(): void
    {
        session_start();
        session_unset();
        session_destroy();
    }
}
