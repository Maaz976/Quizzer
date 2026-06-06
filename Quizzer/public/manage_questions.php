<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../config/db.php';

// STRICT SECURITY: Only allow Teachers (or Admins if you want them to have access too)
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['teacher', 'admin'])) {
    header("Location: login.php");
    exit;
}

// Ensure a course_id was passed in the URL
if (!isset($_GET['course_id'])) {
    die("Error: No course selected.");
}
$course_id = $_GET['course_id'];

// Fetch course details to display the title
$stmt = $pdo->prepare("SELECT coursename FROM Course WHERE id = ?");
$stmt->execute([$course_id]);
$course = $stmt->fetch();

if (!$course) {
    die("Error: Course not found.");
}

$msg = '';

// Handle Adding a New Question
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_question'])) {
    $question_text = trim($_POST['question_text']);
    $options = $_POST['options']; // Array of 4 options
    $correct_option = $_POST['correct_option']; // Index (0-3) of the correct option

    if (!empty($question_text) && count(array_filter($options)) === 4) {
        try {
            $pdo->beginTransaction();

            // 1. Insert the Question 
            $stmt = $pdo->prepare("INSERT INTO Question (text, CourseID) VALUES (?, ?)");
            $stmt->execute([$question_text, $course_id]);
            $question_id = $pdo->lastInsertId();

            // 2. Insert the 4 Options 
            $stmt = $pdo->prepare("INSERT INTO Options (q_id, text, is_correct) VALUES (?, ?, ?)");
            
            foreach ($options as $index => $opt_text) {
                // If the current index matches the radio button selected, it's correct (1), otherwise false (0)
                $is_correct = ($index == $correct_option) ? 1 : 0;
                $stmt->execute([$question_id, trim($opt_text), $is_correct]);
            }

            $pdo->commit();
            $msg = "Question added successfully!";
        } catch (PDOException $e) {
            $pdo->rollBack();
            $msg = "Database Error: " . $e->getMessage();
        }
    } else {
        $msg = "Please fill in the question and all 4 options.";
    }
}

// Handle Deleting a Question
if (isset($_GET['delete_q_id'])) {
    try {
        //We are using ON DELETE CASCADE, deleting this question automatically deletes its options!
        $stmt = $pdo->prepare("DELETE FROM Question WHERE id = ?");
        $stmt->execute([$_GET['delete_q_id']]);
        $msg = "Question deleted successfully!";
    } catch (PDOException $e) {
        $msg = "Error deleting question: " . $e->getMessage();
    }
}

// Fetch existing questions for this course
$stmt = $pdo->prepare("SELECT id, text FROM Question WHERE CourseID = ? ORDER BY id ASC");
$stmt->execute([$course_id]);
$existing_questions = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Questions - <?php echo htmlspecialchars($course['coursename']); ?></title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 30px; }
        .container { background: #fff; padding: 20px; max-width: 800px; margin: auto; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; }
        .btn { padding: 8px 15px; background: #007bff; color: white; border: none; border-radius: 3px; cursor: pointer; text-decoration: none; }
        .btn:hover { background: #0056b3; }
        .btn-back { background: #6c757d; }
        .btn-back:hover { background: #5a6268; }
        .alert { padding: 10px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px; margin-bottom: 15px; }
        .form-group { margin-bottom: 15px; }
        textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .option-row { display: flex; align-items: center; margin-bottom: 10px; }
        .option-row input[type="text"] { flex-grow: 1; padding: 8px; margin-left: 10px; border: 1px solid #ccc; border-radius: 4px; }
        .question-card { background: #f9f9f9; padding: 15px; margin-bottom: 10px; border-left: 4px solid #007bff; }
        .btn-danger { background: #dc3545; font-size: 14px; padding: 5px 10px; }
        .btn-danger:hover { background: #c82333; }
        .question-card { background: #f9f9f9; padding: 15px; margin-bottom: 10px; border-left: 4px solid #007bff; display: flex; justify-content: space-between; align-items: center; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>Manage Questions: <?php echo htmlspecialchars($course['coursename']); ?></h2>
        <a href="teacher_dashboard.php" class="btn btn-back">Back to Dashboard</a>
    </div>

    <?php if ($msg): ?>
        <div class="alert"><?php echo htmlspecialchars($msg); ?></div>
    <?php endif; ?>

    <div style="background: #eef2f5; padding: 20px; border-radius: 5px; margin-bottom: 30px;">
        <h3>Add a New Question</h3>
        <form method="POST" action="">
            <div class="form-group">
                <label><strong>Question Text:</strong></label>
                <textarea name="question_text" rows="3" required></textarea>
            </div>
            
            <p><strong>Enter 4 Options and select the correct one using the radio button:</strong></p>
            
            <?php for ($i = 0; $i < 4; $i++): ?>
                <div class="option-row">
                    <input type="radio" name="correct_option" value="<?php echo $i; ?>" required title="Mark as correct">
                    <input type="text" name="options[]" placeholder="Option <?php echo $i + 1; ?>" required>
                </div>
            <?php endfor; ?>

            <button type="submit" name="add_question" class="btn" style="background: #28a745;">Save Question</button>
        </form>
    </div>

    <h3>Existing Questions in this Course</h3>
    <?php if (count($existing_questions) > 0): ?>
        <?php foreach ($existing_questions as $index => $q): ?>
            <div class="question-card">
                <div>
                    <strong>Q<?php echo $index + 1; ?>:</strong> <?php echo nl2br(htmlspecialchars($q['text'])); ?>
                </div>
                <a href="manage_questions.php?course_id=<?php echo $course_id; ?>&delete_q_id=<?php echo $q['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this question? All its options will be permanently lost.');">Delete</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No questions have been added to this course yet.</p>
    <?php endif; ?>
</div>

</body>
</html>