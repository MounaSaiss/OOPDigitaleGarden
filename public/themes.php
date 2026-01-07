<?php include __DIR__. '/../includes/header.php'; ?>
<section class="p-8 bg-[#1F4E3A] min-h-screen">
    <a href="formThemes.php" class="flex-1 bg-green-500 text-white py-1 rounded-lg  p-2 ">
        Add new thémes                                                                                                       b  
    </a>
    <h2 class="text-3xl font-bold mb-6 text-white mt-3">Liste des thèmes</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <?php foreach ($themes as $theme): ?>
            <div class="p-4 bg-[#FFFBF0] bg-opacity-70 rounded shadow flex flex-col justify-between">
                <div>
                    <h3 class="text-xl font-semibold text-black mb-2"><?= $theme['nom'] ?></h3>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-full h-2 rounded-full" style="background-color: <?= $theme['badgeCouleur'] ?>;"></span>
                    </div>
                    <p class="text-sm text-black">Notes associées : <?= $theme['total_notes'] ?></p>
                </div>
                <div class="flex gap-2 mt-4">
                    <form method="post" class="flex-1" action="formThemes.php">
                        <input type="hidden" name="theme_id" value="<?= $theme['id'] ?>">
                        <button name='edit' value='edit' class="flex-1 bg-blue-500 text-white py-1 rounded hover:bg-blue-600">Modifier</button>
                    </form>
                    <form method="post" class="flex-1" onsubmit="return confirm('Est ce que  t\'as sure supprimer cette theme');">
                        <input type="hidden" name="theme_id" value="<?= $theme['id'] ?>">
                        <button name='delete' value='delete' class="bg-red-500 text-white py-1 rounded hover:bg-red-600">Supprimer</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>