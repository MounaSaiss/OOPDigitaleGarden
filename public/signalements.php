<?php
session_start();

// Vérification admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Repository/SignalementRepository.php';
require_once __DIR__ . '/../src/Repository/UserRepository.php';

$db = new DatabaseConnection();
$pdo = $db->getConnection();
$signalementRepo = new SignalementRepository($pdo);

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $signalementId = (int)($_POST['id'] ?? 0);
    $adminId = $_SESSION['user_id'];

    switch ($action) {
        case 'traiter':
            $statut = $_POST['statut'];
            if ($signalementId && in_array($statut, ['accepted', 'refused'])) {
                $signalementRepo->traiterSignalement($signalementId, $statut,$adminId);
                $_SESSION['message'] = "Signalement traité avec succès";
            }
            break;

        case 'supprimer':
            // Ajouter une méthode delete si nécessaire
            break;
    }

    header('Location: signalements.php');
    exit;
}

// Récupérer les signalements
$filter = $_GET['filter'] ?? 'all';
$signalements = [];

switch ($filter) {
    case 'waiting':
        $signalements = $signalementRepo->findByStatut('waiting');
        break;
    case 'accepted':
        $signalements = $signalementRepo->findByStatut('accepted');
        break;
    case 'refused':
        $signalements = $signalementRepo->findByStatut('refused');
        break;
    default:
        $signalements = $signalementRepo->findAllWithDetails();
        break;
}

// Statistiques
$counts = $signalementRepo->countByStatut();

include __DIR__ . '/../includes/header.php';
?>

