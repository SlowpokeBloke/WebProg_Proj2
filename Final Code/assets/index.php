<?php
session_start();

//sets the file path for the high scores
$scoresFile = './scores.txt';

//sets the questions and answers
$questionsEasy = [
    [
        'question' => 'What is the capital of France?',
        'answers' => ['Berlin', 'Paris', 'Rome', 'Madrid'],
        'correct' => 'Paris'
    ],
    [
        'question' => 'Who wrote the play "Romeo and Juliet"?',
        'answers' => ['William Shakespeare', 'Charles Dickens', 'Jane Austen', 'Mark Twain'],
        'correct' => 'William Shakespeare'
    ],
    [
        'question' => 'Which planet is known as the "Red Planet"?',
        'answers' => ['Venus', 'Mars', 'Jupiter', 'Saturn'],
        'correct' => 'Mars'
    ],
    [
        'question' => 'What is the largest ocean on Earth?',
        'answers' => ['Indian Ocean', 'Atlantic Ocean', 'Pacific Ocean', 'Arctic Ocean'],
        'correct' => 'Pacific Ocean'
    ],
    [
        'question' => 'In what year did the first manned moon landing occur?',
        'answers' => ['1965', '1969', '1975', '1981'],
        'correct' => '1969'
    ],
    [
        'question' => 'What is the capital of Japan?',
        'answers' => ['Seoul', 'Tokyo', 'Beijing', 'Bangkok'],
        'correct' => 'Tokyo'
    ],
    [
        'question' => 'Which element has the chemical symbol "O"?',
        'answers' => ['Oxygen', 'Gold', 'Iron', 'Sodium'],
        'correct' => 'Oxygen'
    ],
    [
        'question' => 'Who is known as the "Father of Computer Science"?',
        'answers' => ['Alan Turing', 'Bill Gates', 'Steve Jobs', 'Tim Berners-Lee'],
        'correct' => 'Alan Turing'
    ],
    [
        'question' => 'What is the currency of the United Kingdom?',
        'answers' => ['Euro', 'Dollar', 'Pound', 'Yen'],
        'correct' => 'Pound'
    ],
    [
        'question' => 'Which Disney character is known for leaving a glass slipper at a royal ball?',
        'answers' => ['Cinderella', 'Ariel', 'Belle', 'Mulan'],
        'correct' => 'Cinderella'
    ],
];

$questionsMedium = [
    [
        'question' => 'Which novel begins with the line, "It was the best of times, it was the worst of times"?',
        'answers' => ['Pride and Prejudice', ' A Tale of Two Cities', '1984', 'The Great Gatsby'],
        'correct' => 'A Tale of Two Cities'
    ],
    [
        'question' => 'What is the largest mammal in the world?',
        'answers' => ['Elephant', 'Blue Whale', 'Giraffe', 'Gorilla'],
        'correct' => 'Blue Whale'
    ],
    [
        'question' => 'Who painted the Mona Lisa?',
        'answers' => ['Vincent van Gogh', 'Pablo Picasso', 'Leonardo da Vinci', 'Michelangelo'],
        'correct' => 'Leonardo da Vinci'
    ],
    [
        'question' => 'What is the chemical symbol for gold?',
        'answers' => ['Gd', 'Au', 'Ag', 'Fe'],
        'correct' => 'Au'
    ],
    [
        'question' => 'Which planet is known as the "Morning Star" or "Evening Star"?',
        'answers' => ['Mars', 'Venus', 'Mercury', 'Jupiter'],
        'correct' => 'Venus'
    ],
    [
        'question' => 'In what year did World War II end?',
        'answers' => ['1943', '1945', '1947', '1950'],
        'correct' => '1945'
    ],
    [
        'question' => 'Who wrote the play "Hamlet"?',
        'answers' => ['William Wordsworth', 'William Faulkner', 'William Shakespeare', 'William Blake'],
        'correct' => 'William Shakespeare'
    ],
    [
        'question' => 'What is the largest organ in the human body?',
        'answers' => ['Heart', 'Liver', 'Skin', 'Brain'],
        'correct' => 'Skin'
    ],
    [
        'question' => 'Which gas is most abundant in the Earth\'s atmosphere?',
        'answers' => ['Nitrogen', 'Oxygen', 'Carbon Dioxide', 'Hydrogen'],
        'correct' => 'Nitrogen'
    ],
    [
        'question' => 'Which famous scientist developed the theory of general relativity?',
        'answers' => ['Isaac Newton', 'Albert Einstein', 'Galileo Galilei', 'Stephen Hawking'],
        'correct' => 'Albert Einstein'
    ],
];

