<?php
session_start();

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin_panel.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    if ($password === 'Comedor1') {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin_panel.php");
        exit;
    } else {
        $error = 'Contraseña incorrecta';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Administración</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: var(--bg-color);
            margin: 0;
            font-family: var(--font-family);
        }
        .login-box {
            background-color: var(--panel-bg);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            text-align: center;
            width: 100%;
            max-width: 350px;
        }
        .login-box h2 {
            margin-bottom: 20px;
            color: var(--text-main);
        }
        .login-box input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            background-color: var(--bg-color);
            color: var(--text-main);
            box-sizing: border-box;
        }
        .login-box button {
            width: 100%;
            padding: 12px;
            background-color: var(--accent-gold);
            color: var(--bg-color);
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }
        .login-box button:hover {
            opacity: 0.9;
        }
        .error {
            color: #ff4d4d;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>🔒 Panel de Admin</h2>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="password" name="password" placeholder="Contraseña" required autofocus>
            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>
