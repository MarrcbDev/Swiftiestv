<?php 
session_start();
if (isset($_SESSION["username"])) {
    header("Location: dashboard.php"); // Redirige si ya está logueado
    exit();
}

include("../php/connect.php"); // Conectar a la base de datos

// Obtener los equipos desde la base de datos
$sql = "SELECT id, team FROM puntos ORDER BY team ASC";
$result = $conn->query($sql);

// Manejo de error en la consulta
if (!$result) {
    die("Error al obtener los equipos: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
        <!-- Icono -->
        <?php Include ("icon.php") ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse - Swifties TV</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Big+Shoulders+Stencil:opsz,wght@10..72,100..900&display=swap" rel="stylesheet">
    <style>
        .bg-darkest { background-color: #0a0a0a; }
        .bg-dark { background-color: #1a1a1a; }
        .bg-medium { background-color: #2d2d2d; }
        .text-light { color: #f5f5f5; }
        .text-grayish { color: #c5c5c5; }
    </style>
</head>
<body class="bg-darkest flex justify-center items-center h-screen text-light" style='font-family: "Big Shoulders Stencil", sans-serif;'>

    <div class="bg-dark p-8 rounded-lg shadow-lg max-w-sm w-full">
        <h2 class="text-2xl font-semibold text-center mb-6">Registrate</h2>

        <form action="../php/controller.php" method="POST">

            <div class="mb-4">
                <label for="user" class="block text-grayish mb-2">Nombre de Usuario</label>
                <input type="text" id="user" name="user" required
                    class="w-full p-3 bg-medium text-light border border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">
            </div>

            <div class="mb-6 relative">
                <label for="password" class="block text-grayish mb-2">Contraseña</label>
                <input type="password" id="password" name="password" required
                    class="w-full p-3 bg-medium text-light border border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">
            </div>

            <div class="mb-6 relative">
                <label for="confirm" class="block text-grayish mb-2">Confirma la contraseña</label>
                <input type="password" id="confirm" name="confirm" required
                    class="w-full p-3 bg-medium text-light border border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">
            </div>

            <!-- Selección de equipo -->
            <div class="mb-6 relative">
                <label for="team" class="block text-grayish mb-2">Selecciona un equipo</label>
                <select id="team" name="team" required
                    class="w-full p-3 bg-medium text-light border border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">
                    <option value="" disabled selected>Elige un equipo</option>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <option value="<?php echo htmlspecialchars($row["team"]); ?>">
                            <?php echo htmlspecialchars($row["team"]); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <!-- contraseña de equipo -->

<div class="mb-4">
    <label for="team_password" class="block text-grayish mb-2">Contraseña del equipo</label>
    <input type="password" id="team_password" name="team_password" required
        class="w-full p-3 bg-medium text-light border border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">
</div>


            <p class="text-red-400 text-center mt-5" id="alert"></p>

            <input disabled id="register-sub" value="Registrarse" name="btn" type="submit"
                class="mt-10 cursor-not-allowed opacity-40 w-full bg-light text-dark p-3 rounded-md hover:bg-gray-300 hover:text-black transition"/>
        </form>
    </div>

    <script>
        let pass = document.getElementById("password");
        let conf = document.getElementById("confirm");
        let aler = document.getElementById("alert");
        let button = document.getElementById("register-sub");
        let user = document.getElementById("user");

        addEventListener('keyup', () => {
            if ((pass.value != conf.value && user.value == "") || (pass.value == "" && conf.value == "")) {
                aler.innerHTML = "Las contraseñas no coinciden o hay campos sin llenar";
                aler.style.display = "block";
                pass.style.border = "1px solid red";
                conf.style.border = "1px solid red";
                user.style.border = "1px solid red";
                button.style.cursor = "not-allowed";
                button.style.opacity = 0.4;
                button.setAttribute("disabled", true);
            } else if (pass.value === conf.value) {
                aler.style.display = "none";
                pass.style.border = "1px solid #424242";
                conf.style.border = "1px solid #424242";
                user.style.border = "1px solid #424242";
                button.style.cursor = "pointer";
                button.style.opacity = 1;
                button.removeAttribute("disabled");
            }
        });
    </script>

</body>
</html>
