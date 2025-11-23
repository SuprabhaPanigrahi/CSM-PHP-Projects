<?php
header('Content-Type: application/json');

require_once "../../core/database.php";

if (!$conn) {
    echo json_encode(['error' => 'No database connection']);
    exit;
}

// 15 Questions per technology (60 total)
$questions_by_tech = [
    // PHP Questions (technology_id = 1) - 15 questions
    1 => [
        ['question_text' => 'What does PHP stand for?', 'option1' => 'Personal Home Page', 'option2' => 'PHP: Hypertext Preprocessor', 'option3' => 'Private Home Page', 'option4' => 'Personal Hypertext Processor', 'answer' => 2],
        ['question_text' => 'Which symbol is used for variables in PHP?', 'option1' => '#', 'option2' => '$', 'option3' => '@', 'option4' => '&', 'answer' => 2],
        ['question_text' => 'How to create a constant in PHP?', 'option1' => 'constant()', 'option2' => 'define()', 'option3' => 'const', 'option4' => 'set_constant()', 'answer' => 2],
        ['question_text' => 'Which function outputs text in PHP?', 'option1' => 'print', 'option2' => 'echo', 'option3' => 'Both', 'option4' => 'None', 'answer' => 3],
        ['question_text' => 'PHP file extension?', 'option1' => '.php', 'option2' => '.html', 'option3' => '.xml', 'option4' => '.ph', 'answer' => 1],
        ['question_text' => 'String length function in PHP?', 'option1' => 'length()', 'option2' => 'strlen()', 'option3' => 'size()', 'option4' => 'str_length()', 'answer' => 2],
        ['question_text' => 'Start session in PHP?', 'option1' => 'session_start()', 'option2' => 'start_session()', 'option3' => 'session_begin()', 'option4' => 'begin_session()', 'answer' => 1],
        ['question_text' => 'Include file in PHP?', 'option1' => 'include "file.php"', 'option2' => '#include "file.php"', 'option3' => 'import "file.php"', 'option4' => 'load "file.php"', 'answer' => 1],
        ['question_text' => 'PHP supports which databases?', 'option1' => 'MySQL', 'option2' => 'PostgreSQL', 'option3' => 'SQLite', 'option4' => 'All of above', 'answer' => 4],
        ['question_text' => 'Which loop is used for arrays in PHP?', 'option1' => 'for', 'option2' => 'while', 'option3' => 'foreach', 'option4' => 'do-while', 'answer' => 3],
        ['question_text' => 'What is $_GET in PHP?', 'option1' => 'Server variable', 'option2' => 'GET method data', 'option3' => 'Global constant', 'option4' => 'Function name', 'answer' => 2],
        ['question_text' => 'How to redirect in PHP?', 'option1' => 'redirect()', 'option2' => 'header()', 'option3' => 'location()', 'option4' => 'goto()', 'answer' => 2],
        ['question_text' => 'Get array length in PHP?', 'option1' => 'count()', 'option2' => 'length()', 'option3' => 'size()', 'option4' => 'array_length()', 'answer' => 1],
        ['question_text' => 'Current PHP version?', 'option1' => 'PHP 5', 'option2' => 'PHP 7', 'option3' => 'PHP 8', 'option4' => 'PHP 9', 'answer' => 3],
        ['question_text' => 'PHP framework examples?', 'option1' => 'Laravel', 'option2' => 'Symfony', 'option3' => 'CodeIgniter', 'option4' => 'All of above', 'answer' => 4],
    ],
    
    // Python Questions (technology_id = 2) - 15 questions
    2 => [
        ['question_text' => 'Python language type?', 'option1' => 'Compiled', 'option2' => 'Interpreted', 'option3' => 'Assembly', 'option4' => 'Machine', 'answer' => 2],
        ['question_text' => 'What does len() do?', 'option1' => 'Calculate length', 'option2' => 'Print value', 'option3' => 'Define list', 'option4' => 'Loop items', 'answer' => 1],
        ['question_text' => 'Create list in Python?', 'option1' => 'list = []', 'option2' => 'list = ()', 'option3' => 'list = {}', 'option4' => 'list = <>', 'answer' => 1],
        ['question_text' => 'Function keyword in Python?', 'option1' => 'function', 'option2' => 'def', 'option3' => 'func', 'option4' => 'define', 'answer' => 2],
        ['question_text' => 'Comment in Python?', 'option1' => '// comment', 'option2' => '/* comment */', 'option3' => '# comment', 'option4' => '-- comment', 'answer' => 3],
        ['question_text' => 'Add item to list?', 'option1' => 'add()', 'option2' => 'append()', 'option3' => 'insert()', 'option4' => 'push()', 'answer' => 2],
        ['question_text' => 'Output of print(2 ** 3)?', 'option1' => '6', 'option2' => '8', 'option3' => '9', 'option4' => '5', 'answer' => 2],
        ['question_text' => 'Create dictionary?', 'option1' => 'dict = []', 'option2' => 'dict = ()', 'option3' => 'dict = {}', 'option4' => 'dict = <>', 'answer' => 3],
        ['question_text' => 'Python module for random numbers?', 'option1' => 'random', 'option2' => 'rand', 'option3' => 'numpy', 'option4' => 'math', 'answer' => 1],
        ['question_text' => 'How to read file in Python?', 'option1' => 'file.read()', 'option2' => 'open(file).read()', 'option3' => 'read(file)', 'option4' => 'file_open().read()', 'answer' => 2],
        ['question_text' => 'Python indentation uses?', 'option1' => 'Tabs', 'option2' => 'Spaces', 'option3' => 'Both', 'option4' => 'None', 'answer' => 3],
        ['question_text' => 'Python package manager?', 'option1' => 'pip', 'option2' => 'npm', 'option3' => 'composer', 'option4' => 'gem', 'answer' => 1],
        ['question_text' => 'Python web framework?', 'option1' => 'Django', 'option2' => 'Flask', 'option3' => 'Both', 'option4' => 'None', 'answer' => 3],
        ['question_text' => 'Python data types?', 'option1' => 'int, str, list', 'option2' => 'dict, tuple, set', 'option3' => 'bool, float', 'option4' => 'All of above', 'answer' => 4],
        ['question_text' => 'Python version?', 'option1' => 'Python 2', 'option2' => 'Python 3', 'option3' => 'Both', 'option4' => 'None', 'answer' => 2],
    ],
    
    // JavaScript Questions (technology_id = 3) - 15 questions
    3 => [
        ['question_text' => 'JavaScript usage?', 'option1' => 'Server-side', 'option2' => 'Client-side', 'option3' => 'Both', 'option4' => 'Database', 'answer' => 3],
        ['question_text' => 'Declare variable in JS?', 'option1' => 'var x', 'option2' => 'int x', 'option3' => 'let x', 'option4' => 'Both var and let', 'answer' => 4],
        ['question_text' => 'JavaScript developer?', 'option1' => 'Microsoft', 'option2' => 'Netscape', 'option3' => 'Google', 'option4' => 'Apple', 'answer' => 2],
        ['question_text' => 'Create function in JS?', 'option1' => 'function myFunc()', 'option2' => 'function:myFunc()', 'option3' => 'function=myFunc()', 'option4' => 'create myFunc()', 'answer' => 1],
        ['question_text' => 'Comments in JS?', 'option1' => '//', 'option2' => '<!--', 'option3' => '/* */', 'option4' => 'Both A and C', 'answer' => 4],
        ['question_text' => 'Alert box syntax?', 'option1' => 'alert("Hello")', 'option2' => 'msg("Hello")', 'option3' => 'alertBox("Hello")', 'option4' => 'msgBox("Hello")', 'answer' => 1],
        ['question_text' => 'Parse JSON method?', 'option1' => 'JSON.parse()', 'option2' => 'JSON.stringify()', 'option3' => 'JSON.encode()', 'option4' => 'JSON.decode()', 'answer' => 1],
        ['question_text' => 'Select element by ID?', 'option1' => 'getElementById()', 'option2' => 'querySelector()', 'option3' => 'Both', 'option4' => 'None', 'answer' => 3],
        ['question_text' => 'Result of 3 + 2 + "7" in JS?', 'option1' => '12', 'option2' => '57', 'option3' => '327', 'option4' => 'Error', 'answer' => 2],
        ['question_text' => 'Constant keyword in JS?', 'option1' => 'const', 'option2' => 'let', 'option3' => 'var', 'option4' => 'constant', 'answer' => 1],
        ['question_text' => 'Which is JS framework?', 'option1' => 'React', 'option2' => 'Angular', 'option3' => 'Vue', 'option4' => 'All', 'answer' => 4],
        ['question_text' => 'Async/await used for?', 'option1' => 'Synchronous code', 'option2' => 'Asynchronous code', 'option3' => 'Both', 'option4' => 'None', 'answer' => 2],
        ['question_text' => 'Local storage in JS?', 'option1' => 'localStorage', 'option2' => 'sessionStorage', 'option3' => 'Both', 'option4' => 'None', 'answer' => 3],
        ['question_text' => 'JavaScript ES6 feature?', 'option1' => 'Arrow functions', 'option2' => 'Classes', 'option3' => 'Modules', 'option4' => 'All of above', 'answer' => 4],
        ['question_text' => 'JavaScript runtime?', 'option1' => 'Node.js', 'option2' => 'Deno', 'option3' => 'Bun', 'option4' => 'All of above', 'answer' => 4],
    ],
    
    // Java Questions (technology_id = 4) - 15 questions
    4 => [
        ['question_text' => 'Java language type?', 'option1' => 'Object-Oriented', 'option2' => 'Procedural', 'option3' => 'Functional', 'option4' => 'Markup', 'answer' => 1],
        ['question_text' => 'JVM stands for?', 'option1' => 'Java Variable Machine', 'option2' => 'Java Virtual Machine', 'option3' => 'Java Visual Machine', 'option4' => 'Just Virtual Memory', 'answer' => 2],
        ['question_text' => 'Java file extension?', 'option1' => '.java', 'option2' => '.class', 'option3' => '.js', 'option4' => '.jav', 'answer' => 1],
        ['question_text' => 'Inheritance keyword?', 'option1' => 'extends', 'option2' => 'implements', 'option3' => 'inherits', 'option4' => 'super', 'answer' => 1],
        ['question_text' => 'Create object in Java?', 'option1' => 'new ClassName()', 'option2' => 'ClassName.new()', 'option3' => 'create ClassName()', 'option4' => 'object ClassName()', 'answer' => 1],
        ['question_text' => 'Entry point method?', 'option1' => 'main()', 'option2' => 'start()', 'option3' => 'run()', 'option4' => 'execute()', 'answer' => 1],
        ['question_text' => 'Compare strings in Java?', 'option1' => '==', 'option2' => 'equals()', 'option3' => 'compare()', 'option4' => 'Both A and B', 'answer' => 2],
        ['question_text' => 'Exception handling?', 'option1' => 'try-catch', 'option2' => 'throw', 'option3' => 'throws', 'option4' => 'All', 'answer' => 4],
        ['question_text' => 'Size of int in Java?', 'option1' => '16 bits', 'option2' => '32 bits', 'option3' => '64 bits', 'option4' => '8 bits', 'answer' => 2],
        ['question_text' => 'Collection with insertion order?', 'option1' => 'HashMap', 'option2' => 'TreeSet', 'option3' => 'ArrayList', 'option4' => 'HashSet', 'answer' => 3],
        ['question_text' => 'Java access modifier?', 'option1' => 'public', 'option2' => 'private', 'option3' => 'protected', 'option4' => 'All', 'answer' => 4],
        ['question_text' => 'Interface keyword?', 'option1' => 'interface', 'option2' => 'abstract', 'option3' => 'implements', 'option4' => 'extends', 'answer' => 1],
        ['question_text' => 'Java compiler command?', 'option1' => 'javac', 'option2' => 'java', 'option3' => 'javacompile', 'option4' => 'compile', 'answer' => 1],
        ['question_text' => 'Java version released?', 'option1' => 'Java 8', 'option2' => 'Java 11', 'option3' => 'Java 17', 'option4' => 'All of above', 'answer' => 4],
        ['question_text' => 'Java framework examples?', 'option1' => 'Spring', 'option2' => 'Hibernate', 'option3' => 'Struts', 'option4' => 'All of above', 'answer' => 4],
    ]
];

