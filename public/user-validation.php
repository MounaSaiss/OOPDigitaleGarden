<?php
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Repository/UserRepository.php';


$db = new DatabaseConnection();
$pdo = $db->getConnection();
$repo = new UserRepository($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'];
    $action = $_POST['action'];

    if ($action === 'validate') {
        $repo->updateStatut($id, 'improve');
    }

    if ($action === 'block') {
        $repo->updateStatut($id, 'block');
    }
}

$users = $repo->findAll();

include __DIR__ . '/../includes/header.php';
?>

<section class="min-h-screen bg-[#1F4E3A] py-10">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl text-white font-bold mb-6">
            Validation / Blocage des comptes
        </h2>

        <table class="w-full bg-white rounded-xl overflow-hidden">
            <thead class="bg-[#98CA43] text-black">
                <tr>
                    <th class="p-3">ID</th>
                    <th class="p-3">Username</th>
                    <th class="p-3">Email</th>
                    <th class="p-3">Statut</th>
                    <th class="p-3">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($users as $user): ?>
                    <?php if ($user['role'] !== 'Admin'): ?>
                        <tr class="border-b text-center">
                            <td class="p-2"><?= $user['id'] ?></td>
                            <td class="p-2"><?= $user['username'] ?></td>
                            <td class="p-2"><?= $user['email'] ?></td>
                            <td class="p-2 font-semibold"><?= $user['statut'] ?></td>
                            <td class="p-2 flex gap-2 justify-center">

                                <?php if ($user['statut'] !== 'improve'): ?>
                                    <form method="post">
                                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                        <input type="hidden" name="action" value="validate">
                                        <button type="submit"
                                            class="bg-green-500 text-white px-3 py-1 rounded">
                                            Valider
                                        </button>
                                    </form>
                                <?php endif; ?>

                                <?php if ($user['statut'] !== 'block'): ?>
                                    <form method="post">
                                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                        <input type="hidden" name="action" value="block">
                                        <button type="submit"
                                            class="bg-red-500 text-white px-3 py-1 rounded">
                                            Bloquer
                                        </button>
                                    </form>
                                <?php endif; ?>

                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="dashboardAdmin.php" class="inline-block mt-6 text-white underline">
            ← Retour au dashboard admin
        </a>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>