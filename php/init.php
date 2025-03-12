<?php
session_start();
include("connect.php"); // Conexión a la base de datos

if (!empty($_POST["btn"])) {
    $user = trim($_POST["user"] ?? '');
    $password = trim($_POST["password"] ?? '');

    if (empty($user) || empty($password)) {
        $_SESSION["message"] = "⚠️ Todos los campos son obligatorios";
        $_SESSION["message_type"] = "error";
        $_SESSION["redirect"] = "../pages/login.php"; // Volver al login
        header("Location: ../pages/message.php");
        exit();
    }

    // Buscar el usuario en la base de datos
    $sql = "SELECT id, username, password, rol, team, superadmin FROM usuarios WHERE username = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $username, $passwordHash, $rol, $team, $superadmin);
            $stmt->fetch();

            // Verificar la contraseña
            if (password_verify($password, $passwordHash)) {
                // Iniciar sesión y guardar datos del usuario
                $_SESSION["username"] = $username;
                $_SESSION["user_id"] = $id;
                $_SESSION["rol"] = $rol;
                $_SESSION["team"] = $team;
                $_SESSION["superadmin"] = $superadmin;

                $_SESSION["message"] = "✅ Inicio de sesión exitoso. ¡Bienvenido, $username!";
                $_SESSION["message_type"] = "success";
                $_SESSION["redirect"] = "../pages/dashboard.php"; // Redirigir al dashboard
                header("Location: ../pages/message.php");
                exit();
            } else {
                $_SESSION["message"] = "❌ Contraseña incorrecta";
                $_SESSION["message_type"] = "error";
                $_SESSION["redirect"] = "../pages/login.php";
                header("Location: ../pages/message.php");
                exit();
            }
        } else {
            $_SESSION["message"] = "❌ Usuario no encontrado";
            $_SESSION["message_type"] = "error";
            $_SESSION["redirect"] = "../pages/login.php";
            header("Location: ../pages/message.php");
            exit();
        }

        $stmt->close();
    } else {
        $_SESSION["message"] = "❌ Error en la consulta: " . $conn->error;
        $_SESSION["message_type"] = "error";
        $_SESSION["redirect"] = "../pages/login.php";
        header("Location: ../pages/message.php");
        exit();
    }
}
?>
