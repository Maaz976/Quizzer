<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../config/db.php';

// STRICT SECURITY: Only allow Teachers
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit;
}

// Fetch all courses
$courses = $pdo->query("SELECT * FROM Course ORDER BY coursename ASC")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Teacher Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f7f6; padding: 30px; }
        .container { background: #fff; padding: 20px; max-width: 800px; margin: auto; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; }
        .logout { color: red; text-decoration: none; font-weight: bold; }
        .course-card { border: 1px solid #ddd; padding: 15px; margin-bottom: 10px; border-radius: 5px; display: flex; justify-content: space-between; align-items: center; }
        .btn { padding: 8px 15px; background: #28a745; color: white; text-decoration: none; border-radius: 3px; }
        .btn:hover { background: #218838; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Teacher Dashboard</h1>
        <a href="logout.php" class="logout">Logout</a>
    </div>

    <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>. Select a course below to add or edit quiz questions.</p>

    <h3>Available Courses</h3>
    
    <?php if (count($courses) > 0): ?>
        <?php foreach ($courses as $course): ?>
            <div class="course-card">
                <strong><?php echo htmlspecialchars($course['coursename']); ?></strong>
                <a href="manage_questions.php?course_id=<?php echo $course['id']; ?>" class="btn">Manage Questions</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No courses have been created by the Admin yet.</p>
    <?php endif; ?>

</div>

</body>
</html>