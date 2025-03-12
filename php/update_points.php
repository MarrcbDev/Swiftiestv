<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION["username"]) || $_SESSION["rol"] !== "admin") {
    die("Acceso denegado");
}

include("connect.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["team"], $_POST["points"], $_POST["action"])) {
    $team = $_POST["team"];
    $points = intval($_POST["points"]);
    $action = $_POST["action"];

    if ($points <= 0) {
        die("La cantidad de puntos debe ser mayor que 0.");
    }

    if ($action === "add") {
        $sql = "UPDATE puntos SET points = points + ? WHERE team = ?";
    } elseif ($action === "subtract") {
        $sql = "UPDATE puntos SET points = points - ? WHERE team = ?";
    } else {
        die("Acción inválida.");
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $points, $team);

    if ($stmt->execute()) {
        header("Location: ../pages/dashboard.php");
        exit();
    } else {
        echo "Error al actualizar puntos: " . $conn->error;
    }

    $stmt->close();
}
?>
