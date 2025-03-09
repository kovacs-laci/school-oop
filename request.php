<?php
//require_once("db.php");
function requestHandle()
{
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    switch ($requestMethod) {
        case 'POST': handlePostRequest(); break;
        case 'GET': handleGetRequest(); break;
    }
}

function handlePostRequest()
{
    $requestUri = $_SERVER['REQUEST_URI'];
    switch ($requestUri) {
        case '/school/year':
            // Read JSON input
            $input = json_decode(file_get_contents('php://input'), true);

            // Validate and sanitize input
            $year = isset($input['year']) ? intval($input['year']) : null;

            if ($year) {
                // Fetch classes for the year
                $classes = getClassesByYear($year);

                // Respond with JSON
                header('Content-Type: application/json');
                echo json_encode(['classes' => $classes]);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Invalid year provided.']);
            }
            break;
        case '/school/class':
            $input = json_decode(file_get_contents('php://input'), true);
            $classId = isset($input['classId']) ? $input['classId'] : null;

            if ($classId) {
                $class = getClass($classId);
                $classAvg = getClassAvg($classId);
                $class['avg'] = $classAvg['value'];
                $students = getStudentsAvgByClassId($classId);
                header('Content-Type: application/json');
                echo json_encode(['students' => $students, 'class' => $class]);
            } else {
                header('Content-Type: application/json', true, 400);
                echo json_encode(['error' => 'Invalid class code provided.']);
            }
            break;
        case '/school/student':
            $input = json_decode(file_get_contents('php://input'), true);
            $studentId = isset($input['studentId']) ? $input['studentId'] : null;
            if ($studentId) {
                $subjects = getStudentAvgDetails($studentId);
                header('Content-Type: application/json');
                echo json_encode(['subjects' => $subjects]);
            }
            break;
        case '/school/subject':
            $input = json_decode(file_get_contents('php://input'), true);
            $studentId = isset($input['studentId']) ? $input['studentId'] : null;
            $subjectId = isset($input['subjectId']) ? $input['subjectId'] : null;
            if ($studentId && $subjectId) {
                $marks = getStudentMarksBySubjectId($studentId, $subjectId);
                header('Content-Type: application/json');
                echo json_encode(['marks' => $marks]);
            }
            break;
    }

    if (isset($_POST['btn-install'])) {
        $result = install(isset($_POST['with-data']));
        if ($result) {
            echo "<p>A telepítés sikeres, frissítse az oldalt.</p>";
        }
    }

}

function handleGetRequest()
{
    $requestUri = $_SERVER['REQUEST_URI'];
    switch ($requestUri) {
//        case "/school/":
//            if (dbExists()) {
//                $years = getYears();
//                displayYears($years);
//            }
//            break;

        case str_starts_with($requestUri,'/school/classes'):

            if (isset($_GET['subject-id'])) {
                $classId = $_GET['class-id'];
                $studentId = $_GET['student-id'];
                $subjectId = $_GET['subject-id'];
                $marks = getStudentMarksBySubjectId($studentId, $subjectId);
                displayStudentMarksBySubject($marks, $studentId, $subjectId);
                break;
            }
            if (isset($_GET['student-id'])) {
                $classId = $_GET['class-id'];
                $studentId = $_GET['student-id'];
                $student = getStudent($studentId);
                $studentAvgDetails = getStudentAvgDetails($studentId);
                displayStudentAvgDetails($student, $studentAvgDetails, $classId);
                break;
            }
            if (isset($_GET['class-id'])) {
                $classId = $_GET['class-id'];
                $students = getStudentsAvgByClassId($classId);
                displayStudents($students, $classId);
                break;
            }
            $classes = getClasses();
            displayClasses($classes);
            break;
    }
}

function install($withData, $dbName = DB_NAME)
{
    // Ensure messages are displayed immediately
    if (php_sapi_name() !== 'cli') { // Only flush for non-CLI (e.g., web server)
        ob_implicit_flush(true);
        ob_end_flush();
    }
    displayMessage("'$dbName' Adatbázis létrehozása.", 'info');
    $result = createDb($dbName);
    if (!$result) {
        displayMessage('Nem sikerült létrehozni.', 'error');
        return false;
    }
    displayMessage('Adatbázis sikeresen létrehozva.', 'success');

    displayMessage("'subjects' tábla létrehozása.", 'info');
    $result = createTableSubjects($dbName);
    if (!$result) {
        displayMessage('Nem sikerült létrehozni.', 'error');
        return false;
    }
    displayMessage('Sikeresen létrehozva.', 'success');

    displayMessage("'classes' tábla létrehozása.", 'info');
    $result = createTableClasses($dbName);
    if (!$result) {
        displayMessage('Nem sikerült létrehozni.', 'error');
        return false;
    }
    displayMessage('Sikeresen létrehozva.', 'success');

    displayMessage("'students' tábla létrehozása.", 'info');
    $result = createTableStudents($dbName);
    if (!$result) {
        displayMessage('Nem sikerült létrehozni.', 'error');
        return false;
    }
    displayMessage('Sikeresen létrehozva.', 'success');

    displayMessage("'marks' tábla létrehozása.", 'info');
    $result = createTableMarks($dbName);
    if (!$result) {
        displayMessage('Nem sikerült létrehozni.', 'error');
        return false;
    }
    displayMessage('Sikeresen létrehozva.', 'success');

    if ($withData) {
        displayMessage('Adatok feltöltése', 'info');

        displayMessage('- subjects', 'info');
        $result = addSubjects(SUBJECTS);
        if (is_array($result)) {
            $errors = implode(", ", $result);
            displayMessage("$errors hozzáadása nem sikerült");
            return false;
        }
        displayMessage("'subjects' hozzáadása sikeres.", 'success');

        displayMessage('- classes', 'info');
        $result = addClasses(YEARS,CLASSES);
        if (is_array($result)) {
            $errors = implode(", ", $result);
            displayMessage("$errors hozzáadása nem sikerült");
            return false;
        }
        displayMessage("'classes' hozzáadása sikeres.", 'success');

        displayMessage('- students', 'info');
        $result = addStudents();
        if (is_array($result)) {
            $errors = implode(", ", $result);
            displayMessage("$errors hozzáadása nem sikerült");
            return false;
        }
        displayMessage("'students' hozzáadása sikeres.", 'success');

        displayMessage('- marks', 'info');
        $result = addMarks();
        if (is_array($result)) {
            $errors = implode(", ", $result);
            displayMessage("$errors hozzáadása nem sikerült");
            return false;
        }
        displayMessage("'marks' hozzáadása sikeres.", 'success');

        if ($result) {
            displayMessage('Az adatbázis sikeresen létrehozva és adatokkal feltöltve.', 'success');

            return true;
        }
    }

    if ($result) {
        displayMessage('Az adatbázis sikeresen létrehozva.', 'success');

        return true;
    }
}