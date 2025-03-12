<?php 

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("connect.php"); 

if (!empty($_POST["btn"])) {
    $user = trim($_POST["user"] ?? '');
    $password = trim($_POST["password"] ?? '');
    $team = trim($_POST["team"] ?? '');
    $teamPassword = trim($_POST["team_password"] ?? '');

    if (empty($user) || empty($password) || empty($team) || empty($teamPassword)) {
        $_SESSION["message"] = "⚠️ Todos los campos son obligatorios.";
        $_SESSION["message_type"] = "error";
        $_SESSION["redirect"] = "../pages/register.php"; // Volver al registro
        header("Location: ../pages/message.php");
        exit();
    }

    if (!$conn) {
        $_SESSION["message"] = "❌ Error de conexión: " . mysqli_connect_error();
        $_SESSION["message_type"] = "error";
        $_SESSION["redirect"] = "../pages/register.php";
        header("Location: ../pages/message.php");
        exit();
    }

    // Verificar si el usuario ya existe
    $sqlCheck = "SELECT id FROM usuarios WHERE username = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("s", $user);
    $stmtCheck->execute();
    $stmtCheck->store_result(); 

    if ($stmtCheck->num_rows > 0) {
        $_SESSION["message"] = "❌ El usuario <strong>$user</strong> ya está registrado. Intenta con otro nombre.";
        $_SESSION["message_type"] = "error";
        $_SESSION["redirect"] = "../pages/register.php";
        header("Location: ../pages/message.php");
        exit();
    }
    $stmtCheck->close();

    // Obtener las contraseñas del equipo
    $sqlTeam = "SELECT password_user, password_admin FROM puntos WHERE team = ?";
    $stmtTeam = $conn->prepare($sqlTeam);
    $stmtTeam->bind_param("s", $team);
    $stmtTeam->execute();
    $stmtTeam->bind_result($passwordUserDB, $passwordAdminDB);
    $stmtTeam->fetch();
    $stmtTeam->close();

    // Verificar si la contraseña ingresada coincide con la del equipo
    if ($teamPassword === $passwordUserDB) {
        $rol = 'usuario'; // Usuario normal del team
    } elseif ($teamPassword === $passwordAdminDB) {
        $rol = 'admin'; // Admin del team
    } else {
        $_SESSION["message"] = "❌ Contraseña incorrecta para el equipo seleccionado.";
        $_SESSION["message_type"] = "error";
        $_SESSION["redirect"] = "../pages/register.php";
        header("Location: ../pages/message.php");
        exit();
    }

    // Cifrar la contraseña antes de guardarla en la base de datos
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    // Insertar el usuario en la base de datos
    $sqlInsert = "INSERT INTO usuarios (username, password, rol, team) VALUES (?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);

    if ($stmtInsert) {
        $stmtInsert->bind_param("ssss", $user, $passwordHash, $rol, $team);
        if ($stmtInsert->execute()) {
            $_SESSION["message"] = "✅ Registro exitoso en <strong>$team</strong> como <strong>$rol</strong>.";
            $_SESSION["message_type"] = "success";
            $_SESSION["redirect"] = "../pages/login.php"; // Redirigir al login después de registrarse
        } else {
            $_SESSION["message"] = "❌ Error al registrar el usuario.";
            $_SESSION["message_type"] = "error";
            $_SESSION["redirect"] = "../pages/register.php";
        }
        $stmtInsert->close();
    } else {
        $_SESSION["message"] = "❌ Error al preparar la consulta.";
        $_SESSION["message_type"] = "error";
        $_SESSION["redirect"] = "../pages/register.php";
    }

    header("Location: ../pages/message.php");
    exit();
}
?>
