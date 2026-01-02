<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Repository/NoteRepository.php';
require_once __DIR__ . '/../src/Entity/Note.php';
require_once __DIR__ . '/../src/Repository/ThemeRepository.php';

$db = new DatabaseConnection();
$conn = $db->getConnection();

$noteRepo = new NoteRepository($conn, $_SESSION['user_id']);
$themeRepo = new ThemeRepository($conn, $_SESSION['user_id']);
$themes = $themeRepo->findAll();


if (isset($_POST['ajoute'])) {
    $note = new Note(
        null,
        $_POST['titre'],
        $_POST['importance'],
        $_POST['contenu'],
        new DateTime(),
        $_POST['theme']
    );

    $noteRepo->add($note);
    header("Location: notes.php");
    exit;

}
$note = null; 
if (isset($_POST['edit'])) {
    $noteId = $_POST['note_id'];
    $note = $noteRepo->find($noteId);
}

if (isset($_POST['update'])) {
    $note = new Note(
        $_POST['note_id'],          
        $_POST['titre'],            
        $_POST['importance'],       
        $_POST['contenu'],          
        new DateTime(),             
        $_POST['theme']             
    );

    $noteRepo->update($note);       
    header("Location: notes.php");
    exit;
}


?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="flex justify-center m-12 ">
    <div class="w-[800px] bg-white/90  p-6 rounded-2xl">

        <h3 class="text-2xl font-bold mb-6 text-[#173B2D] text-center">
            Ajouter
        </h3>
        <form class="grid grid-cols-1 md:grid-cols-2 gap-4" method="post">
            <div class="flex flex-col">
                <label class="text-[#173B2D] mb-1 flex items-center gap-2">
                    <i class="fa-solid fa-tag"></i> Thème
                </label>
                <select class="border border-[#98CA43] p-2 rounded-lg focus:ring-2 focus:ring-[#4DC2C3]" name="theme" required>
                    <?php foreach ($themes as $theme): ?>
                        <option value="<?= $theme->id ?? '' ?>"><?= $theme->nom ?? '' ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="flex flex-col">
                <label class="text-[#173B2D] mb-1 flex items-center gap-2">
                    <i class="fa-solid fa-heading"></i> Titre
                </label>
                <input type="text" name="titre"
                    class="border border-[#98CA43] p-2 rounded-lg ">
            </div>
            <div class="flex flex-col">
                <label class="text-[#173B2D] mb-1 flex items-center gap-2">
                    <i class="fa-solid fa-star"></i> Importance
                </label>
                <select class="border border-[#98CA43] p-2 rounded-lg focus:ring-2 focus:ring-[#4DC2C3]" name="importance">
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                </select>
            </div>
            <div class="flex flex-col md:col-span-2">
                <label class="text-[#173B2D] mb-1 flex items-center gap-2">
                    <i class="fa-solid fa-align-left"></i> Contenu
                </label>
                <textarea rows="4" placeholder="Résumé ou contenu..." name="contenu"
                    class="border border-[#98CA43] p-2 rounded-lg focus:ring-2 focus:ring-[#4DC2C3]"></textarea>
            </div>
            <div class="md:col-span-2">
                <button
                    type="submit" name="ajoute" value="ajoute"
                    class="w-full bg-green-900 text-white py-2 rounded-xl">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>