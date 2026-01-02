<?php include __DIR__ . '/../includes/header.php'; ?>
<section class="bg-[#1F4E3A] min-h-screen py-16">
    <div class="container mx-auto px-6 lg:px-20">
        <div class="mb-10 text-center lg:text-left">
            <h1 class="text-4xl font-bold text-white">
                <i class="fas fa-shield-halved text-3xl"></i>
                Dashboard <span class="text-[#98CA43]">Administrateur</span>
            </h1>
            <p class="text-white/70 mt-2">
                Backoffice de gestion des utilisateurs
            </p>
        </div>
        <!-- Actions -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            
            <!-- Users list -->
            <a href="users.php"
                class="group bg-[#98CA43] rounded-2xl p-6 text-center font-semibold hover:scale-105 transition">
                <i class="fas fa-users text-2xl"></i>
                <p class="mt-2 text-lg">Liste des utilisateurs</p>
            </a>

            <!-- Validate / Block -->
            <a href="user-validation.php"
                class="group bg-white rounded-2xl p-6 text-center font-semibold text-[#1F4E3A] hover:scale-105 transition">
                <i class="fas fa-user-check text-2xl"></i>
                <p class="mt-2 text-lg">Valider / Bloquer comptes</p>
            </a>

            <!-- Logout -->
            <a href="logout.php"
                class="group bg-red-500 rounded-2xl p-6 text-center font-semibold text-white hover:scale-105 transition">
                <i class="fas fa-right-from-bracket text-2xl"></i>
                <p class="mt-2 text-lg">Déconnexion</p>
            </a>

        </div>

    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>