$total_inserted = 0;
$total_skipped = 0;

// First, get all existing question texts
$existing_questions = [];
$result = $conn->query("SELECT question_text FROM question");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $existing_questions[] = $row['question_text'];
    }
}

foreach ($questions_by_tech as $tech_id => $questions) {
    $tech_inserted = 0;
    $tech_skipped = 0;
    
    foreach ($questions as $question) {
        // Check if question text exists in our array
        if (in_array($question['question_text'], $existing_questions)) {
            $tech_skipped++;
            $total_skipped++;
            continue; // Skip existing questions
        }
        
        $sql = "INSERT INTO question (technology_id, question_text, option1, option2, option3, option4, answer) 
                VALUES (
                    $tech_id,
                    '" . $conn->real_escape_string($question['question_text']) . "',
                    '" . $conn->real_escape_string($question['option1']) . "',
                    '" . $conn->real_escape_string($question['option2']) . "',
                    '" . $conn->real_escape_string($question['option3']) . "',
                    '" . $conn->real_escape_string($question['option4']) . "',
                    {$question['answer']}
                )";
        
        if ($conn->query($sql)) {
            $tech_inserted++;
            $total_inserted++;
            $existing_questions[] = $question['question_text']; // Add to existing array to avoid duplicates in same run
        } else {
            echo "Error inserting question: " . $conn->error . "<br>";
        }
    }
    
    echo "Technology ID $tech_id: $tech_inserted questions added, $tech_skipped skipped (already exist)<br>";
}

// Final count
$result = $conn->query("SELECT technology_id, COUNT(*) as count FROM question GROUP BY technology_id");
$final_counts = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $final_counts[] = $row;
    }
}

echo json_encode([
    'total_questions_added' => $total_inserted,
    'total_questions_skipped' => $total_skipped,
    'final_counts_by_technology' => $final_counts,
    'message' => "Added $total_inserted new questions, skipped $total_skipped existing questions"
], JSON_PRETTY_PRINT);

$conn->close();
?>