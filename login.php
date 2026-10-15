<?php
define('BRIGHT_SPACE', true);
session_start();
require __DIR__ . '/config/database.php';

if (isset($_SESSION['user_id'])) { header('Location: index.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $nama = preg_replace('/[^A-Za-z0-9 ]/', '', $nama);
    $nama = trim(substr($nama, 0, 40));

    if ($nama === '') {
        $error = 'Isi namamu dulu ya, Kapten! 🚀';
    } else {
        $user = db_one('SELECT * FROM users WHERE nama = ?', [$nama]);
        if (!$user) {
            $newId = db_insert('INSERT INTO users (nama, star_coins, level) VALUES (?, 120, 1)', [$nama]);
            $_SESSION['user_id'] = $newId;
        } else {
            $_SESSION['user_id'] = $user['id'];
        }
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - BRIGHT SPACE</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: {
            colors: { cosmic: { bg: '#E0F2FE', card: '#EDE9FE', accent: '#FEF3C7', text: '#1E1B4B' } },
            fontFamily: { nunito: ['Nunito', 'ui-rounded', 'system-ui', 'sans-serif'] }
        } } };
    </script>
</head>
<body class="font-nunito bg-cosmic-bg text-cosmic-text min-h-screen flex items-center justify-center p-4 antialiased">
    <div class="w-full max-w-md">
        <div class="bg-white border-4 border-cosmic-accent rounded-3xl shadow-2xl p-8 text-center">
            <div class="w-20 h-20 mx-auto mb-3 flex items-center justify-center bg-cosmic-accent rounded-full border-4 border-amber-300">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-12 h-12" aria-hidden="true">
                    <path d="M9.5 17.5c-.5 1.6-.3 3.2.5 4.5 1-.6 1.6-1.2 2-2 .4.8 1 1.4 2 2 .8-1.3 1-2.9.5-4.5z" fill="#FB923C"/>
                    <path d="M8 11.5 4.5 15l.5 3 3.5-2z" fill="#A78BFA"/>
                    <path d="M16 11.5 19.5 15l-.5 3-3.5-2z" fill="#A78BFA"/>
                    <path d="M12 1.5c3 2 4.8 5.6 4.8 9.6 0 2.2-.4 4.2-1.1 6.4H8.3c-.7-2.2-1.1-4.2-1.1-6.4 0-4 1.8-7.6 4.8-9.6z" fill="#F8FAFC" stroke="#1E1B4B" stroke-width="1.2" stroke-linejoin="round"/>
                    <circle cx="12" cy="10" r="2.2" fill="#7DD3FC" stroke="#1E1B4B" stroke-width="1.2"/>
                </svg>
            </div>
            <h1 class="text-3xl font-black">BRIGHT <span class="text-violet-600">SPACE</span></h1>
            <p class="mt-1 text-sm font-bold opacity-70">Petualangan Belajar di Luar Angkasa 🚀</p>

            <form method="POST" class="mt-6 text-left">
                <label for="nama" class="block text-sm font-extrabold mb-2">Siapa namamu, Kapten?</label>
                <input type="text" name="nama" id="nama" maxlength="40" autofocus
                       value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>"
                       placeholder="Contoh: Budi"
                       class="w-full px-4 py-3 border-2 border-violet-300 rounded-2xl font-bold text-lg text-center bg-violet-50 focus:outline-none focus:border-violet-500 focus:ring-4 focus:ring-violet-200">
                <?php if ($error): ?>
                    <p class="mt-2 text-sm font-bold text-rose-600 text-center"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>
                <button type="submit" class="w-full mt-4 bg-violet-500 hover:bg-violet-600 text-white font-extrabold text-lg py-3 rounded-full shadow-lg transition-transform duration-200 hover:scale-105 active:scale-95">
                    Mulai Petualangan 🚀
                </button>
            </form>
        </div>
    </div>
</body>
</html>