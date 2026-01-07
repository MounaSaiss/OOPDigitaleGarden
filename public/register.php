<?php
require_once '../config/database.php';
require_once '../src/Entity/User.php';
require_once '../src/Repository/UserRepository.php';
require_once '../src/Service/AuthService.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    if ($username === '') {
        $errors['username'] = 'Username obligatoire';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email invalide';
    }

    if (strlen($password) < 6) {
        $errors['password'] = 'Mot de passe trop court';
    }

    if ($password !== $confirm) {
        $errors['password'] = 'Les mots de passe ne correspondent pas';
    }

    if (empty($errors)) {
        $conn = new DatabaseConnection();
        $pdo = $conn->getConnection();
        $repo = new UserRepository($pdo);
        $auth = new AuthService($repo);

        if ($auth->register($username, $email, $password)) {
            header("Location: login.php");
            exit;
        }
    }
}
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<section class="bg-[#1F4E3A] min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-md ">
        <h1 class="text-2xl font-bold mb-6 text-center text-[#1F4E3A]">Créer un compte</h1>

        <form class="flex flex-col gap-4" action="register.php" method="post">

            <div>
                <label class="block text-sm font-semibold mb-1">Nom d’utilisateur</label>
                <input type="text" name="username" class="w-full px-4 py-2 border rounded-lg">
                <?= isset($errors['username']) ? "<p class='text-red-500'>{$errors['username']}</p>" : "" ?>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Email</label>
                <input type="email" name="email" class="w-full px-4 py-2 border rounded-lg">
                <?= isset($errors['email']) ? "<p class='text-red-500'>{$errors['email']}</p>" : "" ?>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Mot de passe</label>
                <input type="password" name="password" class="w-full px-4 py-2 border rounded-lg">
                <?= isset($errors['password']) ? "<p class='text-red-500'>{$errors['password']}</p>" : "" ?>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Confirmation</label>
                <input type="password" name="confirm_password" class="w-full px-4 py-2 border rounded-lg">
            </div>

            <button type="submit" class="bg-[#98CA43] py-2 rounded-full font-semibold">
                S’inscrire
            </button>
        </form>

        <p class="text-sm text-center mt-4 text-gray-500">
            Déjà un compte ? <a href="login.php" class="text-[#98CA43]">Se connecter</a>
        </p>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>

