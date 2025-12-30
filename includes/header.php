<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Garden</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="/public/fichier.css">
    <script src="https://kit.fontawesome.com/a680a19743.js" crossorigin="anonymous"></script>
</head>

<body>
<nav class="bg-[#173B2D] text-white">
        <div class="container mx-auto px-6 lg:px-20">
            <div class="flex items-center justify-between h-16">
                <div class="text-xl font-semibold">
                    Digital <span class="text-[#98CA43]">Garden</span>
                </div>
                <div class="hidden md:flex items-center gap-8 text-sm">
                    <a href="index.php" class="text-white/80 hover:text-[#98CA43] transition">Accueil</a>
                    <a href="#" class="text-white/80 hover:text-[#98CA43] transition">À propos</a>
                    <a href="#" class="text-white/80 hover:text-[#98CA43] transition">Contact</a>
                </div>
                <div class="hidden md:flex items-center gap-4">
                    <a href="login.php" class="text-white/80 hover:text-white transition">
                        Se connecter
                    </a>
                    <a href="inscrit.php"
                        class="px-4 py-2 rounded-full bg-[#98CA43] text-black font-semibold hover:bg-[#86b53c] transition">
                        S’inscrire
                    </a>
                </div>
                <div class="md:hidden">
                    <button class="text-white text-2xl">☰</button>
                </div>
            </div>
        </div>
    </nav>