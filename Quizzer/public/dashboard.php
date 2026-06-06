<?php
// 1. Turn on error reporting so we never see a blank 500 page again
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../config/db.php';

// Protect the page - Only allow students!
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') 
{
    // If they aren't logged in, or they aren't a student, kick them to login
    header("Location: login.php");
    exit;
}

// Safely grab the username, or default to 'User' if it wasn't set in login.php
$username = $_SESSION['username'] ?? 'User';

try 
{
    // Fetch all available courses (Capital 'Course')
    $stmt = $pdo->query("SELECT * FROM Course");
    $courses = $stmt->fetchAll();

    
    // Fetch recent attempts AND count the total questions for that course
    $stmt = $pdo->prepare("
        SELECT qa.id, c.coursename, qa.score, qa.date_taken, qa.time_taken,
               (SELECT COUNT(q.id) FROM Question q WHERE q.CourseID = c.id) as total_questions
        FROM QuizAttempt qa 
        JOIN Course c ON qa.c_id = c.id 
        WHERE qa.u_id = ? 
        ORDER BY qa.date_taken DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $attempts = $stmt->fetchAll();
} 
catch (PDOException $e) 
{
    // If the database fails, PRINT the error, don't just crash.
    die("Database Error in Dashboard: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Dashboard - Quizzer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 30px;
        }

        .container {
            background: #fff;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .course-card {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn {
            padding: 8px 15px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 3px;
        }

        .btn:hover {
            background: #0056b3;
        }

        .btn-review {
            background: #28a745;
        }

        .btn-review:hover {
            background: #218838;
        }

        .logout {
            color: red;
            text-decoration: none;
            float: right;
        }

        .alert {
            padding: 15px;
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            margin-bottom: 20px;
            border-radius: 4px;
        }
    </style>
</head>

<body>

    <div class="container">
        <a href="logout.php" class="logout">Logout</a>
        <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert">
                <?php echo htmlspecialchars($_GET['msg']); ?>
            </div>
        <?php endif; ?>

        <h3>Available Quizzes</h3>

        <?php if (count($courses) > 0): ?>
            <?php foreach ($courses as $course): ?>
                <div class="course-card">
                    <strong><?php echo htmlspecialchars($course['coursename']); ?></strong>
                    <a href="quiz.php?course_id=<?php echo $course['id']; ?>" class="btn">Take Quiz</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No courses available at the moment.</p>
        <?php endif; ?>

        <h2 style="margin-top: 40px;">Your Progress</h2>
        <table border="1" style="width:100%; border-collapse: collapse; background: white; text-align: left;">
            <thead>
                <tr style="background: #eee;">
                    <th style="padding: 10px;">Course</th>
                    <th style="padding: 10px;">Score</th>
                    <th style="padding: 10px;">Time Taken</th>
                    <th style="padding: 10px;">Date</th>
                    <th style="padding: 10px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($attempts) > 0): ?>
                    <?php foreach ($attempts as $attempt): ?>
                        <tr>
                            <td style="padding: 10px;"><?php echo htmlspecialchars($attempt['coursename']); ?></td>
                            <td style="padding: 10px;">
                                <strong><?php echo $attempt['score']; ?> / <?php echo $attempt['total_questions'] > 0 ? $attempt['total_questions'] : 0; ?></strong>
                            </td>
                            <td style="padding: 10px;"><?php echo $attempt['time_taken']; ?></td>
                            <td style="padding: 10px;"><?php echo date('M d, Y g:i A', strtotime($attempt['date_taken'])); ?>
                            </td>
                            <td style="padding: 10px;">
                                <a href="review.php?attempt_id=<?php echo $attempt['id']; ?>" class="btn btn-review">Review</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="padding: 15px; text-align: center;">No quizzes taken yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>

</html>