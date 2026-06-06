<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$attempt_id = $_GET['attempt_id'] ?? null;

// 1. Fetch Attempt Info
$stmt = $pdo->prepare("
    SELECT qa.*, c.coursename 
    FROM QuizAttempt qa 
    JOIN Course c ON qa.c_id = c.id 
    WHERE qa.id = ? AND qa.u_id = ?
");
$stmt->execute([$attempt_id, $_SESSION['user_id']]);
$attempt = $stmt->fetch();

if (!$attempt) { die("Attempt not found."); }

// 2. Fetch all Questions and the user's Responses for this attempt
$stmt = $pdo->prepare("
    SELECT q.id as q_id, q.text as question_text, r.chosen_option_id, r.score as is_correct_response
    FROM Response r
    JOIN Question q ON r.q_id = q.id
    WHERE r.attempt_id = ?
");
$stmt->execute([$attempt_id]);
$responses = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Review Quiz: <?php echo $attempt['coursename']; ?></title>
    <style>
        body { font-family: sans-serif; background: #f4f7f6; padding: 30px; }
        .review-container { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 8px; }
        .q-block { border-bottom: 2px solid #eee; padding: 15px 0; }
        .option { padding: 8px; margin: 4px 0; border-radius: 4px; border: 1px solid #ddd; }
        .correct { background: #d4edda; border-color: #c3e6cb; font-weight: bold; }
        .wrong { background: #f8d7da; border-color: #f5c6cb; }
        .user-choice::after { content: " (Your Choice)"; font-style: italic; font-size: 0.8em; }
        .badge { display: inline-block; padding: 5px 10px; border-radius: 4px; color: white; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="review-container">
        <a href="dashboard.php">← Back to Dashboard</a>
        <h1>Review: <?php echo htmlspecialchars($attempt['coursename']); ?></h1>
        <p><strong>Final Score:</strong> <?php echo $attempt['score']; ?>/10</p>
        <p><strong>Time Taken:</strong> <?php echo $attempt['time_taken']; ?></p>

        <?php foreach ($responses as $res): ?>
            <div class="q-block">
                <p><strong>Q: <?php echo htmlspecialchars($res['question_text']); ?></strong></p>
                
                <?php
                // Fetch all 4 options for this question
                $optStmt = $pdo->prepare("SELECT id, text, is_correct FROM Options WHERE q_id = ?");
                $optStmt->execute([$res['q_id']]);
                $options = $optStmt->fetchAll();

                foreach ($options as $opt):
                    $class = '';
                    // Highlight the correct answer in green
                    if ($opt['is_correct'] == 1) $class = 'correct';
                    
                    // If the user picked this option...
                    if ($opt['id'] == $res['chosen_option_id']) {
                        $class .= ' user-choice';
                        // If it was wrong, highlight it red (the 'correct' class above will override if they got it right)
                        if ($opt['is_correct'] == 0) $class .= ' wrong';
                    }
                ?>
                    <div class="option <?php echo $class; ?>">
                        <?php echo htmlspecialchars($opt['text']); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>