$questionsHard = [
    [
        'question' => 'What is the speed of light in a vacuum, approximately?',
        'answers' => ['300,000 kilometers per second', '150,000 miles per second', '186,282 miles per second', '500,000 kilometers per second'],
        'correct' => '186,282 miles per second'
    ],
    [
        'question' => 'Which element has the highest melting point?',
        'answers' => ['Tungsten', 'Titanium', 'Platinum', 'Rhenium'],
        'correct' => 'Tungsten'
    ],
    [
        'question' => 'Who was the first woman to win a Nobel Prize?',
        'answers' => ['Marie Curie', 'Rosalind Franklin', 'Dorothy Crowfoot Hodgkin', 'Barbara McClintock'],
        'correct' => 'Marie Curie'
    ],
    [
        'question' => 'In what year did the Chernobyl nuclear disaster occur?',
        'answers' => ['1980', '1986', '1991', '1975'],
        'correct' => '1986'
    ],
    [
        'question' => 'What is the smallest prime number?',
        'answers' => ['0', '1', '2', '3'],
        'correct' => '2'
    ],
    [
        'question' => 'Who wrote the epic poem "Paradise Lost"?',
        'answers' => ['John Keats', 'Samuel Taylor Coleridge', 'John Milton', 'Geoffrey Chaucer'],
        'correct' => 'John Milton'
    ],
    [
        'question' => 'What is the molecular formula of water?',
        'answers' => ['H2O2', 'CO2', 'H2O', 'CH4'],
        'correct' => 'H2O'
    ],
    [
        'question' => 'Which planet is known as the "Ice Giant"?',
        'answers' => ['Uranus', 'Neptune', 'Saturn', 'Jupiter'],
        'correct' => 'Neptune'
    ],
    [
        'question' => 'What is the largest desert in the world by area?',
        'answers' => ['Sahara Desert', 'Antarctic Desert', 'Arabian Desert', 'Gobi Desert'],
        'correct' => 'Antarctic Desert'
    ],
    [
        'question' => 'Who is credited with the discovery of penicillin?',
        'answers' => ['Alexander Fleming', 'Louis Pasteur', 'Joseph Lister', 'Marie Curie'],
        'correct' => 'Alexander Fleming'
    ],
];

//check if the questions have been initialized in the session
if (!isset($_SESSION['shuffledQuestions'])) {

    $shuffledQuestionsEasy = $questionsEasy;
    $shuffledQuestionsMedium = $questionsMedium;
    $shuffledQuestionsHard = $questionsHard;

    //shuffling each question arrays at beginning of the session
    shuffle($shuffledQuestionsEasy);
    shuffle($shuffledQuestionsMedium);
    shuffle($shuffledQuestionsHard);

    //shuffle order of answers in each questions
    function shuffleAnswers($question)
    {
        $answers = $question['answers'];
        shuffle($answers);
        return $answers;
    }

    //shuffling the answers for each question
    foreach ($shuffledQuestionsEasy as $key => $question) {
        $shuffledQuestionsEasy[$key]['answers'] = shuffleAnswers($question);
    }
    foreach ($shuffledQuestionsMedium as $key => $question) {
        $shuffledQuestionsMedium[$key]['answers'] = shuffleAnswers($question);
    }
    foreach ($shuffledQuestionsHard as $key => $question) {
        $shuffledQuestionsHard[$key]['answers'] = shuffleAnswers($question);
    }

    //selecting 5 questions from each difficulty level
    $selectedEasy = array_slice($shuffledQuestionsEasy, 0, 5);
    $selectedMedium = array_slice($shuffledQuestionsMedium, 0, 5);
    $selectedHard = array_slice($shuffledQuestionsHard, 0, 5);

    //merge selected questions into one array
    $_SESSION['shuffledQuestions'] = array_merge($selectedEasy, $selectedMedium, $selectedHard);
}

