<?php
session_start();

// Obtener el mensaje de sesión y el tipo (éxito o error)
$message = $_SESSION["message"] ?? "Ocurrió un error inesperado.";
$messageType = $_SESSION["message_type"] ?? "error";
$redirect = $_SESSION["redirect"] ?? "index.php"; // Página a la que redirigir

// Limpiar las variables de sesión
unset($_SESSION["message"]);
unset($_SESSION["message_type"]);
unset($_SESSION["redirect"]);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensaje</title>
    <link rel="stylesheet" href="../css/style.css">
    <script>
        setTimeout(function() {
            window.location.href = "<?php echo htmlspecialchars($redirect, ENT_QUOTES, 'UTF-8'); ?>";
        }, 3000); // Redirige en 3 segundos
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white flex items-center justify-center h-screen">

    <div class="bg-gray-800 bg-opacity-90 text-center p-8 rounded-xl shadow-xl border-4 
        <?php echo ($messageType === 'success') ? 'border-green-500' : 'border-red-500'; ?> 
        animate-fade-in">

        <!-- Icono dinámico -->
        <div class="text-6xl mb-4">
            <?php if ($messageType === 'success') { ?>
                ✅
            <?php } else { ?>
                ❌
            <?php } ?>
        </div>

        <!-- Mensaje dinámico -->
        <h2 class="text-2xl font-bold">
            <?php echo htmlspecialchars_decode($message, ENT_QUOTES); ?>
        </h2>
        <p class="mt-2 text-gray-300">Serás redirigido en unos segundos...</p>
    </div>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.8s ease-out;
        }
    </style>

</body>
</html>
