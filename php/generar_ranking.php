<?php
include("connect.php");
session_start();

// Verificar si el usuario es administrador
if (!isset($_SESSION["rol"]) || ($_SESSION["rol"] !== "admin" && $_SESSION["rol"] !== "superadmin")) {
    die("Acceso denegado.");
}

// Eliminar el ranking histórico anterior
$sqlEliminar = "DELETE FROM ranking_historico";
if (!$conn->query($sqlEliminar)) {
    die("Error al eliminar el ranking anterior: " . $conn->error);
}

// Guardar el nuevo ranking en la tabla de historial con la fecha de actualización
$sqlGuardar = "INSERT INTO ranking_historico (fecha, team, puntos) 
               SELECT NOW(), team, points FROM puntos ORDER BY points DESC";
if (!$conn->query($sqlGuardar)) {
    die("Error al guardar el ranking: " . $conn->error);
}

// Reiniciar los puntos de los equipos
$sqlReset = "UPDATE puntos SET points = 0";
if (!$conn->query($sqlReset)) {
    die("Error al reiniciar los puntos: " . $conn->error);
}

// Redirigir de vuelta al panel de administración con mensaje de éxito
header("Location: ../pages/dashboard.php?ranking_generado=1");
exit();
?>