//get high scores
function getHighScore($scoresFile)
{
    if (!file_exists($scoresFile)) {
        return [];
    }
    $scores = file($scoresFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $highScores = [];
    foreach ($scores as $score_line) {
        list($score, $name) = explode('|', $score_line);
        $highScores[$name] = (int)$score;
    }
    arsort($highScores);
    return array_slice($highScores, 0, 5);
}

//save the score
function saveScore($name, $score, $scoresFile)
{
    //get existing high scores
    $scores = getHighScore($scoresFile);
    $score = getPrizeAmount($score);

    //update the player's score
    if (array_key_exists($name, $scores)) {
        //if higher, update
        if ($score > $scores[$name]) {
            $scores[$name] = $score;
        }
    } else {
        //add new players score
        $scores[$name] = $score;
    }

    //sort scores
    arsort($scores);

    //only show top 5
    $scores = array_slice($scores, 0, 5, true);

    $scoresData = [];
    foreach ($scores as $name => $score) {
        $scoresData[] = $score . '|' . $name;
    }

    //write the scores to file
    file_put_contents($scoresFile, implode("\n", $scoresData));

    //update the session w scores
    $_SESSION['highScores'] = $scores;
}

//lifeline logic
function useLifeline($lifeline)
{
    global $questions;

    //50/50
    if ($lifeline === 'fiftyFifty' && !in_array('fiftyFifty', $_SESSION['usedLifeLines'])) {
        $_SESSION['usedLifeLines'][] = 'fiftyFifty';
        $currQuestion = $_SESSION['shuffledQuestions'][$_SESSION['currIndex']];
        $incorrectAnswers = array_diff($currQuestion['answers'], [$currQuestion['correct']]);
        shuffle($incorrectAnswers);
        array_splice($incorrectAnswers, 2);
        $_SESSION['fiftyFiftyOptions'] = array_diff($currQuestion['answers'], $incorrectAnswers);

        //ask the audience
    } elseif ($lifeline === 'askAudience' && !in_array('askAudience', $_SESSION['usedLifeLines'])) {
        //lifeline is used
        $_SESSION['usedLifeLines'][] = 'askAudience';
        //get question
        $currQuestion = $_SESSION['shuffledQuestions'][$_SESSION['currIndex']];
        //audience reponse (shows probability of each answer)
        $audRes = array_fill(0, count($currQuestion['answers']), 0);
        //correct is higher
        $corrAnsIndex = array_search($currQuestion['correct'], $currQuestion['answers']);
        $audRes[$corrAnsIndex] = rand(70, 100);
        //wrong is higher (use rest of percentage)
        $remPerc = 100 - $audRes[$corrAnsIndex];
        $incorrectAnsIndex = array_diff(range(0, count($currQuestion['answers']) - 1), [$corrAnsIndex]);
        foreach ($incorrectAnsIndex as $index) {
            $audRes[$index] = rand(0, $remPerc);
            $remPerc -= $audRes[$index];
        }
        //store audience response
        $_SESSION['audRes'] = $audRes;

        //phone a friend
    } elseif ($lifeline === 'phoneFriend' && !in_array('phoneFriend', $_SESSION['usedLifeLines'])) {
        $_SESSION['usedLifeLines'][] = 'phoneFriend';
        $currQuestion = $_SESSION['shuffledQuestions'][$_SESSION['currIndex']];
        //friend gives right answer
        $friendRes = $currQuestion['correct'];
        $_SESSION['phoneFriendRes'] = $friendRes;
    }
}

//check if game started
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    //set player name
    $_SESSION['username'] = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
    //reset score and question index
    $_SESSION['score'] = 0;
    $_SESSION['currIndex'] = 0;
    $_SESSION['usedLifeLines'] = [];
    //redirect to index
    header('Location: index.php');
    exit;
}

//checks if a lifeline was used
if (isset($_POST['lifeline'])) {
    useLifeline($_POST['lifeline']);
}

//is answer submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['answer'])) {
    //check if answer is correct
    $currQuestion = $_SESSION['shuffledQuestions'][$_SESSION['currIndex']];
    $correct = $currQuestion['correct'];
    $selection = $_POST['answer'];
    if ($selection == $correct) {
        $_SESSION['score'] += 1; //increase score for correct
        saveScore($_SESSION['username'], $_SESSION['score'], $scoresFile);
        $_SESSION['currIndex'] += 1; //increase question index
        //reset lifelines
        unset($_SESSION['fiftyFiftyOptions']);
        unset($_SESSION['audRes']);
        unset($_SESSION['phoneFriendRes']);
    } else {
        //wrong answer => reset the game
        $game_over = true;
        session_destroy();
    }
}

//check if game is over
if (isset($_SESSION['shuffledQuestions'][$_SESSION['currIndex']])) {
    //get current question
    $currQuestion = $_SESSION['shuffledQuestions'][$_SESSION['currIndex']];
    //check if lifeline was used
    if (isset($_SESSION['fiftyFiftyOptions'])) {
        //display 50/50
        $currQuestion['answers'] = $_SESSION['fiftyFiftyOptions'];
    } elseif (isset($_SESSION['audRes'])) {
        //display audience response
        $currQuestion['audRes'] = $_SESSION['audRes'];
    } elseif (isset($_SESSION['phoneFriendRes'])) {
        //display friend response
        $currQuestion['phoneFriendRes'] = $_SESSION['phoneFriendRes'];
    }
} else {
    $game_over = true;
    //save score if game over
    if (isset($_SESSION['username'])) {
        saveScore($_SESSION['username'], $_SESSION['score'], $scoresFile);
    }
    //reset session
    session_destroy();
}
function getCheckPoint()
{
    //set score
    $score = $_SESSION['score'];

    //prize amounts for each checkpoint
    $prizeAmounts = [0, 100, 200, 300, 500, 1000, 2000, 4000, 8000, 16000, 32000, 64000, 125000, 250000, 500000, 1000000];

    //get checkpoint index
    $checkpointIndex = floor($score / 5) * 5;

    //ensure score is within bounds of prize amounts array
    $checkpointIndex = max(0, min(count($prizeAmounts) - 1, $checkpointIndex));

    //get prize amount
    $prizeAmount = $prizeAmounts[$checkpointIndex];

    return $prizeAmount;
}

