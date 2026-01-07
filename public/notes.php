<?php
session_start();
// if (!isset($_SESSION["user"])) {
//     header("Location: login.php");
//     exit;
// }

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Repository/NoteRepository.php';
require_once __DIR__ . '/../src/Repository/ThemeRepository.php';

$db = new DatabaseConnection();
$conn = $db->getConnection();
$noteRepo = new NoteRepository($conn, $_SESSION['user_id']);
$themeRepo = new ThemeRepository($conn, $_SESSION['user_id']);
$themes = $themeRepo->findAll();

$notes = $noteRepo->findAll();

if (isset($_POST['delete'])) {
    $noteId = $_POST['note_id'];
    $noteRepo->delete($noteId);
    header("Location: notes.php");
    exit;
}

include __DIR__ . '/../includes/header.php';?>
<section class="p-8 bg-[#1F4E3A]  min-h-screen">
    <a href="formNote.php" class="flex-1 bg-green-500 text-white py-1 rounded-lg  p-2  ">
        Add new notes
    </a>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 p-3">
        <?php foreach ($notes as $note): ?>
            <div class="p-4 bg-[<?= $note->color ?? '#fff' ?>] rounded-2xl shadow-lg backdrop-blur-sm flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-bold text-[#173B2D] mb-2"><?= $note->titre ?></h3>
                    <p class="text-sm text-black font-semibold mb-1">Thème : <?= $note->theme ?? 'Non défini' ?></p>
                    <span class="inline-block px-2 py-1 bg-[#FFC107]/60 rounded-full text-xs font-bold mb-2">Importance : <?= $note->importance ?></span>
                    <p class="text-sm text-[#173B2D] mb-2"><?= $note->contenu  ?></p>
                    <p class="text-xs text-black">Créée le : <?= $note->dateCreation->format('d/m/Y H:i') ?></p>
                </div>
                <div class="flex gap-2 mt-4">
                    <button class="flex-1 bg-blue-500 text-white py-1 rounded-lg ">Modifier</button>
                    <form method="post" class="flex-1" onsubmit="return confirm('Est ce que  t\'as sur  supprimer cette note');">
                        <input type="hidden" name="note_id" value="<?= $note->id ?? '' ?>">
                        <button name='delete' value='delete' class="bg-red-500 text-white py-1 rounded hover:bg-red-600">Supprimer</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>