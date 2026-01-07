<?php
session_start();
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

    if (empty($username)) {
        $errors['username'] = 'Username obligatoire';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email invalide';
    }

    if (strlen($password) < 6) {
        $errors['password'] = 'Mot de passe trop court';
    }

    if ($password !== $confirm) {
        $errors['confirm_password'] = 'Les mots de passe ne correspondent pas';
    }

    if (empty($errors)) {
        $conn = new DatabaseConnection();
        $pdo = $conn->getConnection();
        $repo = new UserRepository($pdo);
        $auth = new AuthService($repo);

        $existingUser = $repo->findByEmail($email);
        if ($existingUser) {
            $errors['email'] = 'email déja existe';
        } else {
            if ($auth->register($username, $email, $password)) {
                header("Location: login.php?registered=1");
                exit;
            } else {
                $errors['general'] = 'Une erreur est survenue lors de l\'inscription';
            }
        }
    }
}
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<section class="bg-[#1F4E3A] min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-md ">
        <h1 class="text-2xl font-bold mb-6 text-center text-[#1F4E3A]">Créer un compte</h1>

        <?php if (isset($errors['general'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars($errors['general']) ?>
            </div>
        <?php endif; ?>

        <form class="flex flex-col gap-4" action="register.php" method="post">

            <div>
                <label class="block text-sm font-semibold mb-1">Nom d'utilisateur *</label>
                <input type="text" name="username"
                    value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#98CA43]"
                    required minlength="3">
                <?php if (isset($errors['username'])): ?>
                    <p class='text-red-500 text-sm mt-1'><?= htmlspecialchars($errors['username']) ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Email *</label>
                <input type="email" name="email"
                    value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#98CA43]"
                    required>
                <?php if (isset($errors['email'])): ?>
                    <p class='text-red-500 text-sm mt-1'><?= htmlspecialchars($errors['email']) ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Mot de passe *</label>
                <input type="password" name="password"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#98CA43]"
                    required minlength="6">
                <?php if (isset($errors['password'])): ?>
                    <p class='text-red-500 text-sm mt-1'><?= htmlspecialchars($errors['password']) ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Confirmer le mot de passe *</label>
                <input type="password" name="confirm_password"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#98CA43]"
                    required minlength="6">
                <?php if (isset($errors['confirm_password'])): ?>
                    <p class='text-red-500 text-sm mt-1'><?= htmlspecialchars($errors['confirm_password']) ?></p>
                <?php endif; ?>
            </div>

            <button type="submit" class="bg-[#98CA43] hover:bg-[#87B93A] text-white py-2 rounded-full font-semibold transition duration-300 mt-4">
                S'inscrire
            </button>
        </form>

        <div class="mt-6 p-4 bg-yellow-50 rounded-lg">
            <p class="text-sm text-yellow-700">
                <strong>Note :</strong> Après inscription, votre compte sera en attente de validation par un administrateur.
            </p>
        </div>

        <p class="text-sm text-center mt-4 text-gray-500">
            Déjà un compte ? <a href="login.php" class="text-[#98CA43] hover:underline font-medium">Se connecter</a>
        </p>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>