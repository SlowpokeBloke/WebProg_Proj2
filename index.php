<?php
session_start();

$scores_file = './scores.txt';

// Mock questions for the example
$questions = [
    [
        'question' => 'What is the capital of France?',
        'answers' => ['Berlin', 'Paris', 'Rome', 'Madrid'],
        'correct' => 1
    ],
    [
        'question' => 'Which planet is known as the Red Planet?',
        'answers' => ['Earth', 'Venus', 'Mars', 'Jupiter'],
        'correct' => 2
    ],
    // ... More questions
];

// Function to get the top 5 high scores
function get_high_scores($scores_file) {
    if (!file_exists($scores_file)) {
        return [];
    }
    $scores = file($scores_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $high_scores = [];
    foreach ($scores as $score_line) {
        list($score, $name) = explode('|', $score_line);
        $high_scores[$name] = (int)$score;
    }
    arsort($high_scores);
    return array_slice($high_scores, 0, 5);
}

// Function to save the score
function save_score($name, $score, $scores_file) {
    $scores = get_high_scores($scores_file);
    $scores[$name] = $score;
    arsort($scores);
    $scores_data = [];
    foreach ($scores as $name => $score) {
        $scores_data[] = $score . '|' . $name;
    }
    file_put_contents($scores_file, implode("\n", $scores_data));
}

// Check if a new game is starting with a player's name
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['player_name'])) {
    $_SESSION['player_name'] = filter_var(trim($_POST['player_name']), FILTER_SANITIZE_STRING);
    $_SESSION['score'] = 0;
    $_SESSION['current_question_index'] = 0;
    $_SESSION['used_lifelines'] = [];
    // Redirect to start the game with a clean URL
    header('Location: index.php');
    exit;
}

// Process the lifeline
function use_lifeline($lifeline) {
    global $questions;

    if ($lifeline === 'fifty_fifty' && !in_array('fifty_fifty', $_SESSION['used_lifelines'])) {
        $_SESSION['used_lifelines'][] = 'fifty_fifty';
        $current_question = $questions[$_SESSION['current_question_index']];
        $incorrect_answers = array_keys(array_diff($current_question['answers'], [$current_question['answers'][$current_question['correct']]]));
        shuffle($incorrect_answers);
        array_splice($incorrect_answers, 2);
        $_SESSION['fifty_fifty_options'] = array_diff_key($current_question['answers'], array_flip($incorrect_answers));
    }
}

// Check if a lifeline was used
if (isset($_POST['lifeline'])) {
    use_lifeline($_POST['lifeline']);
}

// Check if an answer was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['answer'])) {
    $current_question = $questions[$_SESSION['current_question_index']];
    if ($current_question['answers'][$_POST['answer']] === $current_question['answers'][$current_question['correct']]) {
        $_SESSION['score'] += 1; // Increase score for correct answer
        $_SESSION['current_question_index'] += 1; // Move to next question
        unset($_SESSION['fifty_fifty_options']); // Reset the 50:50 lifeline options
    } else {
        // Wrong answer, reset the game
        $game_over = true;
        session_destroy();
    }
}

// Get the current question or end the game
if (isset($questions[$_SESSION['current_question_index']])) {
    $current_question = $questions[$_SESSION['current_question_index']];
    // Apply the 50:50 lifeline if it has been used for this question
    if (isset($_SESSION['fifty_fifty_options'])) {
        $current_question['answers'] = $_SESSION['fifty_fifty_options'];
    }
} else {
    $game_over = true;
    if (isset($_SESSION['player_name'])) {
        save_score($_SESSION['player_name'], $_SESSION['score'], $scores_file);
    }
    session_destroy();
}

// Get the top 5 high scores to display
$high_scores = get_high_scores($scores_file);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Who Wants to Be a Millionaire</title>
    <link rel="stylesheet" href="./teststyles.css">
</head>
<body>
    <div class="leaderboard">
        <h3>Leaderboard</h3>
        <ol>
            <?php foreach ($high_scores as $name => $score): ?>
                <li><?php echo htmlspecialchars($name) . ' - ' . $score; ?></li>
            <?php endforeach; ?>
        </ol>
    </div>
    
    <?php if (!isset($_SESSION['player_name'])): ?>
        <form action="index.php" method="post">
            <label for="player_name">Enter your name:</label>
            <input type="text" id="player_name" name="player_name" required>
            <button type="submit">Start Game</button>
        </form>
    <?php elseif (!isset($game_over)): ?>
        <div class="game">
            <div class="question">
                <p><?php echo htmlspecialchars($current_question['question']); ?></p>
            </div>
            <div class="answers">
                <form action="index.php" method="post">
                    <?php foreach ($current_question['answers'] as $index => $answer): ?>
                        <button type="submit" name="answer" value="<?php echo $index; ?>">
                            <?php echo htmlspecialchars($answer); ?>
                        </button>
                    <?php endforeach; ?>
                    <?php if (!in_array('fifty_fifty', $_SESSION['used_lifelines'])): ?>
                        <button type="submit" name="lifeline" value="fifty_fifty">Use 50:50</button>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    <?php else: ?>
        <p>Game over! Your final score was: <?php echo $_SESSION['score']; ?></p>
        <a href="index.php">Play again</a>
    <?php endif; ?>
</body>
</html>
