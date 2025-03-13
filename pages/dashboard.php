<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

include("../php/connect.php");

// Obtener datos del usuario
$username = $_SESSION["username"];
$rol = $_SESSION["rol"] ?? "usuario";
$team = $_SESSION["team"] ?? "";

// Verificar si el usuario es un administrador general
$sqlAdminGeneral = "SELECT superadmin FROM usuarios WHERE username = ?";
$stmt = $conn->prepare($sqlAdminGeneral);
if ($stmt) {
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($superadmin);
    $stmt->fetch();
    $stmt->close();
} else {
    die("Error en la consulta: " . $conn->error);
}

$isAdminGeneral = ($superadmin === 1);
$adminTag = ($isAdminGeneral) ? " (Admin General)" : (($rol === "admin") ? " (Admin)" : "");

// Obtener los puntos de los equipos
if ($isAdminGeneral) {
    $sql = "SELECT id, team, points FROM puntos ORDER BY id ASC";
    $stmt = $conn->prepare($sql);
} else {
    $sql = "SELECT id, team, points FROM puntos WHERE team = ? ORDER BY id ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $team);
}

if (!$stmt) {
    die("Error en la consulta: " . $conn->error);
}

$stmt->execute();
$result = $stmt->get_result();
if (!$result) {
    die("Error en la consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>

    <!-- Icono -->
     <?php Include ("icon.php") ?>

    <!-- Carga de tema dinámico desde LocalStorage -->
    <link id="themeStylesheet" rel="stylesheet" href="../css/default.css">

    <!-- Fuentes originales -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Big+Shoulders+Stencil:opsz,wght@10..72,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Reggae+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Reggae+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Love+Light&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=IM+Fell+DW+Pica:ital@0;1&family=Love+Light&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400..800;1,400..800&family=IM+Fell+DW+Pica:ital@0;1&family=Love+Light&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400..800;1,400..800&family=IM+Fell+DW+Pica:ital@0;1&family=League+Gothic&family=Love+Light&display=swap" rel="stylesheet">

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Cargar el tema guardado en localStorage
            const savedTheme = localStorage.getItem("selectedTheme") || "default.css";
            document.getElementById("themeStylesheet").setAttribute("href", `../css/${savedTheme}`);

            // Asignar el valor guardado al select
            document.getElementById("themeSelector").value = savedTheme;

            // Cambiar de tema cuando se seleccione otro
            document.getElementById("themeSelector").addEventListener("change", function () {
                const selectedTheme = this.value;
                document.getElementById("themeStylesheet").setAttribute("href", `../css/${selectedTheme}`);
                localStorage.setItem("selectedTheme", selectedTheme);
            });
        });
    </script>
</head>
<body>

    <header>
        <h2><?php echo htmlspecialchars($username) . $adminTag; ?></h2>
        <select id="themeSelector" style="padding: 5px; outline: none; background-color:rgba(255, 255, 255, 0.16); border: none; text-align: center;">
            <option value="default.css" disabled selected>Tema</option>
            <option value="team_debut.css">Debut</option>
            <option value="team_fearless.css">Fearless</option>
            <option value="team_speaknow.css">Speak Now</option>
            <option value="team_red.css">Red</option>
            <option value="team_1989.css">1989</option>
            <option value="team_reputation.css">Reputation</option>
            <option value="team_lover.css">Lover</option>
            <option value="team_folklore.css">Folklore</option>
            <option value="team_evermore.css">Evermore</option>
            <option value="team_midnights.css">Midnights</option>
            <option value="team_ttpd.css">TTPD</option>
        </select>



        <a href="logout.php">Cerrar Sesión</a>
    </header>

    <div class="container">
        <h2>Puntos de los Teams</h2>


        <div>
            <table>
                <thead>
                    <tr>
                        <th>Team</th>
                        <th>Puntos</th>
                        <?php if ($rol === "admin" || $isAdminGeneral) { ?>
                            <th>Acciones</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row["team"]); ?></td>
                            <td><?php echo $row["points"]; ?></td>
                            <?php if ($isAdminGeneral || ($rol === "admin" && $row["team"] === $team)) { ?>
                                <td>
                                    <form action="../php/update_points.php" method="POST">
                                        <input type="hidden" name="team" value="<?php echo htmlspecialchars($row["team"]); ?>">

                                        <input type="number" name="points" max="99999999" min="1" required placeholder="Puntos">
                                        </div>
                                        <div>
                                        <button type="submit" name="action" value="add">➕</button>
                                        <button type="submit" name="action" value="subtract">➖</button>
                                        </div>
                                        </div>
                                    </form>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
