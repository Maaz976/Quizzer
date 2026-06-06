<?php
// Turn on error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../config/db.php';

// STRICT SECURITY: Only allow Admins
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$msg = '';

// 1. Handle Adding a Course
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_course'])) {
    $coursename = trim($_POST['coursename']);
    if (!empty($coursename)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO Course (coursename) VALUES (?)");
            $stmt->execute([$coursename]);
            $msg = "Course '$coursename' added successfully!";
        } catch (PDOException $e) {
            $msg = "Error adding course: " . $e->getMessage();
        }
    }
}

// 2. Handle Deleting a Course
if (isset($_GET['delete_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM Course WHERE id = ?");
        $stmt->execute([$_GET['delete_id']]);
        $msg = "Course deleted successfully!";
    } catch (PDOException $e) {
        $msg = "Error deleting course: " . $e->getMessage();
    }
}

// 3. Handle Adding a Teacher
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_teacher'])) {
    $t_username = trim($_POST['t_username']);
    $t_password = $_POST['t_password'];

    if (!empty($t_username) && !empty($t_password)) {
        // Check if user exists
        $check = $pdo->prepare("SELECT id FROM system_user WHERE username = ?");
        $check->execute([$t_username]);
        if ($check->fetch()) {
            $msg = "Username already exists! Choose another one.";
        } else {
            // Hash password and insert as 'teacher'
            $hashed = password_hash($t_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO system_user (username, password_hash, role) VALUES (?, ?, 'teacher')");
            try {
                $stmt->execute([$t_username, $hashed]);
                $msg = "Teacher account '$t_username' created successfully!";
            } catch (PDOException $e) {
                $msg = "Error creating teacher: " . $e->getMessage();
            }
        }
    }
}

// 4. Handle Deleting a Teacher
if (isset($_GET['delete_teacher_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM system_user WHERE id = ? AND role = 'teacher'");
        $stmt->execute([$_GET['delete_teacher_id']]);
        $msg = "Teacher account deleted successfully!";
    } catch (PDOException $e) {
        $msg = "Error deleting teacher: " . $e->getMessage();
    }
}

// Fetch all courses and teachers for the tables
$courses = $pdo->query("SELECT * FROM Course ORDER BY id DESC")->fetchAll();
$teachers = $pdo->query("SELECT id, username FROM system_user WHERE role = 'teacher' ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 30px; }
        .container { background: #fff; padding: 20px; max-width: 800px; margin: auto; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; }
        .logout { color: red; text-decoration: none; font-weight: bold; }
        .alert { padding: 10px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px; margin-bottom: 15px; }
        .form-group { margin-bottom: 15px; }
        input[type="text"], input[type="password"] { padding: 8px; width: 70%; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 10px; display: block; }
        .btn { padding: 8px 15px; background: #007bff; color: white; border: none; border-radius: 3px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn:hover { background: #0056b3; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; margin-bottom: 40px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #eee; }
        .section-divider { border-top: 2px solid #ddd; margin: 40px 0; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Admin Control Panel</h1>
        <a href="logout.php" class="logout">Logout</a>
    </div>

    <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!</p>

    <?php if ($msg): ?>
        <div class="alert"><?php echo htmlspecialchars($msg); ?></div>
    <?php endif; ?>

    <h3>Create New Course</h3>
    <form method="POST" action="admin_dashboard.php">
        <div class="form-group">
            <input type="text" name="coursename" placeholder="Enter Course Name (e.g., Data Structures)" required>
            <button type="submit" name="add_course" class="btn">Add Course</button>
        </div>
    </form>

    <h3>Manage Existing Courses</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Course Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($courses as $course): ?>
            <tr>
                <td><?php echo $course['id']; ?></td>
                <td><?php echo htmlspecialchars($course['coursename']); ?></td>
                <td>
                    <a href="admin_dashboard.php?delete_id=<?php echo $course['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this course? This will delete all associated questions!');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="section-divider"></div>

    <h3>Create Teacher Account</h3>
    <form method="POST" action="admin_dashboard.php">
        <div class="form-group">
            <input type="text" name="t_username" placeholder="Teacher Username" required>
            <input type="password" name="t_password" placeholder="Temporary Password" required>
            <button type="submit" name="add_teacher" class="btn">Add Teacher</button>
        </div>
    </form>

    <h3>Manage Teachers</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($teachers) > 0): ?>
                <?php foreach ($teachers as $teacher): ?>
                <tr>
                    <td><?php echo $teacher['id']; ?></td>
                    <td><?php echo htmlspecialchars($teacher['username']); ?></td>
                    <td>
                        <a href="admin_dashboard.php?delete_teacher_id=<?php echo $teacher['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this teacher account?');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" style="text-align: center; padding: 15px;">No teachers created yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>