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
    // Get existing high scores
    $scores = get_high_scores($scores_file);

    // Check if the player already has a score
    if (array_key_exists($name, $scores)) {
        // Update score only if the new score is higher
        if ($score > $scores[$name]) {
            $scores[$name] = $score;
        }
    } else {
        // Add the new player's score
        $scores[$name] = $score;
    }

    // Sort the scores in descending order
    arsort($scores);

    // Keep only the top scores (e.g., top 5)
    $scores = array_slice($scores, 0, 5, true);

    // Prepare scores to be written to the file
    $scores_data = [];
    foreach ($scores as $name => $score) {
        $scores_data[] = $score . '|' . $name;
    }

    // Write the scores back to the file
    file_put_contents($scores_file, implode("\n", $scores_data));
}

// Process the lifeline
function use_lifeline($lifeline) {
    global $questions;
	
	//FIFTY FIFTY logic
    if ($lifeline === 'fifty_fifty' && !in_array('fifty_fifty', $_SESSION['used_lifelines'])) {
        $_SESSION['used_lifelines'][] = 'fifty_fifty';
        $current_question = $questions[$_SESSION['current_question_index']];
        $incorrect_answers = array_keys(array_diff($current_question['answers'], [$current_question['answers'][$current_question['correct']]]));
        shuffle($incorrect_answers);
        array_splice($incorrect_answers, 2);
        $_SESSION['fifty_fifty_options'] = array_diff_key($current_question['answers'], array_flip($incorrect_answers));
    	
	// ASK THE AUDIENCE Lifeline Logic
	} elseif ($lifeline === 'ask_audience' && !in_array('ask_audience', $_SESSION['used_lifelines'])) {
    // Mark the 'Ask the Audience' lifeline as used
    $_SESSION['used_lifelines'][] = 'ask_audience';
    // Get the current question for audience response simulation
    $current_question = $questions[$_SESSION['current_question_index']];
    // Simulate audience response (customize the logic based on your preference)
    $audience_response = array_fill(0, count($current_question['answers']), 0);
    // Simulate a higher probability for the correct answer
    $correct_answer_index = $current_question['correct'];
    $audience_response[$correct_answer_index] = rand(70, 100);
    // Simulate the remaining percentage among the incorrect answers
    $remaining_percentage = 100 - $audience_response[$correct_answer_index];   
    // Distribute remaining percentage among the incorrect answers
    $incorrect_answer_indices = array_diff(range(0, count($current_question['answers']) - 1), [$correct_answer_index]);  
    foreach ($incorrect_answer_indices as $index) {
        $audience_response[$index] = rand(0, $remaining_percentage);
        $remaining_percentage -= $audience_response[$index];
    }
    // Store the simulated audience response in the session
    $_SESSION['audience_response'] = $audience_response;
	
	//PHONE A FRIEND logic
	} elseif ($lifeline === 'phone_a_friend' && !in_array('phone_a_friend', $_SESSION['used_lifelines'])) {
        $_SESSION['used_lifelines'][] = 'phone_a_friend';
        $current_question = $questions[$_SESSION['current_question_index']];
        // Simulate a friend's response (you can customize the logic based on your preference)
        $friend_response = $current_question['answers'][$current_question['correct']];
        $_SESSION['phone_a_friend_response'] = $friend_response;
    }
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
        unset($_SESSION['audience_response']); // Reset the audience response lifeline
        unset($_SESSION['phone_a_friend_response']); // Reset the phone a friend lifeline
    } else {
        // Wrong answer, reset the game
        $game_over = true;
        session_destroy();
    }
}

// Get the current question or end the game
if (isset($questions[$_SESSION['current_question_index']])) {
    $current_question = $questions[$_SESSION['current_question_index']];
    // Apply lifelines if they have been used for this question
    if (isset($_SESSION['fifty_fifty_options'])) {
        $current_question['answers'] = $_SESSION['fifty_fifty_options'];
    } elseif (isset($_SESSION['audience_response'])) {
        // Display the original question with simulated audience percentages
        $current_question['audience_response'] = $_SESSION['audience_response'];
    } elseif (isset($_SESSION['phone_a_friend_response'])) {
        // Display the original question with the friend's response
        $current_question['phone_a_friend_response'] = $_SESSION['phone_a_friend_response'];
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
                    <?php if (!in_array('ask_audience', $_SESSION['used_lifelines'])): ?>
                        <button type="submit" name="lifeline" value="ask_audience">Ask the Audience</button>
                    <?php endif; ?>
                    <?php if (!in_array('phone_a_friend', $_SESSION['used_lifelines'])): ?>
                        <button type="submit" name="lifeline" value="phone_a_friend">Phone a Friend</button>
                    <?php endif; ?>
                </form>
            </div>
            <?php if (isset($current_question['audience_response'])): ?>
                <div class="lifeline-response">
                    <p>Ask the Audience Results:</p>
                    <?php foreach ($current_question['audience_response'] as $index => $percentage): ?>
                        <p>Option <?php echo $index + 1; ?>: <?php echo $percentage; ?>%</p>
                    <?php endforeach; ?>
                </div>
            <?php elseif (isset($current_question['phone_a_friend_response'])): ?>
                <div class="lifeline-response">
                    <p>Phone a Friend Response:</p>
                    <p><?php echo $current_question['phone_a_friend_response']; ?></p>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <p>Game over! Your final score was: <?php echo $_SESSION['score']; ?></p>
        <a href="index.php">Play again</a>
    <?php endif; ?>
</body>

</html>
