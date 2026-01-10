<?php
// pages/admin/get_signalement_details.php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    exit('Accès refusé');
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Repository/SignalementRepository.php';

$db = new DatabaseConnection();
$pdo = $db->getConnection();
$signalementRepo = new SignalementRepository($pdo);

$id = (int)($_GET['id'] ?? 0);
$signalement = $signalementRepo->findById($id);

if (!$signalement) {
    echo '<p class="text-red-500">Signalement non trouvé</p>';
    exit;
}
?>

<div class="space-y-4">
    <!-- En-tête -->
    <div class="grid grid-cols-2 gap-4">
        <div>
            <p class="text-gray-500">ID</p>
            <p class="font-bold">#<?= $signalement['id'] ?></p>
        </div>
        <div>
            <p class="text-gray-500">Type</p>
            <span class="px-3 py-1 rounded-full text-sm font-semibold 
                <?= $signalement['type'] === 'note' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' ?>">
                <?= htmlspecialchars($signalement['type']) ?>
            </span>
        </div>
    </div>

    <!-- Signalé par -->
    <div>
        <p class="text-gray-500">Signalé par</p>
        <?php if ($signalement['reporter_username']): ?>
            <p class="font-medium"><?= htmlspecialchars($signalement['reporter_username']) ?></p>
            <p class="text-sm text-gray-500"><?= htmlspecialchars($signalement['reporter_email']) ?></p>
        <?php else: ?>
            <p class="text-gray-400">Anonyme</p>
        <?php endif; ?>
    </div>

    <!-- Contenu signalé -->
    <div>
        <p class="text-gray-500">Contenu signalé</p>
        <?php if ($signalement['type'] === 'note' && $signalement['note_titre']): ?>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="font-bold"><?= htmlspecialchars($signalement['note_titre']) ?></p>
                <?php if ($signalement['note_contenu']): ?>
                    <p class="mt-2"><?= htmlspecialchars($signalement['note_contenu']) ?></p>
                <?php endif; ?>
            </div>
        <?php elseif ($signalement['reported_username']): ?>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="font-bold">Utilisateur : <?= htmlspecialchars($signalement['reported_username']) ?></p>
                <p class="text-gray-500"><?= htmlspecialchars($signalement['reported_email']) ?></p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Statut -->
    <div>
        <p class="text-gray-500">Statut</p>
        <span class="px-3 py-1 rounded-full text-sm font-semibold 
            <?= $signalement['statut'] === 'accepted' ? 'bg-green-100 text-green-800' : ($signalement['statut'] === 'refused' ? 'bg-red-100 text-red-800' :
                    'bg-yellow-100 text-yellow-800') ?>">
            <?= htmlspecialchars($signalement['statut']) ?>
        </span>
    </div>

    <!-- Formulaire de traitement (si en attente) -->
    <?php if ($signalement['statut'] === 'waiting'): ?>
        <div class="border-t pt-4 mt-4">
            <h4 class="font-bold mb-3">Traiter ce signalement</h4>
            <form method="POST" action="signalements.php" class="space-y-3">
                <input type="hidden" name="id" value="<?= $signalement['id'] ?>">
                <input type="hidden" name="action" value="traiter">

                <div>
                    <label class="block text-sm font-semibold mb-1">Commentaire (optionnel)</label>
                    <textarea name="comment" rows="3"
                        class="w-full border rounded p-2"></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="submit" name="statut" value="accepted"
                        class="flex-1 bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg">
                        <i class="fas fa-check mr-2"></i> Accepter
                    </button>
                    <button type="submit" name="statut" value="refused"
                        class="flex-1 bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg">
                        <i class="fas fa-times mr-2"></i> Refuser
                    </button>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>