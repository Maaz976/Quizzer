<?php
// Turn on error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../config/db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $message = "Please fill in all fields.";
    } else {
        // Check if username already exists
        $stmt = $pdo->prepare("SELECT id FROM system_user WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->fetch()) {
            $message = "Username is already taken. Please choose another.";
        } else {
            // Hash the password for security
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user. The database will automatically assign the 'student' role.
            $insertStmt = $pdo->prepare("INSERT INTO system_user (username, password_hash) VALUES (?, ?)");

            try {
                $insertStmt->execute([$username, $hashed_password]);
                $message = "Registration successful! You can now <a href='login.php'>Login</a>.";
            } catch (PDOException $e) {
                $message = "Database Error: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register - Quizzer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .register-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn {
            width: 100%;
            padding: 10px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn:hover {
            background: #218838;
        }

        .message {
            margin-bottom: 15px;
            padding: 10px;
            background: #e2e3e5;
            border-radius: 4px;
            text-align: center;
        }

        .text-center {
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>

<body>

    <div class="register-container">
        <h2 style="text-align: center;">Student Registration</h2>

        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Register</button>
        </form>

        <div class="text-center">
            <a href="login.php">Already have an account? Login here.</a>
        </div>
    </div>

</body>

</html>