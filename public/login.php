<?php
require_once '../config/database.php';
require_once '../src/Entity/User.php';
require_once '../src/Repository/UserRepository.php';
require_once '../src/Service/AuthService.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email invalide';
    }

    if (strlen($password) < 6) {
        $errors['password'] = 'Mot de passe trop court';
    }

    if (empty($errors)) {
        $conn = new DatabaseConnection();
        $pdo = $conn->getConnection();
        $repo = new UserRepository($pdo);
        $auth = new AuthService($repo);

        if ($auth->login($email, $password)) {
            if ($_SESSION['role'] === 'Admin') {
                header("Location: dashboardAdmin.php");
            } else {
            header("Location: dashboard.php");
            exit;
        } 
    }
}
}
?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<section class="bg-[#1F4E3A]  flex items-center justify-center py-12">
    <div class="bg-white rounded-xl shadow-lg p-4 w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center text-[#1F4E3A]">Se connecter</h1>
        <form id="loginForm" class="flex flex-col gap-4" action="login.php" method="post">
            <div>
                <label for="email" class="block text-sm font-semibold mb-1">Email</label>
                <input type="email" id="email_login" name="email" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#98CA43]">
                    <?= isset($errors['email']) ? "<p class='text-red-500'>{$errors['email']}</p>" : "" ?>
            </div>
            <div>
                <label for="password" class="block text-sm font-semibold mb-1">Mot de passe</label>
                <input type="password" id="passwordlogin" name="password" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#98CA43]">
                    <?= isset($errors['password']) ? "<p class='text-red-500'>{$errors['password']}</p>" : "" ?>
            </div>
            <p id="errorMsglogin" class="text-red-500 text-sm"></p>
            <button type="submit" value="login" name="login"
                class="bg-[#98CA43] text-black font-semibold py-2 rounded-full hover:bg-[#86b53c] transition">
                Se connecter
            </button>
        </form>
        <p class="text-sm text-center mt-4 text-gray-500">
            Pas encore de compte ? <a href="register.php" class="text-[#98CA43] hover:underline">S’inscrire</a>
        </p>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
