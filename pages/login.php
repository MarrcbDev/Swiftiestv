<?php 
session_start();
if (isset($_SESSION["username"])) {
    header("Location: dashboard.php"); // Redirige si ya está logueado
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicia - Swifties TV</title>
        <!-- Icono -->
        <?php Include ("icon.php") ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Big+Shoulders+Stencil:opsz,wght@10..72,100..900&display=swap" rel="stylesheet">
    <style>
        .bg-darkest { background-color: #0a0a0a; } /* Negro profundo */
        .bg-dark { background-color: #1a1a1a; } /* Negro intermedio */
        .bg-medium { background-color: #2d2d2d; } /* Gris oscuro */
        .text-light { color: #f5f5f5; } /* Blanco puro */
        .text-grayish { color: #c5c5c5; } /* Gris claro */
    </style>
</head>
<body class="bg-darkest flex justify-center items-center h-screen text-light" style='font-family: "Big Shoulders Stencil", sans-serif;'>

    <!-- Contenedor del Login -->
    <div class="bg-dark p-8 rounded-lg shadow-lg max-w-sm w-full">
        <h2 class="text-2xl font-semibold text-center mb-6">Iniciar Sesión</h2>

        <form action="../php/init.php" method="POST">

            <div class="mb-4">
                <label for="text" class="block text-grayish mb-2">Nombre de Usuario</label>
                <input type="text" id="email" name="user" required
                    class="w-full p-3 bg-medium text-light border border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">
            </div>


            <div class="mb-6 relative">
                <label for="password" class="block text-grayish mb-2">Contraseña</label>
                <input type="password" id="password" name="password" required
                    class="w-full p-3 bg-medium text-light border border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 pr-10">
                
                <button type="button" id="togglePassword"
                    class="absolute top-10 right-3 text-grayish hover:text-white transition">
                    👁️
                </button>
            </div>


            <input name="btn" value="Iniciar Sesión" type="submit"
                class="w-full bg-light text-dark p-3 rounded-md hover:bg-gray-300 hover:text-black transition"/>
        </form>


        <p class="text-grayish text-center mt-4">
            ¿No tienes cuenta? <a href="./register.php" class="text-light underline hover:text-grayish">Regístrate</a>
        </p>
    </div>


    <script>
        document.getElementById("togglePassword").addEventListener("click", function () {
            let passwordInput = document.getElementById("password");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                this.textContent = "🙈"; 
            } else {
                passwordInput.type = "password";
                this.textContent = "👁️"; 
            }
        });
    </script>

</body>
</html>
