<?php 
session_start();
include __DIR__ . '/../includes/header.php'; ?>
    <section class="bg-[#1F4E3A] min-h-screen py-16">
        <div class="container mx-auto px-6 lg:px-20">
            <div class="mb-10 text-center lg:text-left">
                <h1 class="text-4xl font-bold text-white"> 
                <i class="fas fa-smile text-3xl"></i>
                Bienvenue, <span class="text-[#98CA43]"><?=$_SESSION["username"] ?></span>
                </h1>
                <p class="text-white/70 mt-2">
                    Voici votre espace personnel Digital Garden
                </p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-10">
                <div class="bg-white rounded-3xl p-6 ">
                    <p class="text-sm text-black-500">Date d’inscription</p>
                    <p class="text-xl font-semibold text-black"><?= date("d F Y", strtotime($_SESSION["dateInscription"])) ?></p>
                </div>
                <div class="bg-white rounded-3xl p-6 ">
                    <p class="text-sm text-black-500">Dernière connexion</p>
                    <p class="text-xl font-semibold text-black">18:45</p>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <a href="themes.php"
                    class="group bg-[#98CA43] rounded-2xl p-6 text-center font-semibold hover:scale-105">
                    <i class="fas fa-seedling text-2xl"></i>
                    <p class="mt-2 text-lg">Gérer mes Thèmes</p>
                </a>
                <a href="notes.php"
                    class="group bg-white rounded-2xl p-6 text-center font-semibold text-[#1F4E3A] hover:scale-105">
                    <i class="fas fa-note-sticky text-2xl"></i>
                    <p class="mt-2 text-lg">Gérer mes Notes</p>
                </a>
                <a href="logout.php"
                    class="group bg-red-500 rounded-2xl p-6 text-center font-semibold text-white hover:scale-105">
                    <i class="fas fa-right-from-bracket text-2xl"></i>
                    <p class="mt-2 text-lg">Déconnexion</p>
                </a>
            </div>

        </div>
    </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>