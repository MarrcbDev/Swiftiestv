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

// Obtener datos del ranking histórico
$sql = "SELECT fecha, team, puntos FROM ranking_historico ORDER BY fecha DESC, puntos DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranking Histórico</title>
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
            const savedTheme = localStorage.getItem("selectedTheme") || "default.css";
            document.getElementById("themeStylesheet").setAttribute("href", `../css/${savedTheme}`);
            document.getElementById("themeSelector").value = savedTheme;
            document.getElementById("themeSelector").addEventListener("change", function () {
                const selectedTheme = this.value;
                document.getElementById("themeStylesheet").setAttribute("href", `../css/${selectedTheme}`);
                localStorage.setItem("selectedTheme", selectedTheme);
            });
        });
    </script>
</head>
<body>
    <?php include("header.php") ?>

   
    <h2 style="text-align: center;">Ranking Anterior</h2>

    <?php if ($result->num_rows > 0) { ?>
        <table>
            <thead>
                <tr>
                    <th>Top</th>
                    <th>Team</th>
                    <th>Puntos</th>
                    <th>Fecha De Actualización</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $top = 1;
                while ($row = $result->fetch_assoc()) {
                    $medal = "";
                    if ($top == 1) $medal = "🥇";
                    elseif ($top == 2) $medal = "🥈";
                    elseif ($top == 3) $medal = "🥉";
                    echo "<tr>";
                    echo "<td>{$medal} #{$top}</td>";
                    echo "<td>" . htmlspecialchars($row["team"]) . "</td>";
                    echo "<td>" . $row["puntos"] . "</td>";
                    echo "<td>" . date("d/m/Y", strtotime($row["fecha"])) . "</td>";
                    echo "</tr>";
                    $top++;
                }
                ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p style="text-align: center; font-weight: bold;">Aún no se ha concretado el primer ranking, cuando se concrete, aparecerá en este apartado.</p>
    <?php } ?>

</body>
</html>
