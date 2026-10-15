<?php
define('BRIGHT_SPACE', true);
if (session_status() === PHP_SESSION_NONE) session_start();
require __DIR__ . '/config/database.php';
require_login();
?>
<!DOCTYPE html>
<html lang="id"><head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanya Arazka - BRIGHT SPACE</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{colors:{cosmic:{bg:'#E0F2FE',card:'#EDE9FE',accent:'#FEF3C7',text:'#1E1B4B'}},fontFamily:{nunito:['Nunito','system-ui','sans-serif']}}}}</script>
</head>
<body class="font-nunito text-slate-800 min-h-screen antialiased" style="background-color:#F0F7FF;">
    <?php include __DIR__ . '/components/sidebar.php'; ?>
    <div class="lg:ml-72 min-h-screen flex flex-col">
        <?php include __DIR__ . '/components/header.php'; ?>
        <main class="max-w-6xl mx-auto px-4 py-8 w-full flex-1">
            <h1 class="text-3xl font-black text-center">🤖 Tanya Arazka</h1>
            <p class="mt-2 text-center font-bold opacity-70">Chat dengan Arazka segera hadir.</p>
        </main>
    </div>
</body></html>