//funciton to determine bg image
function determineBackgroundClass()
{
    //check if game started
    if (!isset($_SESSION['username'])) {
        return 'input-screen';
    } elseif (!isset($game_over)) {
        return 'game-screen';
    } else {
        return 'game-over-screen';
    }
}
function getPrizeAmount($score)
{
    //prize amounts for each checkpoint
    $prizeAmounts = [0, 100, 200, 300, 500, 1000, 2000, 4000, 8000, 16000, 32000, 64000, 125000, 250000, 500000, 1000000];

    //check bounds
    $score = max(0, min(count($prizeAmounts) - 1, $score));

    //return prize amount
    return $prizeAmounts[$score];
}

//get top 5 high scores
$highScores = getHighScore($scoresFile);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Who Wants to Be a Millionaire</title>
    <link rel="stylesheet" href="./styles.css">
</head>

<body class="<?php echo determineBackgroundClass(); ?>">
<!-- leaderboard -->
    <div class="leaderboard">
        <h3>Leaderboard</h3>
        <ol>
            <!-- display scores -->
            <?php foreach ($highScores as $name => $score) : ?>
                <li><?php echo htmlspecialchars($name) . ' - $' . $score; ?></li>
            <?php endforeach; ?>
        </ol>
    </div>
    <div class="main">
        <!-- start screen -->
        <?php if (!isset($_SESSION['username'])) : ?>
            <form class="start" action="index.php" method="post">
                <img class="logo" src="./assets/Logo.png" alt="logo">
                <br>
                <!-- login -->
                <div class="start-box">
                    <label for="username">Enter your name:</label>
                    <input type="text" id="username" name="username" required>
                    <button type="submit">Start Game</button>
                </div>
            </form>
        <?php elseif (!isset($game_over)) : ?>
            <!-- game -->
            <div class="game">
                <img class="game-logo" src="./assets/Logo.png" alt="logo">
                <!-- questions -->
                <div class="question">
                    <p><?php echo htmlspecialchars($currQuestion['question']); ?></p>
                </div>
                <!-- answers -->
                <div class="answers">
                    <form class="grid" action="index.php" method="post">
                        <?php foreach ($currQuestion['answers'] as $answer) : ?>
                            <button class="answer" type="submit" name="answer" value="<?php echo $answer; ?>">
                                <?php echo htmlspecialchars($answer); ?>
                            </button>
                        <?php endforeach; ?>
                        <!-- lifelines -->
                        <div class="lifeline">
                            <?php if (!in_array('fiftyFifty', $_SESSION['usedLifeLines'])) : ?>
                                <button class="lifeline-btn" type="submit" name="lifeline" value="fiftyFifty">Use 50:50</button>
                            <?php endif; ?>
                            <?php if (!in_array('askAudience', $_SESSION['usedLifeLines'])) : ?>
                                <button class="lifeline-btn" type="submit" name="lifeline" value="askAudience">Ask the Audience</button>
                            <?php endif; ?>
                            <?php if (!in_array('phoneFriend', $_SESSION['usedLifeLines'])) : ?>
                                <button class="lifeline-btn" type="submit" name="lifeline" value="phoneFriend">Phone a Friend</button>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
                <!-- lifeline responses -->
                <?php if (isset($currQuestion['audRes'])) : ?>
                    <div class="lifeline-response">
                        <p>Ask the Audience Results:</p>
                        <?php foreach ($currQuestion['audRes'] as $index => $percentage) : ?>
                            <p>Option <?php echo $index + 1; ?>: <?php echo $percentage; ?>%</p>
                        <?php endforeach; ?>
                    </div>
                <?php elseif (isset($currQuestion['phoneFriendRes'])) : ?>
                    <div class="lifeline-response">
                        <p>Phone a Friend Response:</p>
                        <p><?php echo $currQuestion['phoneFriendRes']; ?></p>
                    </div>
                <?php endif; ?>
                <!-- scoreboard -->
                <?php if (isset($_SESSION['score'])) : ?>
                    <div class="score">
                        <?php if (isset($_SESSION['score'])) : ?>
                            <p>Current Prize: $<?php echo number_format(getPrizeAmount($_SESSION['score'])); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            <!-- game over -->
        <?php else : ?>
            <div class="game">
                <div class="question">
                    <!-- final prize -->
                    <h1>Game over! Current Prize: $<?php echo number_format(getCheckPoint()); ?></h1>
                    <a href="index.php">Play again</a>
                    <br>
                </div>
            <?php endif; ?>
            </div>
</body>
</html>