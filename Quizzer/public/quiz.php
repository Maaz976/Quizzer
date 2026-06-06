<?php
// 1. TURN ON ERROR REPORTING (To see why it's crashing)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// 2. GET THE ID (Matches your dashboard link 'course_id')
$course_id = $_GET['course_id'] ?? null;
$user_id = $_SESSION['user_id'];

if (!$course_id) {
    die("Error: No course ID found in the URL. Make sure the link is quiz.php?course_id=1");
}

// 3. FETCH COURSE (Using lowercase 'id')
$stmt = $pdo->prepare("SELECT coursename FROM Course WHERE id = ?");
$stmt->execute([$course_id]);
$course = $stmt->fetch();

if (!$course) {
    die("Error: Course with ID $course_id not found in the database.");
}

// 4. FETCH QUESTIONS (Using 'CourseID' as you defined in your schema)
$stmt = $pdo->prepare("SELECT id, text FROM Question WHERE CourseID = ?");
$stmt->execute([$course_id]);
$questions = $stmt->fetchAll();

// 5. HANDLE SUBMISSION
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $total_score = 0;
    // --- UPDATED CALCULATION AND INSERT ---
    $start_time = $_POST['start_time'];
    $seconds_elapsed = time() - $start_time; 
    
    // Convert seconds into HH:MM:SS format so the database doesn't crash
    $time_formatted = gmdate("H:i:s", $seconds_elapsed);

    // Insert into QuizAttempt (Using $time_formatted instead of raw seconds)
    $stmt = $pdo->prepare("INSERT INTO QuizAttempt (u_id, c_id, score, date_taken, time_taken) VALUES (?, ?, ?, NOW(), ?)");
    $stmt->execute([$user_id, $course_id, 0, $time_formatted]);
    
    $attempt_id = $pdo->lastInsertId();
    // --------------------------------------
    foreach ($questions as $q) {
        $q_id = $q['id'];
        $chosen_opt = $_POST['question_' . $q_id] ?? null;
        $is_correct_val = 0;

        if ($chosen_opt) {
            // Check if correct (Using lowercase 'id')
            $chk = $pdo->prepare("SELECT is_correct FROM Options WHERE id = ?");
            $chk->execute([$chosen_opt]);
            if ($chk->fetchColumn() == 1) {
                $is_correct_val = 1;
                $total_score++;
            }
        }

        // Insert into Response
        $resp = $pdo->prepare("INSERT INTO Response (attempt_id, q_id, score, chosen_option_id) VALUES (?, ?, ?, ?)");
        $resp->execute([$attempt_id, $q_id, $is_correct_val, $chosen_opt]);
    }

    // Update final score
    $upd = $pdo->prepare("UPDATE QuizAttempt SET score = ? WHERE id = ?");
    $upd->execute([$total_score, $attempt_id]);

    header("Location: dashboard.php?msg=Quiz Finished! Score: $total_score");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz: <?php echo htmlspecialchars($course['coursename']); ?></title>
    <style>
        body { font-family: sans-serif; background: #f4f7f6; padding: 20px; color: #333; }
        .quiz-container { max-width: 800px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .question { margin-bottom: 20px; padding: 15px; border-bottom: 1px solid #eee; }
        .question-text { font-weight: bold; margin-bottom: 10px; display: block; }
        .option { display: block; margin-bottom: 8px; cursor: pointer; }
        .btn { background: #28a745; color: white; border: none; padding: 12px 20px; border-radius: 4px; width: 100%; cursor: pointer; font-size: 16px; }
        .btn:hover { background: #218838; }
    </style>
</head>
<body>

<div class="quiz-container">
    <h1><?php echo htmlspecialchars($course['coursename']); ?></h1>
    <form method="POST">
        <input type="hidden" name="start_time" value="<?php echo time(); ?>">
        
        <?php foreach ($questions as $index => $q): ?>
            <div class="question">
                <span class="question-text"><?php echo ($index + 1) . ". " . htmlspecialchars($q['text']); ?></span>
                <?php
                // Fetch options for THIS question (Using 'q_id' as you defined)
                $optStmt = $pdo->prepare("SELECT id, text FROM Options WHERE q_id = ?");
                $optStmt->execute([$q['id']]);
                $options = $optStmt->fetchAll();
                
                foreach ($options as $opt):
                ?>
                    <label class="option">
                        <input type="radio" name="question_<?php echo $q['id']; ?>" value="<?php echo $opt['id']; ?>" required>
                        <?php echo htmlspecialchars($opt['text']); ?>
                    </label>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>

        <button type="submit" class="btn">Finish and Submit</button>
    </form>
</div>

</body>
</html>