<section class="min-h-screen bg-[#1F4E3A] py-10">
    <div class="container mx-auto px-6">
        <!-- En-tête -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-4">
            <div>
                <h2 class="text-3xl text-white font-bold">Gestion des signalements</h2>
                <p class="text-white/70 mt-1">Consultez et traitez les signalements des utilisateurs</p>
            </div>
            <a href="dashboardAdmin.php"
                class="bg-[#98CA43] hover:bg-[#87B93A] text-white px-4 py-2 rounded-lg transition duration-300">
                ← Retour dashboard
            </a>
        </div>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <?= htmlspecialchars($_SESSION['message']) ?>
                <?php unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-xl p-4 shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500">En attente</p>
                        <p class="text-2xl font-bold text-yellow-500"><?= $counts['waiting'] ?></p>
                    </div>
                    <i class="fas fa-clock text-2xl text-yellow-500"></i>
                </div>
                <?php if ($counts['waiting'] > 0): ?>
                    <a href="?filter=waiting" class="text-sm text-blue-500 hover:underline mt-2 inline-block">
                        Voir les signalements en attente
                    </a>
                <?php endif; ?>
            </div>

            <div class="bg-white rounded-xl p-4 shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500">Acceptés</p>
                        <p class="text-2xl font-bold text-green-500"><?= $counts['accepted'] ?></p>
                    </div>
                    <i class="fas fa-check-circle text-2xl text-green-500"></i>
                </div>
                <?php if ($counts['accepted'] > 0): ?>
                    <a href="?filter=accepted" class="text-sm text-blue-500 hover:underline mt-2 inline-block">
                        Voir les signalements acceptés
                    </a>
                <?php endif; ?>
            </div>

            <div class="bg-white rounded-xl p-4 shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500">Refusés</p>
                        <p class="text-2xl font-bold text-red-500"><?= $counts['refused'] ?></p>
                    </div>
                    <i class="fas fa-times-circle text-2xl text-red-500"></i>
                </div>
                <?php if ($counts['refused'] > 0): ?>
                    <a href="?filter=refused" class="text-sm text-blue-500 hover:underline mt-2 inline-block">
                        Voir les signalements refusés
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Filtres -->
        <div class="mb-6 bg-white rounded-xl p-4 shadow">
            <div class="flex flex-wrap gap-2">
                <a href="?filter=all"
                    class="px-4 py-2 rounded-lg <?= $filter === 'all' ? 'bg-[#98CA43] text-white' : 'bg-gray-200 text-gray-700' ?>">
                    Tous (<?= array_sum($counts) ?>)
                </a>
                <a href="?filter=waiting"
                    class="px-4 py-2 rounded-lg <?= $filter === 'waiting' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-700' ?>">
                    En attente (<?= $counts['waiting'] ?>)
                </a>
                <a href="?filter=accepted"
                    class="px-4 py-2 rounded-lg <?= $filter === 'accepted' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-700' ?>">
                    Acceptés (<?= $counts['accepted'] ?>)
                </a>
                <a href="?filter=refused"
                    class="px-4 py-2 rounded-lg <?= $filter === 'refused' ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-700' ?>">
                    Refusés (<?= $counts['refused'] ?>)
                </a>
            </div>
        </div>

        <!-- Tableau des signalements -->
        <div class="bg-white rounded-xl overflow-hidden shadow-lg">
            <?php if (empty($signalements)): ?>
                <div class="p-8 text-center">
                    <i class="fas fa-flag text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">Aucun signalement trouvé</p>
                    <p class="text-gray-400 mt-2">Tous les signalements sont traités !</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-[#98CA43] text-black">
                            <tr>
                                <th class="p-4 text-left">ID</th>
                                <th class="p-4 text-left">Type</th>
                                <th class="p-4 text-left">Raison</th>
                                <th class="p-4 text-left">Signalé par</th>
                                <th class="p-4 text-left">Statut</th>
                                <th class="p-4 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($signalements as $signalement): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-4">#<?= $signalement['id'] ?></td>
                                    <td class="p-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                            <?= $signalement['type'] === 'note' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' ?>">
                                            <?= htmlspecialchars($signalement['type']) ?>
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        <?php if ($signalement['reporter_username']): ?>
                                            <div>
                                                <p class="font-medium"><?= htmlspecialchars($signalement['reporter_username']) ?></p>
                                                <p class="text-sm text-gray-500"><?= htmlspecialchars($signalement['reporter_email']) ?></p>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-gray-400">Anonyme</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-4">
                                        <?php if ($signalement['type'] === 'note' && $signalement['note_titre']): ?>
                                            <div>
                                                <p class="font-medium"><?= htmlspecialchars($signalement['note_titre']) ?></p>
                                                <p class="text-sm text-gray-500">Note</p>
                                            </div>
                                        <?php elseif ($signalement['reported_username']): ?>
                                            <div>
                                                <p class="font-medium"><?= htmlspecialchars($signalement['reported_username']) ?></p>
                                                <p class="text-sm text-gray-500">Utilisateur</p>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-gray-400">Non spécifié</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                            <?= $signalement['statut'] === 'accepted' ? 'bg-green-100 text-green-800' : ($signalement['statut'] === 'refused' ? 'bg-red-100 text-red-800' :
                                                    'bg-yellow-100 text-yellow-800') ?>">
                                            <?= htmlspecialchars($signalement['statut']) ?>
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        <div class="flex flex-col gap-2">
                                            <!-- Voir les détails -->
                                            <button onclick="openModal(<?= $signalement['id'] ?>)"
                                                class="text-blue-500 hover:text-blue-700 text-sm">
                                                <i class="fas fa-eye mr-1"></i> Voir
                                            </button>

                                            <!-- Actions pour les signalements en attente -->
                                            <?php if ($signalement['statut'] === 'waiting'): ?>
                                                <form method="POST" class="inline">
                                                    <input type="hidden" name="id" value="<?= $signalement['id'] ?>">
                                                    <input type="hidden" name="action" value="traiter">
                                                    <input type="hidden" name="statut" value="accepted">
                                                    <button type="submit"
                                                        class="text-green-500 hover:text-green-700 text-sm">
                                                        <i class="fas fa-check mr-1"></i> Accepter
                                                    </button>
                                                </form>

                                                <form method="POST" class="inline">
                                                    <input type="hidden" name="id" value="<?= $signalement['id'] ?>">
                                                    <input type="hidden" name="action" value="traiter">
                                                    <input type="hidden" name="statut" value="refused">
                                                    <button type="submit"
                                                        class="text-red-500 hover:text-red-700 text-sm"
                                                        onclick="return confirm('Refuser ce signalement ?')">
                                                        <i class="fas fa-times mr-1"></i> Refuser
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Modal pour voir les détails -->
        <div id="signalementModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center p-4 z-50">
            <div class="bg-white rounded-xl w-full max-w-2xl max-h-[80vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold">Détails du signalement</h3>
                        <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Script pour la modal -->
<script>
    function openModal(signalementId) {
        fetch(`get_signalement_details.php?id=${signalementId}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('modalContent').innerHTML = html;
                document.getElementById('signalementModal').classList.remove('hidden');
                document.getElementById('signalementModal').classList.add('flex');
            });
    }

    function closeModal() {
        document.getElementById('signalementModal').classList.add('hidden');
        document.getElementById('signalementModal').classList.remove('flex');
    }

    // Fermer la modal en cliquant en dehors
    document.getElementById('signalementModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>