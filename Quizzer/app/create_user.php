<?php
require_once __DIR__ . '/../config/db.php';

// Data for our first test user
$user = 'maaz_admin';
$pass = 'secure123'; // This is the plain text
$email = 'maaz@example.com';

// STEP 1: Hash the password using BCRYPT
$hashed_password = password_hash($pass, PASSWORD_BCRYPT);

try {
    // STEP 2: Prepare the SQL statement (Prevents SQL Injection)
    $sql = "INSERT INTO system_user (username, email, password_hash) VALUES (:username, :email, :password_hash)";
    $stmt = $pdo->prepare($sql);
    
    // STEP 3: Execute the statement
    $stmt->execute([
        ':username' => $user,
        ':email'    => $email,
        ':password_hash' => $hashed_password
    ]);

    echo "✅ User created successfully with a hashed password!\n";
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
    echo "\n";
}
?>