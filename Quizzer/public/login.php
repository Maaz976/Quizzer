<?php
// Turn on error reporting!
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Query the 'system_user' table
    $stmt = $pdo->prepare("SELECT * FROM system_user WHERE username = :username");
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch();

    // Verify against the 'password_hash' column
    // Assuming you just fetched $user from the database...

    if (password_verify($password, $user['password_hash'])) {
        // Password is correct, set up the session!
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        
        // SAVE THE ROLE IN THE SESSION
        $_SESSION['role'] = $user['role']; 

        // REDIRECT BASED ON ROLE
        if ($_SESSION['role'] === 'admin') {
            header("Location: admin_dashboard.php");
        } elseif ($_SESSION['role'] === 'teacher') {
            header("Location: teacher_dashboard.php");
        } else {
            header("Location: dashboard.php"); // The student dashboard we just built!
        }
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Quizzer</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 50px; }
        .login-box { background: #fff; padding: 20px; width: 300px; margin: 0 auto; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .error { color: red; margin-bottom: 10px; font-weight: bold; }
        input { width: 90%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 3px;}
        button { width: 100%; padding: 10px; background: #28a745; color: white; border: none; cursor: pointer; border-radius: 3px;}
        button:hover { background: #218838; }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Quizzer Login</h2>
    
    <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Log In</button>
    </form>
</div>

</body>
</html>